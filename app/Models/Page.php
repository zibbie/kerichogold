<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_visible_in_footer',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_visible_in_footer' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });
    }
}
