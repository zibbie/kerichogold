<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected static ?string $heading = 'Ostatnie Zamówienia';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderResource::getEloquentQuery()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Numer'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Klient'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Suma')
                    ->money('PLN'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ]);
    }
}
