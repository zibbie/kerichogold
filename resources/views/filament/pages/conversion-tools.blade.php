<x-filament-panels::page>
    <div class="mb-6 p-4 bg-primary-50 border-l-4 border-primary-500 text-primary-700 rounded-r-lg">
        <p class="text-sm">
            <strong>Wskazówka:</strong> Poniższe narzędzia pozwalają na szybkie przełączanie funkcji marketingowych. 
            Jeśli wybierzesz "Test A/B", połowa Twoich klientów zobaczy zmianę, a połowa nie – pozwoli to sprawdzić, co przynosi więcej zamówień.
        </p>
    </div>

    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 text-right">
            <x-filament::button type="submit" size="lg" class="px-8 shadow-md hover:shadow-lg transition-all">
                Zastosuj i zapisz zmiany
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
