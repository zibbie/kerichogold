<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawlLog extends Model
{
    protected $fillable = [
        'bot_name',
        'url',
        'status_code',
        'ip_address',
        'user_agent',
        'response_time',
        'crawled_at',
    ];

    protected $casts = [
        'crawled_at' => 'datetime',
        'response_time' => 'float',
    ];
}
