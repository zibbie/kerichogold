<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrawlLogResource\Pages;
use App\Models\CrawlLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class CrawlLogResource extends Resource
{
    protected static ?string $model = CrawlLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    protected static ?string $navigationGroup = 'Analityka SEO';

    protected static ?string $label = 'Log Crawlera';

    protected static ?string $pluralLabel = 'Logi Crawlerów';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bot_name')
                    ->label('Bot')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('status_code')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 200 && $state < 300 => 'success',
                        $state >= 300 && $state < 400 => 'warning',
                        $state >= 400 => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('crawled_at')
                    ->label('Data')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('crawled_at', 'desc')
            ->filters([
                SelectFilter::make('bot_name')
                    ->label('Filtr Botów')
                    ->options([
                        'Google' => 'Google',
                        'Bing' => 'Bing',
                        'OpenAI (GPT)' => 'OpenAI',
                        'Ahrefs' => 'Ahrefs',
                    ]),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCrawlLogs::route('/'),
        ];
    }
}
