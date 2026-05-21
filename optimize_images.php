<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

ini_set('memory_limit', '512M');

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$directory = 'products';
$files = Storage::disk('public')->files($directory);

echo "Found " . count($files) . " files in storage/public/$directory\n";

foreach ($files as $file) {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) continue;

    $path = Storage::disk('public')->path($file);
    $webpPath = $path . '.webp';

    // Skip if webp already exists and is newer than source
    if (file_exists($webpPath) && filemtime($webpPath) > filemtime($path)) {
        echo "Skipping $file (WebP already exists)\n";
        continue;
    }

    echo "Optimizing $file... ";

    try {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            echo "Failed (not an image)\n";
            continue;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Create image from source
        if ($mime == 'image/jpeg') {
            $src = imagecreatefromjpeg($path);
        } elseif ($mime == 'image/png') {
            $src = imagecreatefrompng($path);
            imagepalettetotruecolor($src);
            imagealphablending($src, true);
            imagesavealpha($src, true);
        } else {
            echo "Unsupported mime: $mime\n";
            continue;
        }

        // Calculate new dimensions (max 1024px)
        $maxSize = 1024;
        if ($width > $maxSize || $height > $maxSize) {
            if ($width > $height) {
                $newWidth = $maxSize;
                $newHeight = floor($height * ($maxSize / $width));
            } else {
                $newHeight = $maxSize;
                $newWidth = floor($width * ($maxSize / $height));
            }

            $dst = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG
            if ($mime == 'image/png') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($src);
            $src = $dst;
        }

        // Save as WebP
        imagewebp($src, $webpPath, 80);
        imagedestroy($src);

        echo "Done! (Saved to " . basename($webpPath) . ")\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "\nOptimization complete.\n";
