<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_view_data');
        });
    }

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        if ($setting->type === 'json') {
            return json_decode($setting->value, true);
        }

        if ($setting->type === 'image') {
            if (empty($setting->value)) return $default;
            if (str_starts_with($setting->value, 'http')) return $setting->value;
            return \Illuminate\Support\Facades\Storage::url($setting->value);
        }

        return $setting->value;
    }

    public static function set($key, $value, $type = 'string')
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
