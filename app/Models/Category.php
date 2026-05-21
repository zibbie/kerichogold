<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'google_product_category',
        'status',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'position',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if (empty($model->slug) || $model->isDirty('name')) {
                $originalSlug = Str::slug($model->name);
                
                $count = static::where('slug', 'LIKE', "{$originalSlug}%")
                    ->where('id', '!=', $model->id)
                    ->count();
                
                $model->slug = $count > 0 ? "{$originalSlug}-" . ($count + 1) : $originalSlug;
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('position', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get SEO title with fallback to category name
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->name . ' - Najlepsza Herbata z Kenii | Kericho Gold';
    }

    /**
     * Get SEO description with fallback based on name
     */
    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        $fallback = $this->description ? strip_tags($this->description) : "Poznaj produkty z kategorii {$this->name} w Kericho Gold. Wyselekcjonowane, oryginalne kenijskie herbaty dla każdego.";
        return Str::limit($fallback, 160);
    }
}
