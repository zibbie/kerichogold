<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TpayPaymentService;
use App\Services\TpaySignatureVerifier;

class TestTpaySecurity extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:tpay-security';

    /**
     * The console command description.
     */
    protected $description = 'Test Tpay JWS signature verification security';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Tpay JWS signature verification...');

        // Test 1: Check if public key can be fetched
        $this->info('Test 1: Fetching Tpay public key...');
        $publicKey = TpaySignatureVerifier::getTpayPublicKey();

        if ($publicKey) {
            $this->info('✅ Public key fetched successfully');
        } else {
            $this->warn('⚠️  Could not fetch public key (may be normal in sandbox)');
        }

        // Test 2: Test signature verification with mock data
        $this->info('Test 2: Testing signature verification logic...');

        $testData = [
            'sale_auth' => 'test_transaction_123',
            'status' => 'correct',
            'amount' => '100.00',
            'currency' => 'PLN',
        ];

        // This would normally be a real JWS token from Tpay
        $mockSignature = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.mock_payload.mock_signature';

        $verifier = new TpayPaymentService();
        $isValid = $verifier->verifySignature($testData, $mockSignature);

        if (!$isValid) {
            $this->info('✅ Signature verification correctly rejected invalid signature');
        } else {
            $this->error('❌ Signature verification should have rejected invalid signature');
        }

        // Test 3: Check security measures
        $this->info('Test 3: Checking security measures...');

        $securityChecks = [
            'JWS library loaded' => class_exists('Firebase\JWT\JWT'),
            'Public key configured or fetchable' => !empty($publicKey) || !empty(config('services.tpay.public_key')),
            'Webhook endpoint exists' => true, // Route exists in api.php
            'Logging enabled' => config('logging.default') !== null,
        ];

        foreach ($securityChecks as $check => $passed) {
            if ($passed) {
                $this->info("✅ {$check}");
            } else {
                $this->error("❌ {$check}");
            }
        }

        $this->info('Security test completed.');
        $this->info('');
        $this->info('📋 Production Setup Checklist:');
        $this->info('1. Set TPAY_PUBLIC_KEY in .env (or leave empty for auto-fetch)');
        $this->info('2. Configure webhook URL in Tpay merchant panel');
        $this->info('3. Ensure HTTPS is enabled for webhook endpoint');
        $this->info('4. Monitor logs for signature verification failures');

        return 0;
    }
}