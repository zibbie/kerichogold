<?php

namespace App\Console\Commands;

use App\Services\BaseLinkerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncBaseLinkerStock extends Command
{
    protected $signature = 'bl:sync-stock';

    protected $description = 'Synchronize product stock quantities from BaseLinker back to Kericho Gold';

    public function handle(BaseLinkerService $bl): int
    {
        if (!$bl->isConfigured()) {
            $this->warn('BaseLinker API token not configured. Skipping stock sync.');
            return self::SUCCESS;
        }

        $this->info("Fetching stock from BaseLinker...");

        try {
            $updated = $bl->syncStockFromBaseLinker();
            $this->info("✓ Synchronized stock for {$updated} product(s) from BaseLinker.");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("BaseLinker stock sync failed: {$e->getMessage()}");
            Log::error("bl:sync-stock failed", ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }
}
