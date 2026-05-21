<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperimentResource\Pages;
use App\Models\Experiment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class ExperimentResource extends Resource
{
    protected static ?string $model = Experiment::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Analityka SEO';

    protected static ?string $label = 'Eksperyment A/B';

    protected static ?string $pluralLabel = 'Eksperymenty A/B';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ustawienia Eksperymentu')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nazwa')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug (ID)')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_active')
                            ->label('Aktywny')
                            ->inline(false),
                        Forms\Components\Textarea::make('description')
                            ->label('Opis i cel testu')
                            ->placeholder('Zwiększenie wartości koszyka poprzez wizualny pasek postępu...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Warianty')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nazwa Wariantu (np. Kontrola, Testowy)')
                                    ->required(),
                                TextInput::make('key')
                                    ->label('Klucz (A/B)')
                                    ->required(),
                                TextInput::make('weight')
                                    ->label('Waga (%)')
                                    ->numeric()
                                    ->default(50)
                                    ->required(),
                            ])->columns(3)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nazwa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('ID'),
                ToggleColumn::make('is_active')
                    ->label('Aktywny'),
                TextColumn::make('variants_count')
                    ->label('Warianty')
                    ->counts('variants'),
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListExperiments::route('/'),
            'create' => Pages\CreateExperiment::route('/create'),
            'edit' => Pages\EditExperiment::route('/{record}/edit'),
        ];
    }
}
