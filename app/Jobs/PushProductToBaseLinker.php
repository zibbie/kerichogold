<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\BaseLinkerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushProductToBaseLinker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 60, 300];

    public function __construct(
        public Product $product
    ) {}

    public function handle(BaseLinkerService $bl): void
    {
        if (!$bl->isConfigured()) {
            Log::debug("BaseLinker not configured, skipping product push for {$this->product->sku}");
            return;
        }

        try {
            $bl->pushProduct($this->product);
        } catch (\Exception $e) {
            Log::error("BaseLinker sync failed for product {$this->product->sku}", [
                'error' => $e->getMessage(),
                'response' => method_exists($e, 'getResponse') ? $e->getResponse() : 'N/A'
            ]);
            throw $e; // Re-throw to allow job retries
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("BaseLinker: Failed to push Product {$this->product->sku}", [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
