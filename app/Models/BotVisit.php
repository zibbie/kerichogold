<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotVisit extends Model
{
    protected $fillable = [
        'bot_name',
        'url',
        'ip_address',
        'user_agent',
    ];
}
