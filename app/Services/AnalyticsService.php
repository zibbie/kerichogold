<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    protected ?string $apiSecret;

    public function __construct()
    {
        $this->serverUrl = (string) config('services.gtm.server_url', '');
        $this->measurementId = (string) config('services.gtm.measurement_id', '');
        $this->apiSecret = (string) config('services.gtm.api_secret', '');
    }

    /**
     * Send a purchase event to GA4 (Measurement Protocol or GTM Server-Side)
     */
    public function trackPurchase(Order $order): void
    {
        if (empty($this->measurementId)) {
            Log::info('GA Measurement ID not configured. Skipping tracking.');
            return;
        }

        try {
            $order->load('items.product');

            $totalProfit = 0;
            $items = [];

            foreach ($order->items as $item) {
                $product = $item->product;
                $profit = 0;

                if ($product && $product->purchase_price > 0) {
                    $profit = ($item->price - $product->purchase_price) * $item->quantity;
                }

                $totalProfit += $profit;

                $items[] = [
                    'item_id' => $product?->sku ?? (string)$item->id,
                    'item_name' => $item->name,
                    'price' => (float)$item->price,
                    'quantity' => (int)$item->quantity,
                    'item_brand' => $product?->brand ?? 'Kericho Gold',
                    'item_category' => $product?->category?->name ?? 'General',
                    'profit' => (float)$profit,
                ];
            }

            $clientId = $order->ga_client_id;
            
            // Fallback for Client ID if missing
            if (empty($clientId)) {
                $clientId = request()->cookie('_ga');
                if ($clientId) {
                    preg_match('/(?:GA1\.\d\.)?(\d+\.\d+)/', $clientId, $matches);
                    $clientId = $matches[1] ?? $clientId;
                }
            }
            
            if (empty($clientId)) {
                $clientId = 'server.' . $order->id . '.' . time();
            }

            // Case 1: Direct GA4 Measurement Protocol
            if (!empty($this->apiSecret)) {
                $url = "https://www.google-analytics.com/mp/collect?measurement_id={$this->measurementId}&api_secret={$this->apiSecret}";
                $payload = [
                    'client_id' => $clientId,
                    'events' => [
                        [
                            'name' => 'purchase',
                            'params' => [
                                'transaction_id' => $order->order_number,
                                'value' => (float)$order->total,
                                'currency' => 'PLN',
                                'tax' => (float)$order->tax,
                                'shipping' => (float)$order->shipping_cost,
                                'profit' => (float)$totalProfit,
                                'items' => $items,
                            ]
                        ]
                    ]
                ];
            } 
            // Case 2: GTM Server-Side
            elseif (!empty($this->serverUrl)) {
                $url = $this->serverUrl;
                $payload = [
                    'event' => 'purchase',
                    'client_id' => $clientId,
                    'v' => 2,
                    'tid' => $this->measurementId,
                    'transaction_id' => $order->order_number,
                    'value' => (float)$order->total,
                    'currency' => 'PLN',
                    'items' => $items,
                ];
            } else {
                Log::info('Neither GA API Secret nor GTM Server URL configured. Skipping tracking.');
                return;
            }

            Http::timeout(5)->post($url, $payload);

            Log::info("GA4 Purchase tracked: {$order->order_number}, Method: " . (!empty($this->apiSecret) ? 'Direct MP' : 'GTM Server'));
        } catch (\Exception $e) {
            Log::error("GA4 Tracking Error: " . $e->getMessage());
        }
    }
}
