<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Services\TpayPaymentService;
use App\Services\TpaySignatureVerifier;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TpayWebhookTest extends TestCase
{
    use RefreshDatabase;

    private $tpayService;
    private $testPrivateKey;
    private $testPublicKey;

    protected function setUp(): void
    {
        parent::setUp();

        // Generate test RSA key pair for testing JWS
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $this->testPrivateKey);

        $pubKey = openssl_pkey_get_details($res);
        $this->testPublicKey = $pubKey["key"];

        // Mock the config to use test keys
        config(['services.tpay.public_key' => $this->testPublicKey]);

        $this->tpayService = new TpayPaymentService();
    }

    public function test_successful_payment_callback_with_valid_jws()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 10]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_transaction_id' => 'test_transaction_123',
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Create order item
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 2,
            'price' => 50.00,
            'total' => 100.00,
        ]);

        $callbackData = [
            'sale_auth' => 'test_transaction_123',
            'status' => 'correct',
            'amount' => '100.00',
        ];

        // Generate valid JWS signature
        $signature = $this->generateTestJWSSignature($callbackData);

        // Call webhook handler
        $result = $this->tpayService->handleCallback($callbackData, $signature);

        $this->assertTrue($result);

        // Refresh order from database
        $order->refresh();

        $this->assertEquals('paid', $order->status);
        $this->assertEquals('completed', $order->payment_status);
        $this->assertNotNull($order->paid_at);
    }

    public function test_failed_payment_callback_with_valid_jws()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 10]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_transaction_id' => 'test_transaction_456',
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 1,
            'price' => 50.00,
            'total' => 50.00,
        ]);

        $callbackData = [
            'sale_auth' => 'test_transaction_456',
            'status' => 'error',
            'amount' => '50.00',
        ];

        $signature = $this->generateTestJWSSignature($callbackData);

        $result = $this->tpayService->handleCallback($callbackData, $signature);

        $this->assertTrue($result);

        $order->refresh();

        $this->assertEquals('payment_failed', $order->status);
        $this->assertEquals('failed', $order->payment_status);
        // Check if inventory was restored
        $product->refresh();
        $this->assertEquals(11, $product->quantity); // Original 10 + 1 restored
    }

    public function test_invalid_jws_signature_rejection()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_transaction_id' => 'test_transaction_789',
        ]);

        $callbackData = [
            'sale_auth' => 'test_transaction_789',
            'status' => 'correct',
        ];

        // Invalid signature
        $invalidSignature = 'invalid.jws.signature';

        $result = $this->tpayService->handleCallback($callbackData, $invalidSignature);

        $this->assertFalse($result);

        // Order status should remain unchanged
        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }

    public function test_missing_transaction_id_rejection()
    {
        $callbackData = [
            'status' => 'correct',
        ];

        $signature = $this->generateTestJWSSignature($callbackData);

        $result = $this->tpayService->handleCallback($callbackData, $signature);

        $this->assertFalse($result);
    }

    public function test_unknown_transaction_rejection()
    {
        $callbackData = [
            'sale_auth' => 'nonexistent_transaction',
            'status' => 'correct',
        ];

        $signature = $this->generateTestJWSSignature($callbackData);

        $result = $this->tpayService->handleCallback($callbackData, $signature);

        $this->assertFalse($result);
    }

    private function generateTestJWSSignature(array $data): string
    {
        ksort($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return JWT::encode($payload, $this->testPrivateKey, 'RS256');
    }
}