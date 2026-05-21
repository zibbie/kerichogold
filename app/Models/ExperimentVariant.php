<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentVariant extends Model
{
    protected $fillable = ['experiment_id', 'name', 'key', 'weight', 'visits_count', 'conversions_count'];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }
}
