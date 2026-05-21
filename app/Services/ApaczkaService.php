<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ApaczkaService
{
    protected $apiUrl;
    protected $appId;
    protected $appSecret;

    public function __construct()
    {
        $this->apiUrl = config('services.apaczka.api_url', 'https://www.apaczka.pl/api/v2/');
        $this->appId = config('services.apaczka.app_id');
        $this->appSecret = config('services.apaczka.app_secret');
    }

    /**
     * Get available shipping services
     */
    public function getServices()
    {
        return $this->request('services', []);
    }

    /**
     * Calculate shipping price
     */
    public function calculatePrice($data)
    {
        return $this->request('order/valuation', $data);
    }

    /**
     * Create a shipment order
     */
    public function createOrder($data)
    {
        return $this->request('order/create', $data);
    }

    /**
     * Internal request handler with signature generation
     */
    protected function request($route, $data)
    {
        if (!$this->appId || !$this->appSecret) {
            Log::error('Apaczka API credentials missing');
            return ['success' => false, 'error' => 'Apaczka credentials missing'];
        }

        $expires = time() + 600; // 10 minutes from now
        $jsonData = json_encode($data);
        
        $stringToSign = sprintf("%s:%s:%s:%s", $this->appId, $route, $jsonData, $expires);
        $signature = hash_hmac('sha256', $stringToSign, $this->appSecret);

        try {
            $response = Http::timeout(30)->post($this->apiUrl . $route, [
                'app_id' => $this->appId,
                'request' => $data,
                'expires' => $expires,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('Apaczka API error', [
                'route' => $route,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Apaczka API error: ' . ($response->json()['error']['message'] ?? 'Unknown error')
            ];

        } catch (Exception $e) {
            Log::error('Apaczka request exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
