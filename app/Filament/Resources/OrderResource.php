<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Zarządzanie sklepem';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Zamówienie';
    
    protected static ?string $pluralLabel = 'Zamówienia';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'danger' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Podsumowanie zamówienia')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('order_number')
                                ->label('Numer zamówienia')
                                ->disabled(),
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'pending' => 'Oczekujące',
                                    'paid' => 'Opłacone',
                                    'processing' => 'W trakcie realizacji',
                                    'shipped' => 'Wysłane',
                                    'completed' => 'Zakończone',
                                    'cancelled' => 'Anulowane',
                                    'refunded' => 'Zwrócone',
                                    'payment_failed' => 'Błąd płatności',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('total')
                                ->label('Suma')
                                ->numeric()
                                ->prefix('PLN')
                                ->disabled(),
                        ]),
                    ]),

                Section::make('Dane klienta')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Imię i Nazwisko'),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email(),
                            Forms\Components\TextInput::make('phone')
                                ->label('Telefon')
                                ->tel(),
                            Forms\Components\TextInput::make('city')
                                ->label('Miasto'),
                            Forms\Components\TextInput::make('zip')
                                ->label('Kod pocztowy'),
                        ]),
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Adres dostawy')
                            ->rows(3)
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return $state['address'] ?? '';
                                }
                                return $state;
                            })
                            ->dehydrateStateUsing(function ($state, $record, Forms\Get $get) {
                                $oldAddress = is_array($record?->shipping_address) ? $record->shipping_address : [];
                                return array_merge($oldAddress, [
                                    'address' => $state,
                                    'name' => $get('name'),
                                    'city' => $get('city'),
                                    'zip' => $get('zip'),
                                    'phone' => $get('phone'),
                                    'email' => $get('email'),
                                ]);
                            }),
                    ]),

                Section::make('Płatność i Wysyłka')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('payment_method')
                                ->label('Metoda płatności'),
                            Forms\Components\Select::make('payment_status')
                                ->label('Status płatności')
                                ->options([
                                    'pending' => 'Oczekuje',
                                    'paid' => 'Opłacone',
                                    'completed' => 'Zaksięgowane (completed)',
                                    'failed' => 'Błąd płatności',
                                ]),
                            Forms\Components\TextInput::make('shipping_method')
                                ->label('Metoda wysyłki'),
                            Forms\Components\TextInput::make('shipping_cost')
                                ->label('Koszt wysyłki')
                                ->numeric()
                                ->prefix('PLN'),
                        ]),
                    ]),

                Section::make('Dokument sprzedaży')
                    ->schema([
                        Forms\Components\Toggle::make('wants_invoice')
                            ->label('Prośba o fakturę VAT')
                            ->disabled(),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->disabled()
                            ->visible(fn ($record) => $record?->wants_invoice),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Numer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Klient')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Suma')
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Oczekujące',
                        'paid' => 'Opłacone',
                        'processing' => 'Realizacja',
                        'shipped' => 'Wysłane',
                        'completed' => 'Zakończone',
                        'cancelled' => 'Anulowane',
                        'refunded' => 'Zwrócone',
                        'payment_failed' => 'Błąd płatności',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'success',
                        'processing' => 'info',
                        'shipped' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'danger',
                        'payment_failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Płatność')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Oczekuje',
                        'paid' => 'Opłacone',
                        'completed' => 'Zaksięgowane',
                        'failed' => 'Błąd',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'success',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('wants_invoice')
                    ->label('Faktura')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('')
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'Realizacja',
                        'completed' => 'Zakończone',
                        'cancelled' => 'Anulowane',
                    ]),
                Tables\Filters\TernaryFilter::make('wants_invoice')
                    ->label('Tylko z fakturą VAT'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edytuj'),
                Tables\Actions\Action::make('createShipment')
                    ->label('Nadaj paczkę (BaseLinker)')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Nadaj paczkę przez BaseLinker')
                    ->modalDescription('Czy na pewno chcesz wygenerować list przewozowy dla tego zamówienia?')
                    ->action(function (Order $record, \App\Services\BaseLinkerService $bl) {
                        try {
                            // Map local shipping method string to courier code
                            $method = strtolower($record->shipping_method ?? '');
                            $courierCode = str_contains($method, 'inpost') ? 'inpost' : 'dpd';

                            $result = $bl->createPackage($record, $courierCode);
                            
                            \Filament\Notifications\Notification::make()
                                ->title("Paczka utworzona (ID: {$result['package_id']})")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Błąd tworzenia paczki')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn(Order $record) => $record->baselinker_id !== null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Usuń zaznaczone'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
