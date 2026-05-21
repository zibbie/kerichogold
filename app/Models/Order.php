<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'phone',
        'city',
        'zip',
        'order_number',
        'shipping_method',
        'shipping_data',
        'payment_method',
        'billing_address',
        'shipping_address',
        'wants_invoice',
        'nip',
        'status',
        'payment_status',
        'total',
        'shipping_cost',
        'payment_transaction_id',
        'ga_client_id',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipping_data' => 'array',
        'paid_at' => 'datetime',
        'ordered_at' => 'datetime',
        'total' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'nip' => 'string',
    ];

    const STATUSES = [
        'pending', 'paid', 'processing', 'shipped', 'completed', 'refunded', 'cancelled', 'payment_failed'
    ];

    const TRANSITIONS = [
        'pending'    => ['paid', 'cancelled', 'payment_failed'],
        'paid'       => ['processing', 'refunded'],
        'processing' => ['shipped', 'completed', 'refunded'],
        'shipped'    => ['completed', 'refunded'],
        'payment_failed' => ['pending', 'cancelled'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->recalculateTotal();
        });
    }

    public function transitionTo(string $newStatus): void
    {
        if (!in_array($newStatus, self::TRANSITIONS[$this->status] ?? [], true)) {
             \Illuminate\Support\Facades\Log::warning("Invalid order status transition attempt", [
                 'order_id' => $this->id,
                 'from' => $this->status,
                 'to' => $newStatus
             ]);
             return;
        }
        $this->status = $newStatus;
        $this->save();
    }

    public function recalculateTotal(): void
    {
        if ($this->relationLoaded('items')) {
            $itemsTotal = $this->items->sum('total');
            $this->total = round($itemsTotal + ($this->shipping_cost ?? 0), 2);
        }
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        $method = trim(strtoupper($this->payment_method));
        return match ($method) {
            'COD' => 'Za pobraniem',
            'P24' => 'Przelewy24',
            'BLIK' => 'BLIK',
            'TPAY' => 'Tpay',
            default => $this->payment_method ?? 'Brak',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
