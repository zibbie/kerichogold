<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeCtaResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HomeCtaResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Strony informacyjne';

    protected static ?string $label = 'Sekcja Kontakt Home';
    
    protected static ?string $pluralLabel = 'Sekcja Kontakt Home';

    protected static ?int $navigationSort = 6;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'like', 'cta_home_%');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function ($record) {
                $schema = [
                    Forms\Components\Placeholder::make('key_display')
                        ->label('Klucz techniczny')
                        ->content($record?->key),
                    
                    Forms\Components\Placeholder::make('label_display')
                        ->label('Ustawienie')
                        ->content(match($record?->key) {
                            'cta_home_is_visible' => 'Widoczność sekcji',
                            'cta_home_title' => 'Tytuł',
                            'cta_home_description' => 'Opis',
                            'cta_home_button_text' => 'Tekst przycisku',
                            'cta_home_button_link' => 'Link przycisku',
                            'cta_home_bg_color' => 'Kolor tła',
                            'cta_home_text_color' => 'Kolor tekstu',
                            default => $record?->key,
                        }),
                ];

                $valueField = match($record?->key) {
                    'cta_home_is_visible' => Forms\Components\Toggle::make('value')
                        ->label('Aktywny'),
                    
                    'cta_home_bg_color', 'cta_home_text_color' => Forms\Components\ColorPicker::make('value')
                        ->label('Kolor')
                        ->required(),
                    
                    default => Forms\Components\TextInput::make('value')
                        ->label('Wartość')
                        ->required(),
                };

                $schema[] = Forms\Components\Section::make('Szczegóły')
                    ->schema([$valueField]);

                return $schema;
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key_label')
                    ->label('Ustawienie')
                    ->state(fn ($record) => match($record?->key) {
                        'cta_home_is_visible' => 'Widoczność sekcji',
                        'cta_home_title' => 'Tytuł',
                        'cta_home_description' => 'Opis',
                        'cta_home_button_text' => 'Tekst przycisku',
                        'cta_home_button_link' => 'Link przycisku',
                        'cta_home_bg_color' => 'Kolor tła',
                        'cta_home_text_color' => 'Kolor tekstu',
                        default => $record?->key,
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Wartość')
                    ->limit(50),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edytuj'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHomeCtas::route('/'),
        ];
    }
}
