<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
Schedule::command('logs:parse-crawl')->hourly();
Schedule::command('bl:sync-orders')->everyFiveMinutes();
Schedule::command('bl:sync-stock')->everyFifteenMinutes();
