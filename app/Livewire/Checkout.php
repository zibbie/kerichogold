<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use App\Models\Order;
use Exception;

use Livewire\Attributes\On;

class Checkout extends Component
{
    #[On('cart-updated')]
    public function refreshCart(CartService $cartService)
    {
        if ($cartService->getCart()->items->isEmpty()) {
            return redirect('/');
        }
        
        $this->updateShippingMethods($cartService);
    }

    public $email;
    public $name;
    public $address;
    public $city;
    public $zip;
    public $phone;
    public $payment_method = 'BLIK';
    public $blik_code; // Must be public for wire:model binding
    public $parcel_locker;
    public $selected_shipping = 'courier';
    public $wants_invoice = false;
    public $nip; // Must be public for wire:model binding
    public $cod_fee;
    public $ga_client_id;
    public $isProcessing = false;
    
    public function hydrate()
    {
        $this->cod_fee = (float) \App\Models\Setting::get('cod_fee', 10.00);
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'name' => 'required|string|min:3|regex:/[\pL]/u',
            'address' => 'required|string|regex:/[\pL]/u',
            'city' => 'required|string|regex:/[\pL]/u',
            'zip' => 'required|regex:/^[0-9]{2}-?[0-9]{3}$/',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'payment_method' => 'required|string',
            'selected_shipping' => 'required',
            'parcel_locker' => 'required_if:selected_shipping,paczkomat',
            'wants_invoice' => 'boolean',
            'nip' => 'required_if:wants_invoice,true|nullable|string|size:10',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Adres e-mail jest wymagany.',
            'email.email' => 'Adres e-mail jest nieprawidłowy.',
            'name.required' => 'Imię i nazwisko (lub nazwa firmy) są wymagane.',
            'name.min' => 'Imię i nazwisko muszą mieć co najmniej 3 znaki.',
            'name.regex' => 'Imię i nazwisko (lub firma) muszą zawierać litery.',
            'address.required' => 'Ulica i numer domu są wymagane.',
            'address.regex' => 'Adres musi zawierać nazwę ulicy lub miejscowości (litery).',
            'city.required' => 'Miejscowość jest wymagana.',
            'city.regex' => 'Nazwa miejscowości musi zawierać litery.',
            'zip.required' => 'Kod pocztowy jest wymagany.',
            'zip.regex' => 'Kod pocztowy musi składać się z 5 cyfr (np. 00-000).',
            'phone.required' => 'Numer telefonu jest wymagany.',
            'phone.min' => 'Numer telefonu musi mieć co najmniej 9 cyfr.',
            'phone.regex' => 'Numer telefonu ma nieprawidłowy format.',
            'nip.size' => 'Numer NIP musi mieć dokładnie 10 cyfr.',
            'nip.required_if' => 'Numer NIP jest wymagany, gdy chcesz otrzymać fakturę.',
            'parcel_locker.required_if' => 'Proszę wybrać paczkomat na mapie.',
        ];
    }

    public function updatedZip($value)
    {
        $digits = preg_replace('/[^0-9]/', '', $value);
        if (strlen($digits) === 5) {
            $this->zip = substr($digits, 0, 2) . '-' . substr($digits, 2);
        }
    }

    public function updatedNip($value)
    {
        $this->nip = str_replace([' ', '-'], '', trim($value));
    }

    public function mount(CartService $cartService)
    {
        $this->cod_fee = (float) \App\Models\Setting::get('cod_fee', 10.00);
        
        if ($cartService->getCart()->items->isEmpty()) {
            return redirect('/');
        }

        $summary = $cartService->getCartSummary();
        $this->updateShippingMethods($cartService);

        $this->dispatch('gtag-event', [
            'event' => 'begin_checkout',
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

    public function setParcelLocker($lockerId)
    {
        $this->parcel_locker = $lockerId;
        $this->updatedSelectedShipping(app(CartService::class));
    }

    public function getShippingMethods(CartService $cartService)
    {
        $shippingService = app(\App\Services\ShippingService::class);
        return $shippingService->getAvailableMethods($cartService->getCart());
    }

    public function updateShippingMethods(CartService $cartService)
    {
        $shippingMethods = $this->getShippingMethods($cartService);
        
        if (!isset($shippingMethods[$this->selected_shipping])) {
            $this->selected_shipping = array_key_first($shippingMethods);
        }

        $this->updatedSelectedShipping($cartService);
    }

    public function updatedSelectedShipping(CartService $cartService)
    {
        $shippingMethods = $this->getShippingMethods($cartService);
        if (!isset($shippingMethods[$this->selected_shipping])) return;

        $method = $shippingMethods[$this->selected_shipping];
        $price = $method['price'];

        // Add COD fee if selected
        if ($this->payment_method === 'COD') {
            $price += $this->cod_fee;
        }

        $cartService->setShippingMethod($this->selected_shipping, $price, [
            'name' => $method['name'],
            'parcel_locker' => $this->parcel_locker,
            'base_price' => $method['price'],
            'cod_fee' => $this->payment_method === 'COD' ? $this->cod_fee : 0
        ]);

        $summary = $cartService->getCartSummary();
        $this->dispatch('gtag-event', [
            'event' => 'add_shipping_info',
            'data' => [
                'currency' => 'PLN',
                'value' => (float) $summary['total'],
                'shipping_tier' => $method['name'],
                'items' => collect($summary['items'])->map(fn($item) => [
                    'item_id' => (string) $item['product_id'],
                    'item_name' => $item['product_name'],
                    'price' => (float) $item['price'],
                    'quantity' => $item['quantity'],
                ])->toArray(),
            ]
        ]);
    }

    public function updatedPaymentMethod(CartService $cartService)
    {
        $this->updatedSelectedShipping($cartService);
        
        $summary = $cartService->getCartSummary();
        $this->dispatch('gtag-event', [
            'event' => 'add_payment_info',
            'data' => [
                'currency' => 'PLN',
                'value' => (float) $summary['total'],
                'payment_type' => $this->payment_method,
                'items' => collect($summary['items'])->map(fn($item) => [
                    'item_id' => (string) $item['product_id'],
                    'item_name' => $item['product_name'],
                    'price' => (float) $item['price'],
                    'quantity' => $item['quantity'],
                ])->toArray(),
            ]
        ]);
    }

    public function placeOrder(CartService $cartService, \App\Services\Przelewy24Service $paymentService)
    {
        if ($this->isProcessing) {
            \Illuminate\Support\Facades\Log::warning('placeOrder aborted due to concurrent processing flag');
            return;
        }
        $this->isProcessing = true;

        \Illuminate\Support\Facades\Log::debug('placeOrder called', [
            'payment_method' => $this->payment_method,
            'email' => $this->email,
        ]);

        // Guard: Ensure session/auth context
        if (!\Illuminate\Support\Facades\Auth::check() && !session()->has('cart_session_id')) {
            $this->isProcessing = false;
            return redirect('/');
        }

        try {
            $this->validate();
            \Illuminate\Support\Facades\Log::debug('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::debug('Validation failed', ['errors' => $e->errors()]);
            $this->isProcessing = false;
            throw $e;
        }

        try {
            // 0. Ensure shipping is set correctly (re-verifies server-side in CartService)
            $this->updatedSelectedShipping($cartService);

            // 1. Update addresses in cart
            $addressData = [
                'name' => $this->name,
                'address' => $this->address,
                'city' => $this->city,
                'zip' => $this->zip,
                'phone' => $this->phone,
                'email' => $this->email,
            ];

            $cartService->setShippingAddress($addressData);
            $cartService->setBillingAddress($addressData);

            $gaCookie = $this->ga_client_id ?: request()->cookie('_ga');
            if ($gaCookie) {
                // Extract only the CID part (XXXXXXXXXX.YYYYYYYYYY) from GA1.1.XXXXXXXXXX.YYYYYYYYYY
                if (preg_match('/(?:GA1\.\d\.)?(\d+\.\d+)/', $gaCookie, $matches)) {
                    $gaCookie = $matches[1];
                }
            }

            $order = $cartService->convertToOrder($this->payment_method, [
                'wants_invoice' => $this->wants_invoice,
                'nip' => $this->nip,
                'ga_client_id' => $gaCookie,
            ]);
            
            // Send Admin Notification immediately for all orders (even if unpaid yet)
            try {
                $adminEmails = \App\Models\Setting::get('admin_emails', 'info@nevro-wm.pl');
                $emails = array_map('trim', explode(',', $adminEmails));
                
                \Illuminate\Support\Facades\Mail::to($emails)
                    ->queue(new \App\Mail\AdminOrderNotificationMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Admin Mail queuing failed', ['error' => $e->getMessage()]);
            }

            // 3. Track Purchase (Server-Side)
            try {
                app(\App\Services\AnalyticsService::class)->trackPurchase($order);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('GA4 Server-Side Purchase Tracking failed', ['error' => $e->getMessage()]);
            }

            // 4. Finalize if COD
            if ($this->payment_method === 'COD') {
                // Status remains 'pending' as requested by the user for "Oczekujące"
                
                // Clear cart only after successful save
                $cartService->clearCart();

                // Send confirmation email to Customer
                try {
                    \Illuminate\Support\Facades\Mail::to($order->email)
                        ->queue(new \App\Mail\OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Customer Mail sending failed (COD)', ['error' => $e->getMessage()]);
                }

                return redirect()->route('payment.status', ['transactionId' => $order->order_number, 'status' => 'success']);
            }

            $paymentResult = $paymentService->registerTransaction($order, $this->payment_method);

            if ($paymentResult['success'] && isset($paymentResult['redirect_url'])) {
                $cartService->clearCart(); // Safe to clear now
                return redirect()->away($paymentResult['redirect_url']);
            }

            throw new Exception('Payment initiation failed: ' . ($paymentResult['error'] ?? 'Unknown error'));

        } catch (Exception $e) {
            $this->isProcessing = false;
            session()->flash('error', $e->getMessage());
        }
    }

    public function render(CartService $cartService)
    {
        return view('livewire.checkout', [
            'cart' => $cartService->getCartSummary(),
            'shipping_methods' => $this->getShippingMethods($cartService)
        ])->layout('layouts.app');
    }
}
