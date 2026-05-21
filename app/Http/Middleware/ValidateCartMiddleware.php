<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateCartMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Validate cart exists and has items
        $cartService = app(\App\Services\CartService::class);
        $cart = $cartService->getCart();

        if ($cart->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        // Add cart to request for easy access
        $request->merge(['cart' => $cart]);

        return $next($request);
    }
}