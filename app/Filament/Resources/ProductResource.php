<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Zarządzanie sklepem';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Produkt';
    
    protected static ?string $pluralLabel = 'Produkty';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Podstawowe informacje')
                    ->description('Główne szczegóły produktu')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nazwa')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('sku')
                                ->label('SKU')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Opis')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Cena i Magazyn')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Cena')
                                ->required()
                                ->numeric()
                                ->prefix('PLN'),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Ilość')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Forms\Components\TextInput::make('purchase_price')
                                ->label('Cena zakupu (Netto)')
                                ->helperText('Służy do wyliczania zysku w analityce')
                                ->numeric()
                                ->prefix('PLN'),
                        ]),
                        Forms\Components\Toggle::make('status')
                            ->label('Widoczny w sklepie')
                            ->default(true),
                        Forms\Components\Toggle::make('is_hit')
                            ->label('Bestseller (Hit)')
                            ->default(false),
                        Forms\Components\Toggle::make('google_merchant_center_export')
                            ->label('Eksportuj do Google Merchant Center')
                            ->helperText('Zaznacz, aby produkt był widoczny w pliku feed dla Google Ads')
                            ->default(true),
                        Forms\Components\TextInput::make('google_product_category')
                            ->label('Indywidualny numer kategorii Google')
                            ->helperText('Jeśli puste, zostanie użyty numer przypisany do kategorii sklepu.')
                            ->numeric(),
                        Forms\Components\TextInput::make('gtin')
                            ->label('GTIN / EAN')
                            ->helperText('Kod kreskowy produktu (EAN)'),
                        Forms\Components\TextInput::make('brand')
                            ->label('Marka')
                            ->default('Nevro'),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategoria')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Zdjęcie główne')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Maksymalny rozmiar: 10MB. Format: JPG, PNG lub WebP. Sugerowane proporcje 1:1.')
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        Forms\Components\FileUpload::make('gallery')
                            ->label('Galeria zdjęć')
                            ->image()
                            ->multiple()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Maksymalnie 10MB na plik. Możesz wybrać wiele zdjęć naraz.')
                            ->reorderable()
                            ->appendFiles()
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public'),
                    ]),

                Section::make('Dostawa i Wysyłka')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('delivery_time')
                                ->label('Czas dostawy')
                                ->placeholder('np. 24h, 3 dni...')
                                ->datalist([
                                    '24h',
                                    '48h',
                                    '3 dni',
                                    '7 dni',
                                    '14 dni',
                                ])
                                ->default('24h'),
                            Forms\Components\Select::make('shipping_class')
                                ->label('Klasa wysyłkowa')
                                ->options([
                                    'paczkomat_a' => 'InPost Paczkomat - Gabaryt A (15.99 zł)',
                                    'paczkomat_b' => 'InPost Paczkomat - Gabaryt B (16.99 zł)',
                                    'paczkomat_c' => 'InPost Paczkomat - Gabaryt C (19.99 zł)',
                                    'courier_standard' => 'Kurier Standard (18.99 zł)',
                                    'courier_heavy' => 'Kurier Ciężki (24.99 zł)',
                                    'courier_oversize' => 'Kurier Gabaryt (80.00 zł)',
                                    'pallet' => 'Paleta (260.00 zł)',
                                ])
                                ->required()
                                ->default('courier_standard'),
                            Forms\Components\TextInput::make('items_per_package')
                                ->label('Sztuk w paczce')
                                ->numeric()
                                ->default(1)
                                ->minValue(1),
                            Forms\Components\TextInput::make('weight')
                                ->label('Waga (kg)')
                                ->numeric()
                                ->step(0.01)
                                ->default(0.50)
                                ->helperText('Waga pojedynczego produktu (wymagane przez Google Merchant Center)'),
                        ]),
                    ]),

                Section::make('SEO')
                    ->description('Optymalizacja pod wyszukiwarki')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('URL (slug)')
                            ->helperText('Zostaw puste, aby wygenerować automatycznie z nazwy')
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Tytuł SEO')
                            ->maxLength(120)
                            ->helperText('Optymalnie 50-60 znaków. Domyślnie: nazwa produktu.'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Opis SEO')
                            ->maxLength(320)
                            ->rows(3)
                            ->helperText('Optymalnie 120-160 znaków. Domyślnie: skrót opisu produktu.'),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Słowa kluczowe')
                            ->helperText('Oddzielone przecinkami'),
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->helperText('Pozostaw puste dla domyślnego adresu produktu'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Zdjęcie')
                    ->disk('public')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit: contain; background: #f9fafb;']),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_time')
                    ->label('Czas dostawy')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Cena')
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Ilość')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->quantity < 5 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('profit')
                    ->label('Marża (orient.)')
                    ->getStateUsing(fn ($record) => $record->price - $record->purchase_price)
                    ->money('PLN')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->description('Zysk na 1 szt.'),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\IconColumn::make('google_merchant_center_export')
                    ->label('GMC')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edytuj'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('changeCategory')
                        ->label('Zmień kategorię')
                        ->icon('heroicon-o-folder')
                        ->form([
                            Forms\Components\Select::make('category_id')
                                ->label('Kategoria')
                                ->options(\App\Models\Category::all()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each->update(['category_id' => $data['category_id']]);
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
