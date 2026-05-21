<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'google_merchant_center_export',
        'google_product_category',
        'image',
        'description',
        'attributes',
        'category_id',
        'is_hit',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'gallery',
        'delivery_time',
        'shipping_class',
        'items_per_package',
        'gtin',
        'brand',
        'price',
        'quantity',
        'status',
        'canonical_url',
        'purchase_price',
        'weight',
    ];

    protected $casts = [
        'status' => 'boolean',
        'google_merchant_center_export' => 'boolean',
        'is_hit' => 'boolean',
        'attributes' => 'array',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'gallery' => 'array',
        'items_per_package' => 'integer',
        'weight' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($product) {
            // Only generate slug if it's empty to prevent 404s on name updates
            if (empty($product->slug)) {
                $originalSlug = Str::slug($product->name);
                
                $count = static::where('slug', 'LIKE', "{$originalSlug}%")
                    ->where('id', '!=', $product->id)
                    ->count();
                
                $product->slug = $count > 0 ? "{$originalSlug}-" . ($count + 1) : $originalSlug;
            }
        });

        static::saved(function ($model) {
            // Push product updates to BaseLinker asynchronously
            dispatch(new \App\Jobs\PushProductToBaseLinker($model))->afterCommit();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get SEO title with fallback to product name
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->name . ' | Kericho Gold';
    }

    /**
     * Get SEO description with fallback to stripped product description
     */
    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        $desc = strip_tags($this->description);
        if (empty($desc)) {
            $desc = "Kup {$this->name} w Kericho Gold. Najwyższej jakości kenijska herbata czarna, zielona i ziołowa. Szybka wysyłka.";
        }

        return Str::limit($desc, 160);
    }

    /**
     * Get the canonical URL for this product
     */
    public function getCanonicalAttribute(): string
    {
        return $this->canonical_url ?: route('product.details', ['slug' => $this->slug]);
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://placehold.co/600x800.png?text=' . urlencode($this->name);
        }

        // If it's already a full URL, return it ONLY if it matches our whitelist (prevent stored XSS/Open Redirect)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            $allowedHost = parse_url(config('app.url'), PHP_URL_HOST);
            $imageHost = parse_url($this->image, PHP_URL_HOST);
            
            if ($imageHost !== $allowedHost && !empty(config('services.images.allowed_hosts')) && !in_array($imageHost, config('services.images.allowed_hosts'))) {
                 return 'https://placehold.co/600x800/f7faf5/4a654e?text=External+Image+Blocked';
            }
            return $this->image;
        }

        $path = $this->image;
        if (!\Illuminate\Support\Str::contains($path, '/')) {
            $path = 'products/' . $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }

    /**
     * Get gallery image URLs
     */
    public function getGalleryUrlsAttribute(): array
    {
        if (empty($this->gallery) || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function ($img) {
            if (filter_var($img, FILTER_VALIDATE_URL)) {
                return $img;
            }

            $path = $img;
            if (!Str::contains($path, '/')) {
                $path = 'products/' . $path;
            }

            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }, $this->gallery);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => strip_tags($this->description),
            'category' => $this->category?->name,
            'brand' => $this->brand,
            'price' => (float) $this->price,
            'is_hit' => (bool) $this->is_hit,
            'status' => (bool) $this->status,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status;
    }

    /**
     * Get short description
     */
    public function getDescriptionShortAttribute(): string
    {
        return Str::limit(strip_tags($this->description), 100);
    }
}
