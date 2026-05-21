<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateCleanGmcImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmc:sanitize-images {--dry-run : Only show what would be done}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates unique, clean-named copies of product images (p[ID].ext) for Google Merchant Center compatibility.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::whereNotNull('image')->get();
        $this->info("Found " . $products->count() . " products with images.");

        $successCount = 0;
        $errorCount = 0;
        $skipCount = 0;

        foreach ($products as $product) {
            $originalPath = $product->image;
            $sourcePath = null;
            $isUrl = false;
            
            // Handle full URLs
            if (filter_var($originalPath, FILTER_VALIDATE_URL)) {
                $isUrl = true;
                $this->line("Downloading image for product #{$product->id} from URL...");
                try {
                    $contents = file_get_contents($originalPath);
                    if ($contents) {
                        $tempName = 'temp_download_' . $product->id;
                        Storage::disk('public')->put('products/' . $tempName, $contents);
                        $sourcePath = 'products/' . $tempName;
                    }
                } catch (\Exception $e) {
                    $this->error("Failed to download URL for #{$product->id}: " . $e->getMessage());
                    $errorCount++;
                    continue;
                }
            } else {
                // Ensure we have the products/ prefix if it's just a filename
                $sourcePath = Str::contains($originalPath, '/') ? $originalPath : 'products/' . $originalPath;
            }
            
            if (!$sourcePath || !Storage::disk('public')->exists($sourcePath)) {
                $this->warn("Source file not found for product #{$product->id}: {$sourcePath}");
                $errorCount++;
                continue;
            }

            // Detect extension from content type if it was a URL, otherwise from path
            if ($isUrl) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer(Storage::disk('public')->get($sourcePath));
                $extension = match($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    'image/gif' => 'gif',
                    default => 'jpg'
                };
            } else {
                $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
            }
            // Sanitize extension (e.g. remove .webp if it was appended)
            if (Str::endsWith($extension, 'webp') && Str::contains($sourcePath, '.jpg')) {
                $extension = 'jpg';
            }
            
            $newName = "p{$product->id}.{$extension}";
            $targetPath = "products/{$newName}";

            if ($this->option('dry-run')) {
                $this->line("Would copy: {$sourcePath} -> {$targetPath}");
                $successCount++;
                continue;
            }

            try {
                // We use copy instead of rename to keep original files if needed elsewhere
                Storage::disk('public')->copy($sourcePath, $targetPath);
                
                // Cleanup temporary download
                if ($isUrl) {
                    Storage::disk('public')->delete($sourcePath);
                }

                // Verify the file was actually created before updating DB
                if (Storage::disk('public')->exists($targetPath)) {
                    $product->image = $newName;
                    $product->save();
                    $this->info("Sanitized product #{$product->id}: {$newName}");
                    $successCount++;
                } else {
                    $this->error("Failed to verify image on disk for product #{$product->id}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to sanitize #{$product->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Done! Success: {$successCount}, Errors: {$errorCount}, Skipped: {$skipCount}");
        
        return 0;
    }
}
