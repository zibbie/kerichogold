<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentStatus extends Component
{
    public $transactionId;
    public $status = 'pending';
    public $pollCount = 0;
    public $purchaseEventFired = false;

    public function mount($transactionId = null)
    {
        $this->transactionId = $transactionId;
        
        // If coming from COD with status=success, set it immediately
        if (request()->query('status') === 'success') {
            $this->status = 'completed';
            $this->firePurchaseEvent();
        }
    }

    private function getOrder()
    {
        if (empty($this->transactionId)) {
            return null;
        }

        $query = \App\Models\Order::query();
        
        if (is_numeric($this->transactionId)) {
            $query->where(function($q) {
                $q->where('id', (int)$this->transactionId)
                  ->orWhere('payment_transaction_id', $this->transactionId)
                  ->orWhere('order_number', $this->transactionId);
            });
        } else {
            $query->where(function($q) {
                $q->where('payment_transaction_id', $this->transactionId)
                  ->orWhere('order_number', $this->transactionId);
            });
        }
        
        return $query->first();
    }

    private function firePurchaseEvent()
    {
        if ($this->purchaseEventFired) return;
        
        $order = $this->getOrder();
            
        if ($order) {
            // Server-side deduplication check
            if ($order->is_analytics_sent) {
                $this->purchaseEventFired = true;
                return;
            }

            $this->dispatch('gtag-event', [
                'event' => 'purchase',
                'data' => [
                    'transaction_id' => $order->order_number,
                    'value' => (float) $order->total,
                    'tax' => (float) ($order->tax ?? 0),
                    'shipping' => (float) ($order->shipping_total ?? 0),
                    'currency' => 'PLN',
                    'items' => $order->items->map(fn($item) => [
                        'item_id' => (string) $item->product_id,
                        'item_name' => $item->product_name,
                        'item_brand' => 'Kericho Gold',
                        'price' => (float) $item->price,
                        'quantity' => $item->quantity,
                    ])->toArray(),
                ]
            ]);

            $order->update(['is_analytics_sent' => true]);
            $this->purchaseEventFired = true;
        }
    }

    public function checkStatus()
    {
        $order = $this->getOrder();
        
        if ($order) {
            if ($order->status === 'paid' || $order->payment_status === 'completed') {
                $this->status = 'completed';
            } else {
                // Fallback: Actively check transaction status with Przelewy24 REST API
                try {
                    $p24Service = app(\App\Services\Przelewy24Service::class);
                    $result = $p24Service->checkPaymentStatus($this->transactionId);
                    if (isset($result['success']) && $result['success']) {
                        if ($result['status'] === 'completed') {
                            $this->status = 'completed';
                        } elseif ($result['status'] === 'failed') {
                            $this->status = 'failed';
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Active status fallback check failed in Livewire', ['error' => $e->getMessage()]);
                }
            }
        }

        $this->pollCount++;
        
        // Stop polling after 100 tries or if status is final
        if (in_array($this->status, ['correct', 'paid', 'completed'])) {
            $this->firePurchaseEvent();
        } elseif ($this->pollCount > 100) {
            $this->status = 'failed';
        }
    }

    public function render()
    {
        return view('livewire.payment-status')->layout('layouts.app');
    }
}
