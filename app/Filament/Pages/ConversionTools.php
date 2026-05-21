<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\Experiment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ConversionTools extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Marketing i SEO';
    protected static ?string $navigationLabel = 'Narzędzia Konwersji';
    protected static ?string $title = 'Konfiguracja Mechanizmów Konwersji';
    protected static string $view = 'filament.pages.conversion-tools';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'checkout_button_mode' => Setting::get('checkout_button_mode', 'standard'),
            'free_shipping_mode' => Setting::get('free_shipping_mode', 'hidden'),
            'free_shipping_threshold' => Setting::get('free_shipping_threshold', 300),
            'trust_badges_mode' => Setting::get('trust_badges_mode', 'hidden'),
        ];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('1. Przycisk Finalizacji Zamówienia')
                    ->description('Zarządzaj tekstem na głównym przycisku w koszyku.')
                    ->schema([
                        Select::make('checkout_button_mode')
                            ->label('Tryb przycisku')
                            ->options([
                                'standard' => 'Standardowy ("Zapłać i zamów")',
                                'action' => 'Motywujący ("Odbierz zamówienie")',
                                'ab_test' => 'Test A/B (Połowa widzi standard, połowa motywujący)',
                            ])
                            ->required(),
                    ]),

                Section::make('2. Darmowa Dostawa (Licznik)')
                    ->description('Pasek postępu informujący ile brakuje do darmowej dostawy.')
                    ->schema([
                        Select::make('free_shipping_mode')
                            ->label('Widoczność paska')
                            ->options([
                                'hidden' => 'Ukryty',
                                'visible' => 'Zawsze widoczny',
                                'ab_test' => 'Test A/B (Tylko połowa widzi pasek)',
                            ])
                            ->required(),
                        TextInput::make('free_shipping_threshold')
                            ->label('Próg darmowej dostawy (PLN)')
                            ->numeric()
                            ->default(300)
                            ->required(),
                    ])->columns(2),

                Section::make('3. Odznaki Zaufania (Trust Badges)')
                    ->description('Ikony bezpieczeństwa i gwarancji (SSL, 30 dni na zwrot).')
                    ->schema([
                        Select::make('trust_badges_mode')
                            ->label('Widoczność odznak')
                            ->options([
                                'hidden' => 'Ukryte',
                                'visible' => 'Widoczne w koszyku i podsumowaniu',
                                'ab_test' => 'Test A/B (Połowa widzi odznaki)',
                            ])
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Sync with A/B Experiment models for consistent analytics
        $this->syncExperiments($data);

        Notification::make()
            ->title('Ustawienia konwersji zostały zapisane')
            ->success()
            ->send();
    }

    protected function syncExperiments(array $data)
    {
        $experiments = [
            'checkout-button-text' => $data['checkout_button_mode'],
            'cart-free-shipping-bar' => $data['free_shipping_mode'],
            'trust-badges-visibility' => $data['trust_badges_mode'],
        ];

        foreach ($experiments as $slug => $mode) {
            $experiment = Experiment::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => "Eksperyment: " . ucwords(str_replace('-', ' ', $slug)),
                    'is_active' => $mode === 'ab_test',
                ]
            );

            if ($experiment->wasRecentlyCreated || $experiment->variants()->count() === 0) {
                $experiment->variants()->createMany([
                    ['name' => 'Kontrola', 'key' => 'control', 'weight' => 50],
                    ['name' => 'Test', 'key' => $this->getTestKey($slug), 'weight' => 50],
                ]);
            }
        }
    }

    protected function getTestKey($slug)
    {
        return match($slug) {
            'checkout-button-text' => 'action',
            'cart-free-shipping-bar' => 'bar',
            'trust-badges-visibility' => 'visible',
            default => 'test'
        };
    }
}
