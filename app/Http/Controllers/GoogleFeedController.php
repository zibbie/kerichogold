<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GoogleFeedController extends Controller
{
    public function index()
    {
        return response()->stream(function () {
            echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\n";
            echo '  <channel>' . "\n";
            echo '    <title>' . htmlspecialchars(config('app.name')) . '</title>' . "\n";
            echo '    <link>' . htmlspecialchars(url('/')) . '</link>' . "\n";
            echo '    <description>Google Merchant Center Product Feed</description>' . "\n";

            $products = Product::where('status', true)
                ->where('google_merchant_center_export', true)
                ->whereHas('category', function ($query) {
                    $query->where('status', true);
                })
                ->with('category')
                ->cursor();

            foreach ($products as $product) {
                $imageUrl = $product->main_image_url;
                if ($product->price <= 0 || str_contains($imageUrl, 'placehold.co')) {
                    continue;
                }
                echo '    <item>' . "\n";
                echo '      <g:id>' . $product->id . '</g:id>' . "\n";
                echo '      <g:title>' . htmlspecialchars($product->name) . '</g:title>' . "\n";
                // Preserve basic semantic tags for Google NLP
                $description = strip_tags($product->description, '<b><i><ul><li><p><br>');
                echo '      <g:description>' . htmlspecialchars($description) . '</g:description>' . "\n";
                echo '      <g:link>' . htmlspecialchars(route('product.details', ['slug' => $product->slug])) . '</g:link>' . "\n";
                echo '      <g:image_link>' . htmlspecialchars($imageUrl) . '</g:image_link>' . "\n";
                
                if ($product->weight > 0) {
                    echo '      <g:shipping_weight>' . $product->weight . ' kg</g:shipping_weight>' . "\n";
                }
                
                echo '      <g:condition>new</g:condition>' . "\n";
                echo '      <g:availability>' . ($product->quantity > 0 ? 'in stock' : 'out of stock') . '</g:availability>' . "\n";
                echo '      <g:price>' . number_format($product->price, 2, '.', '') . ' PLN</g:price>' . "\n";
                
                
                if ($product->category) {
                    echo '      <g:product_type>' . htmlspecialchars($product->category->name) . '</g:product_type>' . "\n";
                }
                
                $googleCategory = $product->google_product_category ?: ($product->category?->google_product_category);
                if ($googleCategory) {
                    echo '      <g:google_product_category>' . htmlspecialchars($googleCategory) . '</g:google_product_category>' . "\n";
                }
                
                echo '      <g:brand>' . htmlspecialchars($product->brand ?: 'Kericho Gold') . '</g:brand>' . "\n";
                if ($product->gtin) {
                    echo '      <g:gtin>' . htmlspecialchars($product->gtin) . '</g:gtin>' . "\n";
                    echo '      <g:identifier_exists>yes</g:identifier_exists>' . "\n";
                } else {
                    echo '      <g:identifier_exists>no</g:identifier_exists>' . "\n";
                }
                echo '      <g:mpn>' . htmlspecialchars($product->sku) . '</g:mpn>' . "\n";
                echo '    </item>' . "\n";
            }

            echo '  </channel>' . "\n";
            echo '</rss>' . "\n";
        }, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
