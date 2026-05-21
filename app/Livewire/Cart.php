<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

class Cart extends Component
{
    public $isOpen = false;
    public $errorMessage = null;

    #[On('product-added')]
    #[On('open-cart')]
    public function openCart()
    {
        $this->isOpen = true;
        
        $summary = app(CartService::class)->getCartSummary();
        $this->dispatch('gtag-event', [
            'event' => 'view_cart',
            'data' => [
                'currency' => 'PLN',
                'value' => (float) $summary['total'],
                'items' => collect($summary['items'])->map(fn($item) => [
                    'item_id' => (string) $item['product_id'],
                    'item_name' => $item['product_name'],
                    'item_brand' => 'Nevro',
                    'price' => (float) $item['price'],
                    'quantity' => $item['quantity'],
                ])->toArray(),
            ]
        ]);
    }

    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function removeItem($itemId, CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $summary = $cartService->getCartSummary();
            $item = collect($summary['items'])->firstWhere('id', $itemId);
            
            $cartService->removeItem($itemId);
            
            if ($item) {
                $this->dispatch('gtag-event', [
                    'event' => 'remove_from_cart',
                    'data' => [
                        'currency' => 'PLN',
                        'value' => (float) $item['price'] * $item['quantity'],
                        'items' => [[
                            'item_id' => (string) $item['product_id'],
                            'item_name' => $item['product_name'],
                            'price' => (float) $item['price'],
                            'quantity' => $item['quantity'],
                        ]],
                    ]
                ]);
            }
            
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateQuantity($itemId, $quantity, CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $cartService->updateQuantity($itemId, $quantity);
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function incrementQuantity($itemId, CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $summary = $cartService->getCartSummary();
            $item = collect($summary['items'])->firstWhere('id', $itemId);
            if ($item) {
                $cartService->updateQuantity($itemId, $item['quantity'] + 1);
                $this->dispatch('cart-updated');
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function decrementQuantity($itemId, CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $summary = $cartService->getCartSummary();
            $item = collect($summary['items'])->firstWhere('id', $itemId);
            if ($item) {
                $newQuantity = $item['quantity'] - 1;
                if ($newQuantity < 1) {
                    $this->removeItem($itemId, $cartService);
                } else {
                    $cartService->updateQuantity($itemId, $newQuantity);
                    $this->dispatch('cart-updated');
                }
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    protected function handleException(\Exception $e)
    {
        $message = $e->getMessage();
        if (str_contains($message, 'Insufficient stock')) {
            $this->errorMessage = 'Brak wystarczającej ilości produktu w magazynie.';
        } elseif (str_contains($message, 'Product is not available')) {
            $this->errorMessage = 'Ten produkt nie jest obecnie dostępny.';
        } else {
            $this->errorMessage = 'Wystąpił błąd: ' . $message;
        }
    }

    public function render(CartService $cartService)
    {
        return view('livewire.cart', [
            'cart' => $cartService->getCartSummary()
        ]);
    }
}
