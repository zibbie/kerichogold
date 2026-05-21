<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class SeoService
{
    /**
     * Generate Organization JSON-LD (global, on every page)
     */
    public function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/') . '#organization',
            'name' => 'Kericho Gold',
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/logo.png'),
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'kontakt@kerichogold.pl',
                'contactType' => 'customer service',
                'availableLanguage' => 'Polish',
            ],
            'sameAs' => [],
        ];
    }

    /**
     * Generate WebSite JSON-LD with SearchAction
     */
    public function webSiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'name' => 'Kericho Gold',
            'url' => url('/'),
            'publisher' => [
                '@id' => url('/') . '#organization',
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('shop') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Generate Product + Offer JSON-LD
     */
    public function productSchema(Product $product): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            '@id' => route('product.details', ['slug' => $product->slug]) . '#product',
            'name' => $product->name,
            'description' => strip_tags($product->description),
            'sku' => $product->sku,
            'gtin' => $product->gtin,
            'image' => $product->main_image_url,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand ?: 'Kericho Gold',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.details', ['slug' => $product->slug]),
                'priceCurrency' => 'PLN',
                'price' => number_format($product->price, 2, '.', ''),
                'priceValidUntil' => now()->endOfYear()->toDateString(),
                'availability' => $product->quantity > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@id' => url('/') . '#organization',
                ],
            ],
        ];

        if ($product->category) {
            $schema['category'] = $product->category->name;
        }

        return $schema;
    }

    /**
     * Generate BreadcrumbList JSON-LD
     */
    public function breadcrumbSchema(array $items): array
    {
        $listItems = [];
        foreach ($items as $i => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Generate CollectionPage JSON-LD for categories
     */
    public function collectionPageSchema(Category $category): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            '@id' => route('category.details', ['slug' => $category->slug]) . '#webpage',
            'name' => $category->name,
            'description' => $category->meta_description ?? $category->description,
            'url' => route('category.details', ['slug' => $category->slug]),
            'isPartOf' => [
                '@id' => url('/') . '#website',
            ],
        ];

        if ($category->image) {
            $schema['image'] = asset('storage/' . $category->image);
        }

        return $schema;
    }

    /**
     * Render JSON-LD script tag from array
     */
    public function renderJsonLd(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Render multiple JSON-LD blocks
     */
    public function renderMultipleJsonLd(array $schemas): string
    {
        return implode("\n", array_map(fn($s) => $this->renderJsonLd($s), $schemas));
    }
}
