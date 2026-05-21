<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_image',
        'quantity',
        'options',
        'customizations',
    ];

    protected $casts = [
        'options' => 'array',
        'customizations' => 'array',
        'product_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function updateTotal()
    {
        // Always re-derive price from the live product record to prevent stale price accumulation
        $this->product_price = $this->product->price;
        $this->total = round($this->product_price * $this->quantity, 2);
        $this->save();
    }
}
