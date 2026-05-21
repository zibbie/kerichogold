<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FooterResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'Strony informacyjne';

    protected static ?string $label = 'Stopka';
    
    protected static ?string $pluralLabel = 'Stopka';

    protected static ?int $navigationSort = 7;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'like', 'footer_%');
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
                            'footer_description' => 'Opis w stopce',
                            'footer_email' => 'Email kontaktowy',
                            'footer_phone' => 'Telefon kontaktowy',
                            'footer_copyright' => 'Tekst copyright (prawa autorskie)',
                            default => $record?->key,
                        }),
                ];

                $valueField = match($record?->key) {
                    'footer_description' => Forms\Components\Textarea::make('value')
                        ->label('Treść')
                        ->rows(3)
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
                        'footer_description' => 'Opis w stopce',
                        'footer_email' => 'Email kontaktowy',
                        'footer_phone' => 'Telefon kontaktowy',
                        'footer_copyright' => 'Tekst copyright (prawa autorskie)',
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
            'index' => Pages\ManageFooters::route('/'),
        ];
    }
}
