<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from old MySQL database to new PostgreSQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration from old MySQL to new PostgreSQL...');

        // Fetch products from old MySQL database
        $this->info('Fetching products from old database...');
        try {
            $oldProducts = DB::connection('mysql_old')
                ->table('products as p')
                ->leftJoin('products_description as pd', 'p.products_id', '=', 'pd.products_id')
                ->where('pd.language_id', 1)
                ->select('p.products_id', 'p.products_model', 'p.products_price', 'p.products_quantity', 'p.products_status', 'p.products_image', 'pd.products_name', 'pd.products_description')
                ->get();
        } catch (\Exception $e) {
            $this->error("Could not connect to old database: " . $e->getMessage());
            return 1;
        }

        $this->info("Found {$oldProducts->count()} products in old database.");

        $imported = 0;
        $updated = 0;

        foreach ($oldProducts as $product) {
            // Handle SKU
            $sku = $product->products_model ?? null;
            if (!$sku) {
                $sku = 'AUTO-' . $product->products_id;
            }

            // Check if product already exists by old_id OR by SKU
            $existingProduct = DB::table('products')->where('old_id', $product->products_id)->first();
            
            if (!$existingProduct && $sku) {
                $existingProduct = DB::table('products')->where('sku', $sku)->first();
            }

            // Ensure SKU is unique if we are creating a NEW product (or if it changed)
            $existingSkuProduct = DB::table('products')->where('sku', $sku)->first();
            if ($existingSkuProduct && (!$existingProduct || $existingSkuProduct->id !== $existingProduct->id)) {
                $sku = $sku . '-' . $product->products_id;
            }

            // Generate unique slug
            $slug = \Illuminate\Support\Str::slug($product->products_name);
            $baseSlug = $slug;
            $counter = 1;
            while (DB::table('products')->where('slug', $slug)->where(function($q) use ($existingProduct, $product) {
                if ($existingProduct) {
                    $q->where('id', '!=', $existingProduct->id);
                } else {
                    $q->where('old_id', '!=', $product->products_id);
                }
            })->exists()) {
                $slug = $baseSlug . '-' . ($product->products_id + $counter++);
            }

            $productData = [
                'name' => $product->products_name ?? '',
                'slug' => $slug,
                'sku' => $sku,
                'price' => (float) ($product->products_price ?? 0),
                'quantity' => (int) ($product->products_quantity ?? 0),
                'status' => (bool) ($product->products_status ?? false),
                'description' => $product->products_description ?? '',
                'old_id' => $product->products_id,
            ];

            // Only update image if the old DB has one AND the new one doesn't have a "real" one yet
            // Or if it's a new product.
            if (!empty($product->products_image)) {
                if (!$existingProduct || empty($existingProduct->image)) {
                    $productData['image'] = 'products/' . ltrim($product->products_image, '/');
                }
            }

            // Fetch gallery images from old database (additional_images table)
            if (!$existingProduct || empty($existingProduct->gallery)) {
                try {
                    $galleryImages = DB::connection('mysql_old')
                        ->table('additional_images')
                        ->where('products_id', $product->products_id)
                        ->orderBy('sort_order')
                        ->pluck('popup_images')
                        ->toArray();
                    
                    if (!empty($galleryImages)) {
                        $galleryImages = array_map(fn($img) => 'products/' . ltrim($img, '/'), $galleryImages);
                        $productData['gallery'] = json_encode($galleryImages);
                    }
                } catch (\Exception $e) {
                    $this->warn("Could not fetch gallery for product {$product->products_id}: " . $e->getMessage());
                }
            }

            if ($existingProduct) {
                DB::table('products')->where('id', $existingProduct->id)->update($productData);
                $updated++;
            } else {
                DB::table('products')->insert($productData);
                $imported++;
            }
        }

        $this->info("Migration summary: Imported {$imported} new, Updated {$updated} existing.");

        // Run photo migration
        $this->info('Running photo migration...');
        $this->call('photos:migrate', [
            '--source' => '/var/www/nevro-wm/images',
            '--chunk' => 1000
        ]);

        $this->info('Data migration completed.');
    }
}