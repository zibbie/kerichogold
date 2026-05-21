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

class CreateBaseLinkerInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 60, 300];

    public function __construct(
        public Order $order
    ) {}

    public function handle(BaseLinkerService $bl): void
    {
        if (!$bl->isConfigured()) {
            Log::debug("BaseLinker not configured, skipping invoice creation for #{$this->order->order_number}");
            return;
        }

        if (!$this->order->wants_invoice) {
            return;
        }

        $bl->createInvoice($this->order);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("BaseLinker: Failed to create invoice for Order #{$this->order->order_number}", [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
