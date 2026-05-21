<?php

namespace App\Filament\Widgets;

use App\Models\BotVisit;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class BotActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Aktywność Botów (7 dni)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Ponieważ trend wymaga zewnętrznej paczki, użyjemy prostego zapytania Eloquent
        $data = BotVisit::selectRaw('DATE(created_at) as date, count(*) as visits')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('visits', 'date')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Wizyty botów (Google, Bing, AI)',
                    'data' => array_values($data),
                    'fill' => 'start',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
