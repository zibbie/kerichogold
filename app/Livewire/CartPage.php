<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;

class CartPage extends Component
{
    public $errorMessage = null;

    public function removeItem($itemId, CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $cartService->removeItem($itemId);
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateQuantity($itemId, $quantity, CartService $cartService)
    {
        $this->errorMessage = null;
        if ($quantity < 1) {
            $this->removeItem($itemId, $cartService);
            return;
        }
        
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
        return view('livewire.cart-page', [
            'cart' => $cartService->getCartSummary()
        ])->layout('layouts.app')->title('Twój Koszyk - Kericho Gold');
    }
}
