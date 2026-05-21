<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experiment extends Model
{
    protected $fillable = ['name', 'slug', 'is_active', 'started_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ExperimentVariant::class);
    }
}
