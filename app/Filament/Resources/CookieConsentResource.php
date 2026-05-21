<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CookieConsentResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CookieConsentResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Ustawienia';

    protected static ?string $label = 'Zgoda na Cookies';
    
    protected static ?string $pluralLabel = 'Zgoda na Cookies';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'like', 'cookie_consent_%');
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
                            'cookie_consent_active' => 'Czy pasek cookies jest aktywny?',
                            'cookie_consent_title' => 'Tytuł w pasku',
                            'cookie_consent_description' => 'Treść komunikatu',
                            'cookie_consent_policy_url' => 'Link do polityki prywatności/cookies',
                            default => $record?->key,
                        }),
                ];

                $valueField = match($record?->key) {
                    'cookie_consent_active' => Forms\Components\Toggle::make('value')
                        ->label('Aktywny')
                        ->afterStateHydrated(fn ($component, $state) => $component->state((bool) $state))
                        ->dehydrateStateUsing(fn ($state) => $state ? '1' : '0')
                        ->required(),
                    
                    'cookie_consent_description' => Forms\Components\Textarea::make('value')
                        ->label('Treść')
                        ->rows(4)
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
                        'cookie_consent_active' => 'Status aktywności',
                        'cookie_consent_title' => 'Tytuł',
                        'cookie_consent_description' => 'Treść komunikatu',
                        'cookie_consent_policy_url' => 'URL polityki',
                        default => $record?->key,
                    }),
                Tables\Columns\TextColumn::make('value_display')
                    ->label('Wartość')
                    ->state(fn ($record) => $record->key === 'cookie_consent_active' ? ($record->value === '1' ? 'TAK' : 'NIE') : $record->value)
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
            'index' => Pages\ManageCookieConsents::route('/'),
        ];
    }
}
