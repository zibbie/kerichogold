<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $tpayService;

    public function __construct(\App\Services\Przelewy24Service $paymentService, \App\Services\TpayPaymentService $tpayService)
    {
        $this->paymentService = $paymentService;
        $this->tpayService = $tpayService;
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'nullable|string',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            // Check if we should use Tpay (e.g. for BLIK or if explicitly requested)
            if ($request->method === 'TPAY' || ($request->method === 'BLIK' && config('services.tpay.active', false))) {
                $result = $this->tpayService->createTransaction($order);
            } else {
                $result = $this->paymentService->registerTransaction($order, $request->method);
            }
            
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function webhook(Request $request)
    {
        // 1. Detect if it's a Tpay callback
        $tpaySignature = $request->header('X-JWS-Signature') ?: $request->header('X-Tpay-Signature');
        if ($tpaySignature) {
            Log::info('Processing Tpay Webhook');
            $success = $this->tpayService->handleCallback($request->all(), $tpaySignature);
            return $success ? response('TRUE') : response('FALSE', 400); // Tpay expects TRUE/FALSE response
        }

        // 2. Handle Przelewy24 callback
        $sessionId = $request->input('sessionId');
        
        // If we are on production, and the order is not found here, it must be a staging/sandbox transaction.
        // We forward it to staging so it can be verified and processed there.
        if (config('app.env') === 'production') {
            $orderExists = Order::where('payment_transaction_id', $sessionId)->exists();
            if (!$orderExists) {
                Log::info('P24 Webhook: Order not found in production. Forwarding to staging.', ['sessionId' => $sessionId]);
                try {
                    $response = Http::post('https://sklep2.kerichogold.com.pl/api/payment/webhook', $request->all());
                    
                    Log::info('P24 Webhook: Forwarded to staging response', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    return response($response->body(), $response->status());
                } catch (\Exception $e) {
                    Log::error('P24 Webhook: Forwarding to staging failed', ['error' => $e->getMessage()]);
                    return response('Forwarding failed', 500);
                }
            }
        }

        $verifyResult = $this->paymentService->verifyTransaction($request->all());
        
        if ($verifyResult !== 'completed') {
            Log::warning('P24 Webhook: Verification error or not completed', [
                'ip' => $request->ip(),
                'sessionId' => $request->sessionId,
                'result' => $verifyResult
            ]);
            return response('Error', 400);
        }

        // Update order status if verified with idempotency guard
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $order = Order::where('payment_transaction_id', $request->sessionId)
                ->where('status', '!=', 'paid') // Idempotency check
                ->lockForUpdate()
                ->first();

            if ($order) {
                $order->status = 'paid';
                $order->payment_status = 'completed';
                $order->paid_at = now();
                $order->save();

                // Send confirmation email to Customer & Admin asynchronously
                try {
                    $adminEmails = \App\Models\Setting::get('admin_emails', 'kontakt@kerichogold.pl');
                    $emails = array_map('trim', explode(',', $adminEmails));

                    \Illuminate\Support\Facades\Mail::to($order->email)
                        ->bcc($emails)
                        ->queue(new \App\Mail\OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    Log::error('Mail queuing failed after P24 payment', ['error' => $e->getMessage()]);
                }

                // Auto-generate invoice in BaseLinker
                if ($order->wants_invoice) {
                    dispatch(new \App\Jobs\CreateBaseLinkerInvoice($order))->afterCommit();
                }

                // Track Purchase in GTM Server-Side
                try {
                    app(\App\Services\AnalyticsService::class)->trackPurchase($order);
                } catch (\Exception $e) {
                    Log::error('Analytics tracking failed in P24 webhook', ['error' => $e->getMessage()]);
                }
            }

            return response('OK');
        });
    }

    public function status($transactionId)
    {
        $result = $this->paymentService->checkPaymentStatus($transactionId);
        return response()->json($result);
    }
}
