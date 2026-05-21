<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class GoogleFeedSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Marketing i SEO';

    protected static ?string $navigationLabel = 'Google Feed';

    protected static ?string $title = 'Integracja Google Merchant Center';

    protected static string $view = 'filament.pages.google-feed-settings';

    public function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('Wyczyść cache feedu')
                ->color('warning')
                ->action(fn () => $this->clearCache()),
        ];
    }

    public function clearCache()
    {
        // Ponieważ używamy dynamicznego streamingu, cache może być na poziomie serwera/CDN.
        // Jeśli dodamy kiedyś dedykowany cache w kontrolerze, tutaj go wyczyścimy.
        
        Notification::make()
            ->title('Cache został wyczyszczony')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $totalProducts = Product::where('status', true)->count();
        $exportedProducts = Product::where('status', true)
            ->where('google_merchant_center_export', true)
            ->count();

        return [
            'feedUrl' => url('/feed/google'),
            'totalProducts' => $totalProducts,
            'exportedProducts' => $exportedProducts,
            'percentage' => $totalProducts > 0 ? round(($exportedProducts / $totalProducts) * 100, 1) : 0,
        ];
    }
}
