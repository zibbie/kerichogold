<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroBannerResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HeroBannerResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationGroup = 'Strony informacyjne';

    protected static ?string $label = 'Baner Home';
    
    protected static ?string $pluralLabel = 'Baner Home';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'like', 'hero_%');
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
                            'hero_is_visible' => 'Widoczność sekcji',
                            'hero_title' => 'Tytuł (H1)',
                            'hero_description' => 'Opis',
                            'hero_button_text' => 'Tekst przycisku',
                            'hero_button_link' => 'Link przycisku',
                            'hero_image_url' => 'Zdjęcie tła',
                            'hero_title_color' => 'Kolor tytułu',
                            'hero_description_color' => 'Kolor opisu',
                            'hero_button_bg_color' => 'Kolor przycisku (tło)',
                            'hero_button_text_color' => 'Kolor tekstu przycisku',
                            'hero_text_bg' => 'Tło tekstu (Pasek)',
                            default => $record?->key,
                        }),
                ];

                $valueField = match($record?->key) {
                    'hero_is_visible' => Forms\Components\Toggle::make('value')
                        ->label('Aktywny'),
                    
                    'hero_image_url' => Forms\Components\FileUpload::make('value')
                        ->label('Zdjęcie')
                        ->image()
                        ->directory('settings')
                        ->visibility('public')
                        ->required(),
                    
                    'hero_title_color', 'hero_description_color', 'hero_button_bg_color', 'hero_button_text_color' => Forms\Components\ColorPicker::make('value')
                        ->label('Kolor')
                        ->required(),

                    'hero_text_bg' => Forms\Components\Group::make([
                        Forms\Components\ColorPicker::make('color')
                            ->label('Kolor tła')
                            ->required(),
                        Forms\Components\Select::make('opacity')
                            ->label('Przezroczystość (%)')
                            ->options([
                                0 => '0% (brak tła)',
                                10 => '10%',
                                20 => '20%',
                                30 => '30%',
                                40 => '40%',
                                50 => '50%',
                                60 => '60%',
                                70 => '70%',
                                80 => '80%',
                                90 => '90%',
                                100 => '100% (pełne tło)',
                            ])
                            ->default(50)
                            ->required(),
                    ])
                    ->statePath('value')
                    ->afterStateHydrated(fn ($component, $state) => $component->state(is_string($state) ? json_decode($state, true) : $state))
                    ->dehydrateStateUsing(fn ($state) => json_encode($state)),
                    
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
                        'hero_is_visible' => 'Widoczność sekcji',
                        'hero_title' => 'Tytuł (H1)',
                        'hero_description' => 'Opis',
                        'hero_button_text' => 'Tekst przycisku',
                        'hero_button_link' => 'Link przycisku',
                        'hero_image_url' => 'Zdjęcie tła',
                        'hero_title_color' => 'Kolor tytułu',
                        'hero_description_color' => 'Kolor opisu',
                        'hero_button_bg_color' => 'Kolor przycisku (tło)',
                        'hero_button_text_color' => 'Kolor tekstu przycisku',
                        'hero_text_bg' => 'Tło tekstu (Pasek)',
                        default => $record?->key,
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Wartość')
                    ->limit(50)
                    ->formatStateUsing(fn ($state) => is_array($state) ? 'Zbiór danych' : (string) $state),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edytuj'),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHeroBanners::route('/'),
        ];
    }
}
