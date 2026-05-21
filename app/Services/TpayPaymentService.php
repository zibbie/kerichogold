<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Order;
use App\Models\Product;
use App\Services\TpaySignatureVerifier;
use Exception;

class TpayPaymentService
{
    protected $apiUrl;
    protected $merchantId;
    protected $securityCode;
    protected $crc;
    protected $returnUrl;
    protected $resultUrl;
    protected $publicKey;

    public function __construct()
    {
        $this->apiUrl = config('services.tpay.api_url', 'https://secure.tpay.com/api/gw/');
        $this->merchantId = config('services.tpay.merchant_id');
        $this->securityCode = config('services.tpay.security_code');
        $this->crc = config('services.tpay.crc');
        $this->returnUrl = config('services.tpay.return_url');
        $this->resultUrl = config('services.tpay.result_url');
        // Get public key from config or fetch from Tpay JWKS (cached to prevent HTTP on every request)
        $this->publicKey = config('services.tpay.public_key') ?: \Illuminate\Support\Facades\Cache::remember('tpay_public_key', 3600, function() {
            return TpaySignatureVerifier::getTpayPublicKey();
        });
    }

    /**
     * Create Tpay transaction for order
     */
    public function createTransaction(Order $order, string $paymentMethod = 'BLIK', ?string $blikCode = null): array
    {
        $transactionData = [
            'id' => $this->merchantId,
            'amount' => number_format($order->total, 2, '.', ''),
            'description' => "Zamówienie {$order->order_number}",
            'crc' => $this->crc,
            'md5sum' => $this->generateMd5Sum($order),
            'name' => $order->name, // Changed from user->name to support guest orders
            'email' => $order->email, // Changed from user->email
            'return_url' => $this->returnUrl,
            'result_url' => $this->resultUrl,
            'group' => $this->getGroupForMethod($paymentMethod),
            'accept_tos' => 1,
        ];

        if ($paymentMethod === 'BLIK' && $blikCode) {
            $transactionData['blik_code'] = $blikCode;
        }

        try {
            $response = Http::timeout(30)->post($this->apiUrl . 'DirectBill', $transactionData);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['result']) && $result['result'] == 1) {
                    // Transaction created successfully
                    $order->update([
                        'payment_transaction_id' => $result['sale_auth'],
                        'payment_status' => 'pending',
                        'payment_method' => $paymentMethod,
                    ]);

                    return [
                        'success' => true,
                        'transaction_id' => $result['sale_auth'],
                        'payment_url' => $result['payment_url'] ?? null,
                        'status' => 'pending',
                    ];
                } else {
                    throw new Exception('Tpay transaction creation failed: ' . ($result['error'] ?? 'Unknown error'));
                }
            } else {
                throw new Exception('Tpay API request failed with status: ' . $response->status());
            }

        } catch (Exception $e) {
            Log::error('Tpay transaction creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'data' => collect($transactionData)->except(['blik_code', 'md5sum'])->toArray(),
            ]);

            throw new Exception('Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Tpay webhook/callback
     */
    public function handleCallback(array $callbackData, ?string $signature = null): bool
    {
        try {
            // Verify callback authenticity using JWS signature
            if (!$this->verifySignature($callbackData, $signature)) {
                Log::warning('Invalid Tpay callback signature', [
                    'callback_data' => $callbackData,
                    'signature' => $signature,
                ]);
                return false;
            }

            $transactionId = $callbackData['sale_auth'] ?? null;
            $status = $callbackData['status'] ?? null;

            if (!$transactionId) {
                Log::error('Missing transaction ID in callback', $callbackData);
                return false;
            }

            $order = Order::where('payment_transaction_id', $transactionId)->first();

            if (!$order) {
                Log::error('Order not found for transaction', [
                    'transaction_id' => $transactionId,
                    'callback_data' => $callbackData,
                ]);
                return false;
            }

            // Update order status based on payment result with idempotency guard
            switch ($status) {
                case 'correct':
                    \Illuminate\Support\Facades\DB::transaction(function () use ($order, $transactionId, $status) {
                        $order = Order::where('id', $order->id)
                            ->where('status', '!=', 'paid')
                            ->lockForUpdate()
                            ->first();

                        if ($order) {
                            $order->status = 'paid';
                            $order->payment_status = 'completed';
                            $order->paid_at = now();
                            $order->save();

                            $this->handleSuccessfulPayment($order);
                        }
                    });
                    break;

                case 'error':
                    $order->status = 'payment_failed';
                    $order->payment_status = 'failed';
                    $order->save();

                    // Restore inventory
                    $this->restoreInventory($order);
                    break;

                default:
                    Log::info('Unhandled payment status', [
                        'order_id' => $order->id,
                        'status' => $status,
                    ]);
                    break;
            }

            Log::info('Payment callback processed', [
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'status' => $status,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error processing Tpay callback', [
                'callback_data' => $callbackData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(string $transactionId): array
    {
        $checkData = [
            'id' => $this->merchantId,
            'sale_auth' => $transactionId,
            'crc' => $this->crc,
            'md5sum' => md5($this->merchantId . $transactionId . $this->crc),
        ];

        try {
            $response = Http::timeout(10)->post($this->apiUrl . 'get', $checkData);

            if ($response->successful()) {
                $result = $response->json();

                return [
                    'success' => true,
                    'status' => $result['status'] ?? 'unknown',
                    'amount' => $result['amount'] ?? 0,
                    'currency' => $result['currency'] ?? 'PLN',
                ];
            } else {
                throw new Exception('Status check request failed');
            }

        } catch (Exception $e) {
            Log::error('Payment status check failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment(Order $order, float $amount = null): bool
    {
        $refundAmount = $amount ?? $order->total;

        $refundData = [
            'id' => $this->merchantId,
            'sale_auth' => $order->payment_transaction_id,
            'amount' => number_format($refundAmount, 2, '.', ''),
            'crc' => $this->crc,
            'md5sum' => md5($this->merchantId . $order->payment_transaction_id . number_format($refundAmount, 2, '.', '') . $this->crc),
        ];

        try {
            $response = Http::timeout(30)->post($this->apiUrl . 'refund', $refundData);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['result']) && $result['result'] == 1) {
                    Log::info('Payment refund successful', [
                        'order_id' => $order->id,
                        'amount' => $refundAmount,
                    ]);

                    $order->update([
                        'status' => 'refunded',
                        'payment_status' => 'refunded',
                    ]);

                    return true;
                }
            }

            Log::error('Payment refund failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);

            return false;

        } catch (Exception $e) {
            Log::error('Payment refund exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function generateMd5Sum(Order $order): string
    {
        return md5($this->merchantId . number_format($order->total, 2, '.', '') . $this->crc);
    }

    protected function getGroupForMethod(string $method): int
    {
        $groups = [
            'BLIK' => 166, // Tpay BLIK group
            'CARD' => 103, // Tpay card payments
            'BANK' => 150, // Tpay bank transfers
        ];

        return $groups[$method] ?? 150; // Default to bank transfers
    }

    /**
     * Verify JWS signature from X-Tpay-Signature header
     */
    protected function verifySignature(array $data, ?string $signature): bool
    {
        if (!$signature || !$this->publicKey) {
            Log::error('Missing signature or public key for verification', [
                'has_signature' => !empty($signature),
                'has_public_key' => !empty($this->publicKey),
            ]);
            return false;
        }

        // Prepare data for verification - sort keys for consistent ordering
        ksort($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return TpaySignatureVerifier::verifyWebhookSignature($payload, $signature, $this->publicKey);
    }

    /**
     * Legacy MD5 verification (deprecated, kept for backward compatibility)
     */
    protected function verifyCallback(array $data): bool
    {
        // Tpay callback verification (legacy MD5 method - NOT SECURE FOR PRODUCTION)
        $receivedMd5 = $data['md5sum'] ?? '';
        $expectedMd5 = md5($this->merchantId . ($data['sale_auth'] ?? '') . ($data['status'] ?? '') . $this->crc);

        return hash_equals($expectedMd5, $receivedMd5);
    }

    protected function handleSuccessfulPayment(Order $order)
    {
        // Send order confirmation email asynchronously
        try {
            $adminEmails = \App\Models\Setting::get('admin_emails', 'kontakt@kerichogold.pl');
            $emails = array_map('trim', explode(',', $adminEmails));

            \Illuminate\Support\Facades\Mail::to($order->email)
                ->bcc($emails)
                ->queue(new \App\Mail\OrderConfirmationMail($order));
        } catch (\Exception $e) {
            Log::error('Mail queuing failed after successful Tpay payment', ['error' => $e->getMessage()]);
        }

        // Auto-generate invoice in BaseLinker
        if ($order->wants_invoice) {
            dispatch(new \App\Jobs\CreateBaseLinkerInvoice($order))->afterCommit();
        }

        // Track Purchase in GTM Server-Side
        try {
            app(\App\Services\AnalyticsService::class)->trackPurchase($order);
        } catch (\Exception $e) {
            Log::error('Analytics tracking failed in Tpay handler', ['error' => $e->getMessage()]);
        }

        Log::info('Successful payment processed and mail queued', ['order_id' => $order->id]);
    }

    protected function restoreInventory(Order $order)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            // Restore product quantities using atomic increments
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('quantity', $item->quantity);
            }
        });

        Log::info('Inventory restored for failed payment', ['order_id' => $order->id]);
    }
}