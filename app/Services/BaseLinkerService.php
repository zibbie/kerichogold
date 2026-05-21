<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product;
use Exception;

class BaseLinkerService
{
    protected string $apiUrl;
    protected ?string $token;

    public function __construct()
    {
        $this->apiUrl = config('services.baselinker.api_url');
        $this->token = config('services.baselinker.token');
    }

    /**
     * Check if BaseLinker integration is configured and active.
     * Returns false if no API token is set (graceful degradation).
     */
    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    /**
     * Universal BaseLinker API call method.
     * All communication goes through this single entry point.
     *
     * @param string $method BaseLinker API method name (e.g. 'addOrder', 'getOrders')
     * @param array $params Method parameters
     * @return array Decoded JSON response
     * @throws Exception on API error or missing configuration
     */
    public function apiCall(string $method, array $params = []): array
    {
        if (!$this->isConfigured()) {
            throw new Exception("BaseLinker API token not configured. Set BASELINKER_API_TOKEN in .env");
        }

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->retry(2, 1000)
                ->withHeaders(['X-BLToken' => $this->token])
                ->post($this->apiUrl, [
                    'method' => $method,
                    'parameters' => json_encode($params),
                ]);

            if (!$response->successful()) {
                Log::error("BaseLinker API HTTP error", [
                    'method' => $method,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new Exception("BaseLinker API HTTP {$response->status()}: {$method}");
            }

            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'ERROR') {
                Log::error("BaseLinker API returned error", [
                    'method' => $method,
                    'error_code' => $data['error_code'] ?? 'unknown',
                    'error_message' => $data['error_message'] ?? 'unknown',
                ]);
                throw new Exception("BaseLinker [{$data['error_code']}]: " . ($data['error_message'] ?? 'Unknown error'));
            }

            return $data;

        } catch (Exception $e) {
            if (!str_starts_with($e->getMessage(), 'BaseLinker')) {
                Log::error("BaseLinker Service Exception", [
                    'method' => $method,
                    'message' => $e->getMessage(),
                ]);
            }
            throw $e;
        }
    }

    // ──────────────────────────────────────────────
    //  ORDERS
    // ──────────────────────────────────────────────

    /**
     * Push a Nevro-Shop order to BaseLinker order manager.
     * Idempotent: skips if order already has a baselinker_id.
     *
     * @return int BaseLinker order ID
     */
    public function pushOrder(Order $order): int
    {
        // Idempotency guard
        if ($order->baselinker_id) {
            Log::info("BaseLinker: Order #{$order->order_number} already synced (BL ID: {$order->baselinker_id})");
            return $order->baselinker_id;
        }

        $order->loadMissing('items');

        $products = $order->items->map(fn($item) => [
            'storage'      => 'db',
            'storage_id'   => 0,
            'product_id'   => (string) ($item->product?->baselinker_id ?? $item->product_sku),
            'name'         => $item->product_name,
            'sku'          => $item->product_sku ?? '',
            'quantity'     => $item->quantity,
            'price_brutto' => (float) $item->price,
            'tax_rate'     => 23,
        ])->toArray();

        $shipping = $order->shipping_address ?? [];

        $result = $this->apiCall('addOrder', [
            'order_status_id'    => $this->mapStatusToBaseLinker($order->status),
            'date_add'           => $order->ordered_at?->timestamp ?? now()->timestamp,
            'currency'           => 'PLN',
            'payment_method'     => $order->payment_method_label,
            'paid'               => $order->payment_status === 'paid' ? 1 : 0,
            'user_login'         => $order->email,
            'phone'              => $order->phone ?? '',
            'email'              => $order->email,
            'user_comments'      => '',
            'admin_comments'     => "Zamówienie z Nevro-Shop: {$order->order_number}",
            'delivery_fullname'  => $shipping['name'] ?? $order->name,
            'delivery_address'   => $shipping['address'] ?? '',
            'delivery_city'      => $shipping['city'] ?? $order->city,
            'delivery_postcode'  => $shipping['zip'] ?? $order->zip,
            'delivery_country_code' => 'PL',
            'delivery_method'    => $order->shipping_method ?? 'Kurier',
            'delivery_price'     => (float) $order->shipping_cost,
            'want_invoice'       => $order->wants_invoice ? 1 : 0,
            'invoice_nip'        => $order->nip ?? '',
            'products'           => $products,
        ]);

        $blOrderId = $result['order_id'];

        $order->baselinker_id = $blOrderId;
        $order->save();

        Log::info("BaseLinker: Order #{$order->order_number} pushed successfully (BL ID: {$blOrderId})");

        return $blOrderId;
    }

    /**
     * Update order status in BaseLinker when it changes in Nevro.
     */
    public function updateOrderStatus(Order $order): void
    {
        if (!$order->baselinker_id) {
            return;
        }

        $this->apiCall('setOrderStatus', [
            'order_id'        => $order->baselinker_id,
            'status_id'       => $this->mapStatusToBaseLinker($order->status),
        ]);

        Log::info("BaseLinker: Order #{$order->order_number} status updated to '{$order->status}'");
    }

    /**
     * Fetch recently changed orders from BaseLinker and sync statuses back to Nevro.
     * Used by the scheduler (bl:sync-orders command).
     *
     * @return int Number of orders updated
     */
    public function syncOrderStatuses(int $sinceTimestamp): int
    {
        $result = $this->apiCall('getOrders', [
            'date_from' => $sinceTimestamp,
            'get_unconfirmed_orders' => false,
        ]);

        $updated = 0;

        foreach ($result['orders'] ?? [] as $blOrder) {
            $order = Order::where('baselinker_id', $blOrder['order_id'])->first();
            if (!$order) {
                continue;
            }

            $nevroStatus = $this->mapStatusFromBaseLinker($blOrder['order_status_id']);
            if ($nevroStatus && $nevroStatus !== $order->status) {
                $order->transitionTo($nevroStatus);
                $order->baselinker_status = (string) $blOrder['order_status_id'];
                $order->save();
                $updated++;

                Log::info("BaseLinker sync: Order #{$order->order_number} status → '{$nevroStatus}'");
            }
        }

        return $updated;
    }

    // ──────────────────────────────────────────────
    //  INVENTORY / STOCK
    // ──────────────────────────────────────────────

    /**
     * Push a product to BaseLinker inventory.
     */
    public function pushProduct(Product $product): int
    {
        if ($product->baselinker_id) {
            return $product->baselinker_id;
        }

        $inventoryId = config('services.baselinker.inventory_id');

        $result = $this->apiCall('addInventoryProduct', [
            'inventory_id' => $inventoryId,
            'product_id'   => '', // empty = create new
            'sku'          => $product->sku ?? '',
            'ean'          => $product->gtin ?? '',
            'text_fields'  => [
                'name'             => $product->name,
                'description'      => strip_tags($product->description ?? ''),
                'description_extra1' => $product->brand ?? 'Nevro',
            ],
            'prices' => [
                // Default price group ID = 1 (klient skonfiguruje w BL)
                1 => (float) $product->price,
            ],
            'stock' => [
                // Default warehouse ID = 'bl_1' (klient skonfiguruje w BL)
                'bl_1' => (int) $product->quantity,
            ],
        ]);

        $blProductId = $result['product_id'];
        $product->baselinker_id = $blProductId;
        $product->save();

        Log::info("BaseLinker: Product '{$product->name}' pushed (BL ID: {$blProductId})");

        return $blProductId;
    }

    /**
     * Sync stock levels FROM BaseLinker TO Nevro (BL is master for stock).
     *
     * @return int Number of products updated
     */
    public function syncStockFromBaseLinker(): int
    {
        $inventoryId = config('services.baselinker.inventory_id');

        $result = $this->apiCall('getInventoryProductsStock', [
            'inventory_id' => $inventoryId,
        ]);

        $updated = 0;

        foreach ($result['products'] ?? [] as $blProductId => $stockData) {
            $product = Product::where('baselinker_id', $blProductId)->first();
            if (!$product) {
                continue;
            }

            $totalStock = is_array($stockData['stock'] ?? null)
                ? array_sum($stockData['stock'])
                : 0;

            if ((int) $product->quantity !== $totalStock) {
                $product->quantity = $totalStock;
                $product->save();
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Push current Nevro stock to BaseLinker after a sale.
     */
    public function pushStockUpdate(Product $product): void
    {
        if (!$product->baselinker_id) {
            return;
        }

        $inventoryId = config('services.baselinker.inventory_id');

        $this->apiCall('updateInventoryProductsStock', [
            'inventory_id' => $inventoryId,
            'products' => [
                $product->baselinker_id => [
                    'bl_1' => (int) $product->quantity,
                ],
            ],
        ]);
    }

    // ──────────────────────────────────────────────
    //  SHIPPING / COURIER
    // ──────────────────────────────────────────────

    /**
     * Create a shipment package in BaseLinker for a given order.
     *
     * @return array Package details including package_id and courier_package_nr
     */
    public function createPackage(Order $order, string $courierCode = 'dpd', array $courierFields = []): array
    {
        if (!$order->baselinker_id) {
            throw new Exception("Cannot create package: Order #{$order->order_number} not synced to BaseLinker");
        }

        $result = $this->apiCall('createPackage', array_merge([
            'order_id'     => $order->baselinker_id,
            'courier_code' => $courierCode,
        ], $courierFields));

        Log::info("BaseLinker: Package created for Order #{$order->order_number}", [
            'package_id' => $result['package_id'] ?? null,
            'courier_code' => $courierCode,
        ]);

        return $result;
    }

    /**
     * Get shipping label PDF for a package.
     */
    public function getLabel(int $packageId): string
    {
        $result = $this->apiCall('getLabel', [
            'package_id' => $packageId,
        ]);

        return base64_decode($result['label'] ?? '');
    }

    // ──────────────────────────────────────────────
    //  INVOICING
    // ──────────────────────────────────────────────

    /**
     * Generate an invoice in BaseLinker for a paid order.
     */
    public function createInvoice(Order $order): ?int
    {
        if (!$order->baselinker_id || !$order->wants_invoice) {
            return null;
        }

        $seriesId = config('services.baselinker.invoice_series_id');
        if (!$seriesId) {
            Log::warning("BaseLinker: Invoice series not configured. Set BASELINKER_INVOICE_SERIES_ID in .env");
            return null;
        }

        $result = $this->apiCall('addInvoice', [
            'order_id'  => $order->baselinker_id,
            'series_id' => (int) $seriesId,
        ]);

        $invoiceId = $result['invoice_id'] ?? null;

        Log::info("BaseLinker: Invoice created for Order #{$order->order_number}", [
            'invoice_id' => $invoiceId,
        ]);

        return $invoiceId;
    }

    // ──────────────────────────────────────────────
    //  STATUS MAPPING HELPERS
    // ──────────────────────────────────────────────

    /**
     * Map Nevro order status to BaseLinker status ID.
     */
    protected function mapStatusToBaseLinker(string $nevroStatus): int
    {
        $map = config('services.baselinker.status_map', []);
        return (int) ($map[$nevroStatus] ?? 0);
    }

    /**
     * Map BaseLinker status ID back to Nevro order status.
     * Returns null if no mapping found.
     */
    protected function mapStatusFromBaseLinker(int $blStatusId): ?string
    {
        $map = config('services.baselinker.status_map', []);
        $flipped = array_flip(array_map('intval', $map));

        return $flipped[$blStatusId] ?? null;
    }
}
