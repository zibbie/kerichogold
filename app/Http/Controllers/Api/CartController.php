<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return response()->json($this->cartService->getCartSummary());
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
            'options' => 'array',
            'customizations' => 'array',
        ]);

        try {
            $cart = $this->cartService->addProduct(
                $request->product_id,
                $request->quantity ?? 1,
                $request->options ?? [],
                $request->customizations ?? []
            );
            return response()->json($this->cartService->getCartSummary());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $this->cartService->updateQuantity($itemId, $request->quantity);
            return response()->json($this->cartService->getCartSummary());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function removeItem($itemId)
    {
        try {
            $this->cartService->removeItem($itemId);
            return response()->json($this->cartService->getCartSummary());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function clear()
    {
        $this->cartService->clearCart();
        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function validateCart()
    {
        try {
            $this->cartService->validateCart();
            return response()->json(['valid' => true]);
        } catch (Exception $e) {
            return response()->json(['valid' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
