<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigratePhotosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:migrate {--source= : Absolute path to the old images directory} {--chunk=100 : Number of records to process at once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate photos from old images/ folder to new storage/app/public/products/ and update database records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chunkSize = (int) $this->option('chunk');
        $oldImagesPath = $this->option('source');

        if (!$oldImagesPath) {
            $this->error("Please provide the absolute path to the old images directory using --source option.");
            return 1;
        }

        $newImagesPath = storage_path('app/public'); // Paths in DB are relative to this root (e.g. products/image.jpg)

        // Ensure new directory exists
        if (!File::exists($newImagesPath)) {
            File::makeDirectory($newImagesPath, 0755, true);
        }

        if (!File::exists($oldImagesPath)) {
            $this->error("Source directory does not exist: {$oldImagesPath}");
            return 1;
        }

        $this->info("Starting photo migration from {$oldImagesPath} to {$newImagesPath}");

        $totalProcessed = 0;
        $totalCopied = 0;
        $totalErrors = 0;
        $reportEvery = 1000;

        // Process products to copy images (main and gallery)
        DB::table('products')
            ->whereNotNull('image')
            ->orWhereNotNull('gallery')
            ->orderBy('id')
            ->chunk($chunkSize, function ($products) use (&$totalProcessed, &$totalCopied, &$totalErrors, $oldImagesPath, $newImagesPath, $reportEvery) {
                foreach ($products as $product) {
                    // Collect all images for this product
                    $imagesToCopy = [];
                    if (!empty($product->image)) {
                        $imagesToCopy[] = $product->image;
                    }
                    
                    if (!empty($product->gallery)) {
                        $gallery = is_string($product->gallery) ? json_decode($product->gallery, true) : $product->gallery;
                        if (is_array($gallery)) {
                            foreach ($gallery as $img) {
                                if (!empty($img)) {
                                    $imagesToCopy[] = $img;
                                }
                            }
                        }
                    }

                    // Remove duplicates
                    $imagesToCopy = array_unique($imagesToCopy);

                    foreach ($imagesToCopy as $image) {
                        $totalProcessed++;
                        
                        // Handle potential paths in the filename (e.g. subfolders)
                        $oldImagePath = rtrim($oldImagesPath, '/') . '/' . ltrim($image, '/');
                        $newImagePath = $newImagesPath . '/' . ltrim($image, '/');

                        // Ensure subdirectories exist in target
                        $newImageDir = dirname($newImagePath);
                        if (!File::exists($newImageDir)) {
                            File::makeDirectory($newImageDir, 0755, true);
                        }

                        if (File::exists($oldImagePath)) {
                            try {
                                if (!File::exists($newImagePath)) {
                                    File::copy($oldImagePath, $newImagePath);
                                    $totalCopied++;
                                }
                            } catch (\Exception $e) {
                                $totalErrors++;
                                $this->error("Error copying {$image}: " . $e->getMessage());
                            }
                        } else {
                            // Only warn if it's not already in the target (might have been moved/renamed)
                            if (!File::exists($newImagePath)) {
                                // $this->warn("File not found: {$oldImagePath}");
                            }
                        }

                        // Report progress
                        if ($totalProcessed % $reportEvery == 0) {
                            $this->info("Processed {$totalProcessed} image checks so far. Copied: {$totalCopied}, Errors: {$totalErrors}");
                        }
                    }
                }
            });

        $this->info("Migration completed. Total processed: {$totalProcessed}, Copied: {$totalCopied}, Errors: {$totalErrors}");

        // Create symbolic link if not exists (Laravel standard)
        if (!File::exists(public_path('storage'))) {
            $this->info("Creating storage link...");
            $this->call('storage:link');
        }

        return 0;
    }
}
