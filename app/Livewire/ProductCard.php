<?php

namespace App\Livewire;

use Livewire\Component;

class ProductCard extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart(\App\Services\CartService $cartService)
    {
        $cartService->addProduct($this->product->id, 1);
        
        $this->dispatch('cart-updated');
        $this->dispatch('product-added');
        
        $this->dispatch('gtag-event', [
            'event' => 'add_to_cart',
            'data' => [
                'currency' => 'PLN',
                'value' => (float) $this->product->price,
                'items' => [
                    [
                        'item_id' => (string) $this->product->id,
                        'item_name' => $this->product->name,
                        'item_brand' => 'Nevro',
                        'price' => (float) $this->product->price,
                        'quantity' => 1,
                    ]
                ]
            ]
        ]);
        
        session()->flash('message', $this->product->name . ' added to cart!');
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}