<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Experiment;
use Illuminate\Support\Facades\Session;

class ExperimentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only for GET requests (visits)
        if (!$request->isMethod('get')) {
            return $next($request);
        }

        $activeExperiments = Experiment::where('is_active', true)->with('variants')->get();
        
        $isBot = $request->userAgent() && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $request->userAgent());

        foreach ($activeExperiments as $experiment) {
            $sessionKey = "ab_test_{$experiment->slug}";

            if (!Session::has($sessionKey)) {
                if ($isBot) {
                    $variant = $experiment->variants->first();
                    Session::put($sessionKey, $variant->key);
                } else {
                    $variant = $this->assignVariant($experiment);
                    Session::put($sessionKey, $variant->key);
                    
                    // Track visit
                    $variant->increment('visits_count');
                }
            }
        }

        return $next($request);
    }

    protected function assignVariant($experiment)
    {
        $variants = $experiment->variants;
        $totalWeight = $variants->sum('weight');
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($variants as $variant) {
            $currentWeight += $variant->weight;
            if ($random <= $currentWeight) {
                return $variant;
            }
        }

        return $variants->first();
    }
}
