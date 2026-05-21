<?php

namespace App\Filament\Widgets;

use App\Models\CrawlLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class CrawlStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $last24h = Carbon::now()->subDay();
        
        $totalCrawls = CrawlLog::where('crawled_at', '>=', $last24h)->count();
        $googleCrawls = CrawlLog::where('bot_name', 'Google')->where('crawled_at', '>=', $last24h)->count();
        $errors = CrawlLog::where('status_code', '>=', 400)->where('crawled_at', '>=', $last24h)->count();

        return [
            Stat::make('Indeksowanie (24h)', $totalCrawls)
                ->description('Łączna liczba wizyt robotów')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Googlebot (24h)', $googleCrawls)
                ->description('Wizyty robota Google')
                ->color('info'),
            Stat::make('Błędy Crawlera (24h)', $errors)
                ->description('Kody statusu 4xx/5xx')
                ->color($errors > 0 ? 'danger' : 'success'),
        ];
    }
}
