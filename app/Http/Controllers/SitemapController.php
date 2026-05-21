<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('status', true)
            ->whereHas('category', function ($query) {
                $query->where('status', true);
            })
            ->whereNotNull('slug')
            ->with('category')
            ->get();

        $categories = Category::where('status', true)
            ->select('slug', 'updated_at')
            ->get();

        $pages = Page::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // Homepage
        $xml .= $this->urlEntry(url('/'), now()->toW3cString(), 'daily', '1.0');

        // Categories
        foreach ($categories as $category) {
            $xml .= $this->urlEntry(
                route('category.details', ['slug' => $category->slug]),
                $category->updated_at->toW3cString(),
                'weekly',
                '0.8'
            );
        }

        // Products
        foreach ($products as $product) {
            $xml .= $this->urlEntry(
                route('product.details', ['slug' => $product->slug]),
                $product->updated_at->toW3cString(),
                'daily',
                '0.6',
                $product->main_image_url,
                $product->name
            );
        }

        // CMS Pages
        foreach ($pages as $page) {
            $xml .= $this->urlEntry(
                route('page.details', ['slug' => $page->slug]),
                $page->updated_at->toW3cString(),
                'monthly',
                '0.3'
            );
        }

        $xml .= '</urlset>';

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    private function urlEntry(string $loc, string $lastmod, string $changefreq, string $priority, ?string $imageUrl = null, ?string $imageTitle = null): string
    {
        $entry = "<url>";
        $entry .= "<loc>{$loc}</loc>";
        $entry .= "<lastmod>{$lastmod}</lastmod>";
        $entry .= "<changefreq>{$changefreq}</changefreq>";
        $entry .= "<priority>{$priority}</priority>";
        
        if ($imageUrl) {
            $entry .= "<image:image>";
            $entry .= "<image:loc>" . htmlspecialchars($imageUrl) . "</image:loc>";
            if ($imageTitle) {
                $entry .= "<image:title>" . htmlspecialchars($imageTitle) . "</image:title>";
            }
            $entry .= "</image:image>";
        }
        
        $entry .= "</url>";
        return $entry;
    }
}
