<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateProductSlugs extends Command
{
    protected $signature = 'products:generate-slugs';
    protected $description = 'Generate slugs for all products that do not have one';

    public function handle()
    {
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();

        $this->info("Found {$products->count()} products without slugs.");

        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;

            // Ensure uniqueness
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $product->slug = $slug;
            $product->saveQuietly(); // Skip model events
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All slugs generated successfully.');
    }
}
