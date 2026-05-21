<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\BaseLinkerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushOrderToBaseLinker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Seconds to wait before retrying a failed job.
     */
    public array $backoff = [10, 60, 300];

    public function __construct(
        public Order $order
    ) {}

    public function handle(BaseLinkerService $bl): void
    {
        // Graceful degradation: skip silently if BL not configured
        if (!$bl->isConfigured()) {
            Log::debug("BaseLinker not configured, skipping order push for #{$this->order->order_number}");
            return;
        }

        $bl->pushOrder($this->order);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("BaseLinker: Failed to push Order #{$this->order->order_number} after {$this->tries} attempts", [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
