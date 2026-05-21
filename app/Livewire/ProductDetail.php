<?php

namespace App\Livewire;

use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    public $quantity = 1;
    public $selectedImage = 0;
    public $errorMessage = null;

    public function mount($slug)
    {
        $this->product = \App\Models\Product::where('slug', $slug)
             ->with('category')
             ->firstOrFail();
    }

    public function addToCart(\App\Services\CartService $cartService)
    {
        $this->errorMessage = null;
        try {
            $cartService->addProduct($this->product->id, $this->quantity);
            $this->dispatch('cart-updated');
            $this->dispatch('product-added', quantity: $this->quantity, price: (float)$this->product->price);
            session()->flash('message', $this->product->name . ' dodany do koszyka!');
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

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function render()
    {
        return view('livewire.product-detail')
            ->layout('layouts.app')
            ->title($this->product->seo_title);
    }
}