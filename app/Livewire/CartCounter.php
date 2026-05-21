<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $count = 0;

    #[On('cart-updated')]
    #[On('product-added')]
    #[On('cart-item-removed')]
    public function updateCount(CartService $cartService)
    {
        $this->count = $cartService->getCartSummary()['item_count'];
    }

    public function render(CartService $cartService)
    {
        $this->count = $cartService->getCartSummary()['item_count'];
        return view('livewire.cart-counter');
    }
}
