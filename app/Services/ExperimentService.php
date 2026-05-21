<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class ExperimentService
{
    /**
     * Get the active variant for an experiment.
     */
    public function getVariant(string $slug): ?string
    {
        return Session::get("ab_test_{$slug}");
    }

    /**
     * Check if user is in a specific variant.
     */
    public function isVariant(string $slug, string $variantKey): bool
    {
        return $this->getVariant($slug) === $variantKey;
    }
}
