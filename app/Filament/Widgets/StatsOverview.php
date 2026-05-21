<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nowe Zamówienia', Order::where('status', 'pending')->count())
                ->description('Oczekują na akcję')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('danger'),
            Stat::make('Wszystkie Zamówienia', Order::count())
                ->description('Wszystkie zamówienia w systemie')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
            Stat::make('Aktywne Produkty', Product::count())
                ->description('Produkty w ofercie')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}
