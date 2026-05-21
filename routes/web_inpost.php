<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

Route::get('/inpost-sdk-bridge', function () {
    return Cache::remember('inpost_sdk_bridge', 3600, function () {
        try {
            $response = Http::get('https://geowidget.inpost.pl/sdk/for-javascript.js');
            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', 'application/javascript')
                    ->header('Cache-Control', 'public, max-age=3600');
            }
        } catch (\Exception $e) {
            return response('console.error("InPost Bridge Error: " + ' . json_encode($e->getMessage()) . ');', 200)
                ->header('Content-Type', 'application/javascript');
        }
        
        return response('console.error("InPost Bridge: Failed to fetch SDK");', 404);
    });
});
