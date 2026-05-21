<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Tpay JWS Signature Verification Helper
 *
 * This class provides additional security methods for Tpay webhook verification
 */
class TpaySignatureVerifier
{
    /**
     * Verify JWS signature with additional security checks
     */
    public static function verifyWebhookSignature(string $payload, string $signature, string $publicKey): bool
    {
        try {
            // Decode header to check algorithm
            $header = self::decodeJWSHeader($signature);

            if (!$header || !isset($header['alg'])) {
                Log::error('Invalid JWS header in signature verification');
                return false;
            }

            // Only allow secure algorithms
            $allowedAlgorithms = ['RS256', 'RS384', 'RS512', 'ES256', 'ES384', 'ES512'];
            if (!in_array($header['alg'], $allowedAlgorithms)) {
                Log::error('Unsupported JWS algorithm', ['algorithm' => $header['alg']]);
                return false;
            }

            // Verify signature and decode payload
            $decoded = \Firebase\JWT\JWT::decode($signature, new \Firebase\JWT\Key($publicKey, $header['alg']));

            // Verify payload matches
            // Since decoded is already the payload string, compare directly
            return hash_equals($payload, $decoded);

        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            Log::error('JWS signature verification failed: invalid signature', [
                'error' => $e->getMessage(),
            ]);
            return false;
        } catch (\Firebase\JWT\BeforeValidException $e) {
            Log::error('JWS token not yet valid', ['error' => $e->getMessage()]);
            return false;
        } catch (\Firebase\JWT\ExpiredException $e) {
            Log::error('JWS token expired', ['error' => $e->getMessage()]);
            return false;
        } catch (\Exception $e) {
            Log::error('JWS verification error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return false;
        }
    }

    /**
     * Decode JWS header without verification
     */
    private static function decodeJWSHeader(string $jwt): ?array
    {
        $tks = explode('.', $jwt);
        if (count($tks) !== 3) {
            return null;
        }

        try {
            return json_decode(\Firebase\JWT\JWT::urlsafeB64Decode($tks[0]), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get public key from Tpay (cache it to avoid repeated downloads)
     */
    public static function getTpayPublicKey(): ?string
    {
        $cacheKey = 'tpay_public_key_pem';
        $cacheTtl = 86400; // 24 hours - certificates change rarely

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, $cacheTtl, function () {
            try {
                // Tpay uses X.509 certificates for JWS notifications
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://secure.tpay.com/x509/notifications-jws.pem');

                if ($response->successful()) {
                    return $response->body();
                }

                Log::error('Failed to fetch Tpay public key PEM', ['status' => $response->status()]);
                return null;

            } catch (\Exception $e) {
                Log::error('Error fetching Tpay public key PEM', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }
}