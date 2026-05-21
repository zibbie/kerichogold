<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'subtotal',
        'tax_total',
        'shipping_total',
        'shipping_method',
        'shipping_data',
        'discount_total',
        'total',
        'currency',
        'shipping_address',
        'billing_address',
        'expires_at',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipping_data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function isEmpty()
    {
        return $this->relationLoaded('items') 
            ? $this->items->isEmpty() 
            : $this->items()->doesntExist();
    }

    public function getItemCount()
    {
        return $this->relationLoaded('items') 
            ? $this->items->sum('quantity') 
            : (int) $this->items()->sum('quantity');
    }

    public function recalculateTotals()
    {
        $items = $this->items()->with('product')->get();
        $subtotalGrosze = 0;
        $taxGrosze = 0;

        foreach ($items as $item) {
            $vatRate = $item->product?->vat_rate ?? 0.23;
            // Work with integers (grosze) to prevent floating point errors
            $itemPriceGrosze = (int) round($item->product_price * 100);
            $itemTotalGrosze = $itemPriceGrosze * $item->quantity;
            
            // Extract tax from gross amount
            $itemTaxGrosze = (int) round($itemTotalGrosze - ($itemTotalGrosze / (1 + $vatRate)));
            
            $subtotalGrosze += $itemTotalGrosze;
            $taxGrosze += $itemTaxGrosze;
        }

        $this->subtotal = $subtotalGrosze / 100;
        $this->tax_total = $taxGrosze / 100;
        
        // Final total is subtotal + shipping - discount
        // Ensure shipping_total is also handled safely
        $shippingGrosze = (int) round(($this->shipping_total ?? 0) * 100);
        $discountGrosze = (int) round(($this->discount_total ?? 0) * 100);
        
        $this->total = ($subtotalGrosze + $shippingGrosze - $discountGrosze) / 100;
        
        $this->save();
    }
}
