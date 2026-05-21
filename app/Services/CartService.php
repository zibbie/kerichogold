<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class CartService
{
    protected $sessionKey = 'cart_session_id';

    /**
     * Get or create cart for current session/user
     */
    public function getCart()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::id();

        // Try to find existing cart with unambiguous priority: user_id > session_id
        if ($userId) {
            $cart = Cart::where('user_id', $userId)->first()
                ?? Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        } else {
            $cart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        }

        if (!$cart) {
            $cart = $this->createCart($sessionId, $userId);
        }

        // Merge guest cart with user cart if user just logged in
        if ($userId && $cart->user_id !== $userId) {
            $userCart = Cart::where('user_id', $userId)->first();
            if ($userCart) {
                $this->mergeCarts($cart, $userCart);
                $cart = $userCart;
            } else {
                $cart->update(['user_id' => $userId]);
            }
        }

        return $cart->load('items.product');
    }

    /**
     * Add product to cart
     */
    public function addProduct($productId, $quantity = 1, $options = [], $customizations = [])
    {
        // Use pessimistic lock to prevent stock race conditions
        $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();

        // Validate product availability
        if (!$product->status) {
            throw new Exception('Product is not available');
        }

        if ($product->quantity < $quantity) {
            throw new Exception('Insufficient stock');
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            // Update existing item
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->quantity < $newQuantity) {
                throw new Exception('Insufficient stock for requested quantity');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->options = array_merge($cartItem->options ?? [], $options);
            $cartItem->customizations = array_merge($cartItem->customizations ?? [], $customizations);
            $cartItem->updateTotal();
        } else {
            // Create new item
            $price = $product->price;
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'product_image' => $product->image ?? null,
                'quantity' => $quantity,
                'options' => $options,
                'customizations' => $customizations,
            ]);
            $cartItem->product_price = $price;
            $cartItem->total = $price * $quantity;
            $cartItem->save();
        }

        $cart->recalculateTotals();
        return $cart->load('items.product');
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }

        // Check stock
        if ($item->product->quantity < $quantity) {
            throw new Exception('Insufficient stock');
        }

        $item->quantity = $quantity;
        $item->updateTotal();
        $cart->recalculateTotals();

        return $cart->load('items.product');
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        $cart = $this->getCart();
        $cart->items()->findOrFail($itemId)->delete();
        $cart->recalculateTotals();

        return $cart->load('items.product');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->update([
            'subtotal' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'discount_total' => 0,
            'total' => 0,
        ]);

        return $cart;
    }

    /**
     * Set shipping address
     */
    public function setShippingAddress($address)
    {
        $cart = $this->getCart();
        $cart->update(['shipping_address' => $address]);
        return $cart;
    }

    /**
     * Set billing address
     */
    public function setBillingAddress($address)
    {
        $cart = $this->getCart();
        $cart->update(['billing_address' => $address]);
        return $cart;
    }

    public function setShippingMethod($method, $cost, $data = [])
    {
        $cart = $this->getCart();
        $cart->update([
            'shipping_method' => $method,
            'shipping_total' => $cost,
            'shipping_data' => $data
        ]);
        $cart->recalculateTotals();
        return $cart;
    }

    /**
     * Validate cart before checkout
     */
    public function validateCart()
    {
        $cart = $this->getCart();

        if ($cart->isEmpty()) {
            throw new Exception('Cart is empty');
        }

        foreach ($cart->items as $item) {
            if (!$item->product->status) {
                throw new Exception("Product '{$item->product_name}' is no longer available");
            }

            if ($item->product->quantity < $item->quantity) {
                throw new Exception("Insufficient stock for '{$item->product_name}'");
            }

            if (bccomp((string)$item->product->price, (string)$item->product_price, 2) !== 0) {
                // Price changed, update cart
                $item->update(['product_price' => $item->product->price]);
                $item->updateTotal();
            }
        }

        $cart->recalculateTotals();
        return true;
    }

    /**
     * Convert cart to order
     */
    public function convertToOrder($paymentMethod = 'tpay', $extraAttributes = [])
    {
        $cart = $this->getCart();
        $this->validateCart();

        // 0. Re-calculate shipping server-side to prevent client-side price manipulation
        $shippingService = app(\App\Services\ShippingService::class);
        $shippingCost = $shippingService->calculate($cart, $cart->shipping_method ?? 'courier');

        // Add COD fee if cash on delivery is selected
        if ($paymentMethod === 'COD') {
            $shippingCost += (float) \App\Models\Setting::get('cod_fee', 10.00);
        }
        
        // 1. Generate unique order number (more collision-resistant)
        $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        return DB::transaction(function () use ($cart, $paymentMethod, $extraAttributes, $orderNumber, $shippingCost) {
            // 2. Atomic stock decrement check
            foreach ($cart->items as $cartItem) {
                $affected = Product::where('id', $cartItem->product_id)
                    ->where('quantity', '>=', $cartItem->quantity)
                    ->decrement('quantity', $cartItem->quantity);

                if ($affected === 0) {
                    throw new Exception("Niestety produkt '{$cartItem->product_name}' został właśnie wyprzedany.");
                }
            }

            // 3. Create order within transaction
            $shipping = $cart->shipping_address ?? [];
            $orderData = array_merge([
                'user_id' => $cart->user_id,
                'email' => $shipping['email'] ?? null,
                'name' => $shipping['name'] ?? null,
                'phone' => $shipping['phone'] ?? null,
                'city' => $shipping['city'] ?? null,
                'zip' => $shipping['zip'] ?? null,
                'order_number' => $orderNumber,
                'total' => $cart->subtotal + $shippingCost - $cart->discount_total,
                'tax' => $cart->tax_total,
                'shipping_cost' => $shippingCost,
                'shipping_method' => $cart->shipping_method,
                'shipping_data' => $cart->shipping_data,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'billing_address' => $cart->billing_address,
                'shipping_address' => $cart->shipping_address,
                'ordered_at' => now(),
            ], $extraAttributes);

            $order = new Order();
            $order->fill($orderData); // Fills only $fillable fields
            
            // Manually assign protected financial fields to bypass mass-assignment protection
            $order->total = $orderData['total'];
            $order->tax = $orderData['tax'];
            $order->shipping_cost = $orderData['shipping_cost'];
            $order->status = $orderData['status'];
            $order->payment_status = 'pending';
            
            $order->save();

            // 4. Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product_name,
                    'product_sku' => $cartItem->product_sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product_price,
                    'total' => $cartItem->total,
                    'options' => $cartItem->options,
                ]);
            }

            // 5. Cart will be cleared by the caller after successful payment initiation
            // to prevent data loss on payment provider errors.

            dispatch(new \App\Jobs\PushOrderToBaseLinker($order))->afterCommit();

            return $order->load('items');
        });
    }

    /**
     * Get cart summary for API responses
     */
    public function getCartSummary()
    {
        $cart = $this->getCart();

        return [
            'id' => $cart->id,
            'item_count' => $cart->getItemCount(),
            'subtotal' => $cart->subtotal,
            'tax_total' => $cart->tax_total,
            'shipping_total' => $cart->shipping_total,
            'shipping_method' => $cart->shipping_method,
            'shipping_data' => $cart->shipping_data,
            'discount_total' => $cart->discount_total,
            'total' => $cart->total,
            'currency' => $cart->currency,
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_sku' => $item->product_sku,
                    'product_image' => $item->product?->main_image_url ?? $item->product_image,
                    'product_slug' => $item->product?->slug ?? '',
                    'price' => $item->product_price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'options' => $item->options,
                    'customizations' => $item->customizations,
                ];
            }),
        ];
    }

    protected function getSessionId()
    {
        $sessionId = Session::get($this->sessionKey);

        if (!$sessionId) {
            $sessionId = Session::getId() . '_' . time();
            Session::put($this->sessionKey, $sessionId);
        }

        return $sessionId;
    }

    protected function createCart($sessionId, $userId = null)
    {
        return Cart::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'expires_at' => now()->addDays(7), // Cart expires in 7 days
        ]);
    }

    protected function mergeCarts(Cart $fromCart, Cart $toCart)
    {
        foreach ($fromCart->items as $item) {
            $existingItem = $toCart->items()->where('product_id', $item->product_id)->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $item->quantity);
                $existingItem->updateTotal();
            } else {
                $item->update(['cart_id' => $toCart->id]);
            }
        }

        $fromCart->delete();
        $toCart->recalculateTotals();
    }
}