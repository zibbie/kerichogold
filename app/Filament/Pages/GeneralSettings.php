<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class GeneralSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Ustawienia';
    protected static ?string $navigationLabel = 'Ustawienia Sklepu';
    protected static ?string $title = 'Ustawienia Ogólne';
    protected static string $view = 'filament.pages.general-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'cod_fee' => Setting::get('cod_fee', 10.00),
            'admin_emails' => Setting::get('admin_emails', 'kontakt@kerichogold.pl'),
            'paypo_enabled' => Setting::get('paypo_enabled', true),
        ];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ustawienia Płatności i Dostawy')
                    ->schema([
                        TextInput::make('cod_fee')
                            ->label('Opłata za pobraniem (PLN)')
                            ->numeric()
                            ->step(0.01)
                            ->required(),
                        Toggle::make('paypo_enabled')
                            ->label('Włącz płatności PayPo (Kup teraz, zapłać za 30 dni)')
                            ->helperText('Jeśli wyłączone, opcja ta nie będzie widoczna w koszyku.'),
                    ]),
                Section::make('Powiadomienia E-mail')
                    ->schema([
                        TextInput::make('admin_emails')
                            ->label('Adresy e-mail administratorów')
                            ->helperText('Oddzielaj adresy przecinkami (np. kontakt@kerichogold.pl, magdalena@kerichogold.pl)')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('cod_fee', $data['cod_fee']);
        Setting::set('admin_emails', $data['admin_emails']);
        Setting::set('paypo_enabled', (bool) $data['paypo_enabled']);

        Notification::make()
            ->title('Ustawienia zostały zapisane')
            ->success()
            ->send();
    }
}
