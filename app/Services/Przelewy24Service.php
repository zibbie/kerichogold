<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Exception;

class Przelewy24Service
{
    protected $apiUrl;
    protected $merchantId;
    protected $posId;
    protected $apiKey;
    protected $crc;
    protected $returnUrl;
    protected $statusUrl;

    public function __construct()
    {
        $env = config('services.przelewy24.env', 'sandbox');
        $this->apiUrl = $env === 'production' 
            ? 'https://secure.przelewy24.pl/api/v1/' 
            : 'https://sandbox.przelewy24.pl/api/v1/';
        
        $this->merchantId = config('services.przelewy24.merchant_id');
        $this->posId = config('services.przelewy24.pos_id') ?: $this->merchantId;
        $this->apiKey = config('services.przelewy24.api_key');
        $this->crc = config('services.przelewy24.crc');
        $this->returnUrl = config('services.przelewy24.return_url');
        $this->statusUrl = config('services.przelewy24.status_url');
    }

    /**
     * Register a new transaction
     */
    public function registerTransaction(Order $order, ?string $method = null): array
    {
        $sessionId = 'order_' . $order->id . '_' . \Illuminate\Support\Str::random(12);
        $amount = (int) round($order->total * 100); // P24 uses grosze

        $baseUrl = config('app.url');
        if (app()->environment('local') && config('services.przelewy24.test_url')) {
            $baseUrl = config('services.przelewy24.test_url');
        }

        $data = [
            'merchantId' => (int) $this->merchantId,
            'posId' => (int) $this->posId,
            'sessionId' => $sessionId,
            'amount' => $amount,
            'currency' => 'PLN',
            'description' => "Zamówienie #{$order->order_number}",
            'email' => $order->email,
            'client' => $order->name,
            'address' => $order->address,
            'zip' => $order->zip,
            'city' => $order->city,
            'country' => 'PL',
            'language' => 'pl',
            'urlReturn' => $baseUrl . '/payment/status/' . $order->id,
            'urlStatus' => (config('services.przelewy24.env') === 'sandbox')
                ? 'https://sklep2.kerichogold.com.pl/api/payment/webhook'
                : $baseUrl . '/api/payment/webhook',
        ];

        // Preselect payment method if provided
        if ($method === 'PAYPO') {
            $data['method'] = 248; // PayPo channel ID in P24
        } elseif ($method === 'BLIK') {
            $data['method'] = 154; // BLIK channel ID in P24
        }

        $data['sign'] = $this->generateSign([
            'sessionId' => $sessionId,
            'merchantId' => (int) $this->merchantId,
            'amount' => $amount,
            'currency' => 'PLN',
            'crc' => $this->crc
        ]);

        try {
            Log::debug('P24 Registration Request', ['url' => $this->apiUrl . 'transaction/register', 'data' => collect($data)->except('sign')->toArray()]);
            
            $response = Http::withBasicAuth($this->merchantId, $this->apiKey)
                ->withHeaders([
                    'Referer' => config('app.url'),
                    'Accept' => 'application/json',
                ])
                ->post($this->apiUrl . 'transaction/register', $data);

            if ($response->successful()) {
                $result = $response->json();
                
                $order->update([
                    'payment_transaction_id' => $sessionId,
                    'payment_status' => 'pending'
                ]);

                return [
                    'success' => true,
                    'token' => $result['data']['token'],
                    'redirect_url' => (config('services.przelewy24.env') === 'production' 
                        ? 'https://secure.przelewy24.pl/trnRequest/' 
                        : 'https://sandbox.przelewy24.pl/trnRequest/') . $result['data']['token']
                ];
            }

            Log::error('P24 Registration Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merchantId' => $this->merchantId
            ]);

            throw new Exception('P24 Registration failed: ' . ($response->json()['error'] ?? $response->body()));

        } catch (Exception $e) {
            Log::error('Przelewy24 Service Exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verify transaction
     */
    public function verifyTransaction(array $data): string
    {
        $amount = $data['amount'];
        $sessionId = $data['sessionId'];

        // Cross-verify amount against order to prevent 1-grosz exploits
        $order = Order::where('payment_transaction_id', $sessionId)->first();
        if ($order && (int)round($order->total * 100) !== (int)$amount) {
            Log::critical('P24 Amount mismatch detected during verification!', [
                'order_id' => $order->id,
                'expected' => (int)round($order->total * 100),
                'received' => (int)$amount
            ]);
            return 'failed';
        }

        $verifyData = [
            'merchantId' => (int) $this->merchantId,
            'posId' => (int) $this->posId,
            'sessionId' => $sessionId,
            'amount' => (int) $amount,
            'currency' => 'PLN',
            'orderId' => (int) $data['orderId'],
            'sign' => $this->generateSign([
                'sessionId' => $sessionId,
                'orderId' => $data['orderId'],
                'amount' => $amount,
                'currency' => 'PLN',
                'crc' => $this->crc
            ])
        ];

        try {
            $response = Http::withBasicAuth($this->merchantId, $this->apiKey)
                ->put($this->apiUrl . 'transaction/verify', $verifyData);

            if ($response->successful()) {
                return 'completed';
            }

            $body = $response->body();
            Log::error('P24 Verification Failed', [
                'status' => $response->status(),
                'body' => $body
            ]);

            if (str_contains($body, 'awaits payment confirmation')) {
                return 'pending';
            }

            return 'failed';
        } catch (Exception $e) {
            Log::error('P24 Verification Service Exception', ['message' => $e->getMessage()]);
            return 'failed';
        }
    }

    /**
     * Active fallback status check
     */
    public function checkPaymentStatus(string $transactionId): array
    {
        $query = Order::query();
        if (is_numeric($transactionId)) {
            $query->where(function($q) use ($transactionId) {
                $q->where('id', (int)$transactionId)
                  ->orWhere('payment_transaction_id', $transactionId)
                  ->orWhere('order_number', $transactionId);
            });
        } else {
            $query->where(function($q) use ($transactionId) {
                $q->where('payment_transaction_id', $transactionId)
                  ->orWhere('order_number', $transactionId);
            });
        }
        $order = $query->first();

        if (!$order) {
            return ['success' => false, 'error' => 'Order not found'];
        }

        if ($order->status === 'paid' || $order->payment_status === 'completed') {
            return ['success' => true, 'status' => 'completed'];
        }

        $sessionId = $order->payment_transaction_id;
        if (!$sessionId) {
            return ['success' => false, 'error' => 'No session ID found'];
        }

        try {
            $response = Http::withBasicAuth($this->merchantId, $this->apiKey)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . 'transaction/by/sessionId/' . $sessionId);

            if ($response->successful()) {
                $result = $response->json();
                $p24Data = $result['data'] ?? null;
                
                if ($p24Data && isset($p24Data['orderId'])) {
                    // Try to verify/finalize the transaction actively using the retrieved orderId and amount!
                    $verifyData = [
                        'sessionId' => $sessionId,
                        'amount' => (int) $p24Data['amount'],
                        'orderId' => (int) $p24Data['orderId'],
                    ];
                    
                    Log::info('P24 Fallback status check: Actively verifying transaction', $verifyData);
                    
                    $verifiedStatus = $this->verifyTransaction($verifyData);
                    
                    if ($verifiedStatus === 'completed') {
                        // Mark as paid in database
                        $order->status = 'paid';
                        $order->payment_status = 'completed';
                        $order->paid_at = now();
                        $order->save();
                        
                        // Queue order confirmation emails and analytics
                        try {
                            $adminEmails = \App\Models\Setting::get('admin_emails', 'kontakt@kerichogold.pl');
                            $emails = array_map('trim', explode(',', $adminEmails));

                            \Illuminate\Support\Facades\Mail::to($order->email)
                                ->bcc($emails)
                                ->queue(new \App\Mail\OrderConfirmationMail($order));
                        } catch (\Exception $e) {
                            Log::error('Mail queuing failed in fallback P24 verify', ['error' => $e->getMessage()]);
                        }

                        // Auto-generate invoice in BaseLinker
                        if ($order->wants_invoice) {
                            dispatch(new \App\Jobs\CreateBaseLinkerInvoice($order))->afterCommit();
                        }

                        // Track Purchase in GTM Server-Side
                        try {
                            app(\App\Services\AnalyticsService::class)->trackPurchase($order);
                        } catch (\Exception $e) {
                            Log::error('Analytics tracking failed in fallback P24 verify', ['error' => $e->getMessage()]);
                        }
                        
                        return [
                            'success' => true,
                            'status' => 'completed',
                        ];
                    } elseif ($verifiedStatus === 'failed') {
                        return [
                            'success' => true,
                            'status' => 'failed',
                        ];
                    }
                }
            }
            
            return [
                'success' => true,
                'status' => 'pending',
            ];
            
        } catch (Exception $e) {
            Log::error('P24 Active check failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function generateSign(array $params): string
    {
        // Explicitly define key order to prevent signature breakage on refactors
        $signData = [
            'sessionId' => $params['sessionId'],
        ];

        if (isset($params['orderId'])) {
            // VERIFICATION signature format: sessionId, orderId, amount, currency, crc
            $signData['orderId'] = (int) $params['orderId'];
        } else {
            // REGISTRATION signature format: sessionId, merchantId, amount, currency, crc
            $signData['merchantId'] = (int) ($params['merchantId'] ?? $this->merchantId);
        }

        $signData['amount'] = (int) $params['amount'];
        $signData['currency'] = $params['currency'] ?? 'PLN';
        $signData['crc'] = $params['crc'] ?? $this->crc;

        return hash('sha384', json_encode($signData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
