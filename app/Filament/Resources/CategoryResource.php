<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Zarządzanie sklepem';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Kategoria';
    
    protected static ?string $pluralLabel = 'Kategorie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nazwa')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('URL (slug)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('parent_id')
                    ->label('Kategoria nadrzędna')
                    ->relationship('parent', 'name')
                    ->placeholder('Brak'),
                Forms\Components\FileUpload::make('image')
                    ->label('Zdjęcie')
                    ->image()
                    ->directory('categories'),
                Forms\Components\TextInput::make('icon')
                    ->label('Ikona (Material Symbols)')
                    ->placeholder('np. bathtub, water_drop')
                    ->helperText('Wpisz nazwę ikony z biblioteki Material Symbols.'),
                Forms\Components\Toggle::make('status')
                    ->label('Status (widoczność)')
                    ->default(true),
                Forms\Components\TextInput::make('google_product_category')
                    ->label('Google Product Category ID')
                    ->placeholder('np. 505315')
                    ->helperText('Kod kategorii Google dla Merchant Center (opcjonalnie)'),

                Forms\Components\Section::make('SEO')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Tytuł SEO')
                            ->maxLength(120)
                            ->helperText('Optymalnie 50-60 znaków'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Opis SEO')
                            ->maxLength(320)
                            ->rows(3),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Słowa kluczowe'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL (slug)'),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Kategoria nadrzędna'),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ikona'),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->reorderable('position')
            ->defaultSort('position', 'asc')
            ->authorizeReorder(true)
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edytuj'),
                Tables\Actions\DeleteAction::make()
                    ->label('Usuń'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Usuń zaznaczone'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
