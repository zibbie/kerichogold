<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Ustawienia';

    protected static ?string $label = 'Ustawienie';
    
    protected static ?string $pluralLabel = 'Ustawienia';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'not like', 'hero_%');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function ($record) {
                $schema = [
                    Forms\Components\Section::make('Szczegóły ustawienia')
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->label('Klucz techniczny')
                                ->required()
                                ->disabled()
                                ->maxLength(255),
                            
                            Forms\Components\Select::make('type')
                                ->label('Typ danych')
                                ->options([
                                    'string' => 'Tekst',
                                    'json' => 'JSON',
                                    'image' => 'Zdjęcie (Upload)',
                                ])
                                ->required()
                                ->live()
                                ->hidden(fn () => in_array($record?->key, ['google_ads_id', 'google_analytics_id', 'google_tag_manager_id', 'quality_guarantee', 'timezone'])),
                        ])
                        ->columns(2),
                ];

                // Determine the correct component for 'value'
                $valueComponent = match (true) {
                    $record?->key === 'timezone' => 
                        Forms\Components\Select::make('value')
                            ->label('Strefa czasowa')
                            ->options(collect(\DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz])->toArray())
                            ->searchable()
                            ->required(),

                    $record?->key === 'quality_guarantee' =>
                        Forms\Components\Textarea::make('value')
                            ->label('Gwarancja Jakości - Treść')
                            ->rows(6)
                            ->required(),

                    $record?->type === 'image' =>
                        Forms\Components\FileUpload::make('value')
                            ->label('Zdjęcie')
                            ->image()
                            ->directory('settings')
                            ->visibility('public')
                            ->required(),

                    default =>
                        Forms\Components\TextInput::make('value')
                            ->label(match($record?->key) {
                                'google_ads_id' => 'Google Ads ID',
                                'google_analytics_id' => 'Google Analytics 4 ID',
                                'google_tag_manager_id' => 'Google Tag Manager ID',
                                default => 'Wartość',
                            })
                            ->helperText(match($record?->key) {
                                'google_ads_id' => 'Format: AW-XXXXXXXXX',
                                'google_analytics_id' => 'Format: G-XXXXXXXXXX',
                                'google_tag_manager_id' => 'Format: GTM-XXXXXXX',
                                default => null,
                            })
                            ->required(),
                };

                $schema[] = Forms\Components\Section::make('Wartość ustawienia')
                    ->schema([$valueComponent]);

                return $schema;
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Klucz'),
                Tables\Columns\ImageColumn::make('value_preview')
                    ->label('Podgląd')
                    ->state(fn ($record) => $record->type === 'image' ? Setting::get($record->key) : null)
                    ->circular()
                    ->size(40)
                    ->visible(fn ($record) => $record?->type === 'image'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Wartość')
                    ->limit(50)
                    ->hidden(fn ($record) => $record?->type === 'image'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Typ'),
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
            'index' => Pages\ManageSettings::route('/'),
        ];
    }
}
