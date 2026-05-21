<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Services\CartService;
use App\Livewire\Checkout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function test_can_place_a_cod_order_with_courier()
    {
        // 1. Przygotowanie produktu
        $product = Product::forceCreate([
            'name' => 'Testowy Produkt',
            'price' => 100.00,
            'quantity' => 10,
            'status' => true,
            'sku' => 'TEST-SKU',
            'slug' => 'testowy-produkt',
        ]);

        // 2. Dodanie do koszyka (manualnie w sesji)
        $cartService = app(CartService::class);
        $cartService->addProduct($product->id, 1);

        \App\Models\Setting::set('admin_emails', 'kontakt@kerichogold.pl,magdalena@kerichogold.pl');

        // 3. Test komponentu Checkout
        Livewire::test(Checkout::class)
            ->set('email', 'test@example.com')
            ->set('name', 'Jan Testowy')
            ->set('address', 'Testowa 123')
            ->set('city', 'Warszawa')
            ->set('zip', '00-001')
            ->set('phone', '123456789')
            ->set('payment_method', 'COD')
            ->set('selected_shipping', 'courier')
            ->call('placeOrder')
            ->assertRedirect();

        // 4. Weryfikacja zamówienia w bazie
        $order = Order::where('email', 'test@example.com')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('COD', $order->payment_method);
        $this->assertEquals('pending', $order->status);

        // 5. Weryfikacja maili
        Mail::assertQueued(OrderConfirmationMail::class);
        Mail::assertQueued(AdminOrderNotificationMail::class, function ($mail) {
            return $mail->hasTo('kontakt@kerichogold.pl') && $mail->hasTo('magdalena@kerichogold.pl');
        });
        
        // Sprzątanie po teście
        $order->delete();
    }

    /** @test */
    public function test_requires_parcel_locker_for_paczkomat_shipping()
    {
        $product = Product::forceCreate([
            'name' => 'Testowy Produkt 2',
            'price' => 50.00,
            'quantity' => 10,
            'status' => true,
            'sku' => 'TEST-SKU-2',
            'slug' => 'testowy-produkt-2',
        ]);
        $cartService = app(CartService::class);
        $cartService->addProduct($product->id, 1);

        Livewire::test(Checkout::class)
            ->set('selected_shipping', 'paczkomat')
            ->set('parcel_locker', '')
            ->call('placeOrder')
            ->assertHasErrors(['parcel_locker' => 'required_if']);
    }

    /** @test */
    public function test_calculates_total_price_with_cod_fee()
    {
        $product = Product::forceCreate([
            'name' => 'Testowy Produkt 3',
            'price' => 100.00,
            'quantity' => 10,
            'status' => true,
            'sku' => 'TEST-SKU-3',
            'slug' => 'testowy-produkt-3',
        ]);
        $cartService = app(CartService::class);
        $cartService->addProduct($product->id, 1);

        $basePrice = $product->price;
        
        Livewire::test(Checkout::class)
            ->set('payment_method', 'COD')
            ->set('selected_shipping', 'courier')
            ->assertSet('cod_fee', 10.00);
            
        $summary = $cartService->getCartSummary();
        // Sprawdzamy czy suma w serwisie koszyka uwzględnia shipping + cod_fee
        // Shipping dla kuriera to np. 15 PLN + 10 PLN COD
        $this->assertGreaterThan($basePrice, $summary['total']);
    }

    /** @test */
    public function test_cart_handles_insufficient_stock_gracefully()
    {
        // 1. Stworzenie produktu o niskim stanie magazynowym (1 sztuka)
        $product = Product::forceCreate([
            'name' => 'Unikalny Produkt',
            'price' => 120.00,
            'quantity' => 1,
            'status' => true,
            'sku' => 'UNIQUE-SKU',
            'slug' => 'unikalny-produkt',
        ]);

        $cartService = app(CartService::class);
        $cart = $cartService->addProduct($product->id, 1);
        $item = $cart->items->first();

        // 2. Test dla komponentu Cart (boczny koszyk)
        Livewire::test(\App\Livewire\Cart::class)
            ->call('incrementQuantity', $item->id)
            ->assertSet('errorMessage', 'Brak wystarczającej ilości produktu w magazynie.');

        // 3. Test dla komponentu CartPage (główna strona koszyka)
        Livewire::test(\App\Livewire\CartPage::class)
            ->call('incrementQuantity', $item->id)
            ->assertSet('errorMessage', 'Brak wystarczającej ilości produktu w magazynie.');

        // 4. Test dla ProductDetail (dodanie ponad stan magazynowy)
        Livewire::test(\App\Livewire\ProductDetail::class, ['slug' => 'unikalny-produkt'])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertSet('errorMessage', 'Brak wystarczającej ilości produktu w magazynie.');
    }
}
