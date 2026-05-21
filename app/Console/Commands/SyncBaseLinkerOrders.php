<?php

namespace App\Console\Commands;

use App\Services\BaseLinkerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncBaseLinkerOrders extends Command
{
    protected $signature = 'bl:sync-orders 
                            {--since= : Unix timestamp to sync from (default: last sync time)}
                            {--force : Force full sync from 24h ago}';

    protected $description = 'Synchronize order statuses from BaseLinker back to Kericho Gold';

    protected const CACHE_KEY = 'baselinker_last_order_sync';

    public function handle(BaseLinkerService $bl): int
    {
        if (!$bl->isConfigured()) {
            $this->warn('BaseLinker API token not configured. Skipping sync.');
            return self::SUCCESS;
        }

        // Determine sync start time
        if ($this->option('force')) {
            $since = now()->subDay()->timestamp;
        } elseif ($this->option('since')) {
            $since = (int) $this->option('since');
        } else {
            $since = Cache::get(self::CACHE_KEY, now()->subMinutes(10)->timestamp);
        }

        $this->info("Syncing orders from BaseLinker since " . date('Y-m-d H:i:s', $since));

        try {
            $updated = $bl->syncOrderStatuses($since);

            // Save last sync timestamp
            Cache::put(self::CACHE_KEY, now()->timestamp, now()->addDay());

            $this->info("✓ Synchronized {$updated} order(s) from BaseLinker.");

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("BaseLinker sync failed: {$e->getMessage()}");
            Log::error("bl:sync-orders failed", ['error' => $e->getMessage()]);

            return self::FAILURE;
        }
    }
}
