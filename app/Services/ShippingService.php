<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class ShippingService
{
    /**
     * Calculate shipping cost for a given cart and selected group (paczkomat or courier)
     * 
     * @throws AuthorizationException
     */
    public function calculate(Cart $cart, $type = 'courier')
    {
        $this->validateOwnership($cart);

        $items = $cart->relationLoaded('items') ? $cart->items : $cart->items()->with('product.category')->get();
        $totalCostGrosze = 0;
        
        $groups = [
            'regular' => [],
            'accessories' => [],
            'per_package' => []
        ];

        foreach ($items as $item) {
            $product = $item->product;
            if (!$product) continue;

            $isAccessory = $product->category && in_array(
                $product->category->slug, 
                config('shipping.accessory_categories', [])
            );

            $class = $product->shipping_class ?? 'courier_standard';

            if ($isAccessory) {
                $groups['accessories'][$class] = true;
            } else {
                $groups['regular'][$class] = ($groups['regular'][$class] ?? 0) + $item->quantity;
                
                $groups['per_package'][$class] = min(
                    $groups['per_package'][$class] ?? 999, 
                    $product->items_per_package ?: config('shipping.default_items_per_package', 1)
                );
            }
        }

        // 1. Process regular items (Consolidated by shipping class)
        foreach ($groups['regular'] as $class => $totalQuantity) {
            $rate = $this->getRateForClass($class, $type);
            $itemsPerPackage = $groups['per_package'][$class];
            
            $packages = ceil($totalQuantity / $itemsPerPackage);
            $ratePriceGrosze = (int) round($rate['price'] * 100);
            $totalCostGrosze += ($ratePriceGrosze * $packages);
        }

        // 2. Process accessory items (Only once per class group)
        if (!empty($groups['accessories'])) {
            $maxAccessoryCostGrosze = 0;
            
            foreach (array_keys($groups['accessories']) as $class) {
                $rate = $this->getRateForClass($class, $type);
                $ratePriceGrosze = (int) round($rate['price'] * 100);
                if ($ratePriceGrosze > $maxAccessoryCostGrosze) {
                    $maxAccessoryCostGrosze = $ratePriceGrosze;
                }
            }
            
            $totalCostGrosze += $maxAccessoryCostGrosze;
        }

        return round($totalCostGrosze / 100, 2);
    }

    public function getAvailableMethods(Cart $cart)
    {
        $this->validateOwnership($cart);
        
        $items = $cart->relationLoaded('items') ? $cart->items : $cart->items()->with('product')->get();
        $canUsePaczkomat = true;

        foreach ($items as $item) {
            $class = $item->product->shipping_class;
            // Paczkomat is available for paczkomat classes AND courier_standard (small items)
            if (!str_contains($class, 'paczkomat') && $class !== 'courier_standard') {
                $canUsePaczkomat = false;
                break;
            }
        }

        $methods = [];
        
        // Courier is always available
        $methods['courier'] = [
            'id' => 'courier',
            'name' => 'Kurier',
            'price' => $this->calculate($cart, 'courier'),
        ];

        if ($canUsePaczkomat) {
            try {
                $paczkomatPrice = $this->calculate($cart, 'paczkomat');
                $methods['paczkomat'] = [
                    'id' => 'paczkomat',
                    'name' => 'InPost Paczkomat',
                    'price' => $paczkomatPrice,
                ];
            } catch (\Exception $e) {
                // If paczkomat calculation fails, just don't offer it
                \Illuminate\Support\Facades\Log::warning("Paczkomat not available for cart {$cart->id}: " . $e->getMessage());
            }
        }

        return $methods;
    }

    protected function validateOwnership(Cart $cart)
    {
        if (Auth::check()) {
            if ($cart->user_id && $cart->user_id !== Auth::id()) {
                throw new AuthorizationException('Access denied to this cart.');
            }
        } else {
            // For guest carts, verify against session-based ID to prevent IDOR
            $sessionCartId = session('cart_session_id');
            if ($cart->user_id !== null || (string)$cart->session_id !== (string)$sessionCartId) {
                 throw new AuthorizationException('Access denied to this cart.');
            }
        }
    }

    protected function getRateForClass($class, $requestedType)
    {
        $rates = config('shipping.rates', []);
        
        if (isset($rates[$class])) {
            $rate = $rates[$class];
            
            if ($rate['type'] === $requestedType) {
                return $rate;
            }
            
            // Fallback logic
            if ($requestedType === 'courier') {
                return $rates['courier_standard'];
            }
            
            if ($requestedType === 'paczkomat' && $class === 'courier_standard') {
                return $rates['paczkomat_c'];
            }
            
            throw new \RuntimeException("No shipping rate found for class '{$class}' and type '{$requestedType}'.");
        }

        throw new \RuntimeException("Unknown shipping class: {$class}");
    }
}
