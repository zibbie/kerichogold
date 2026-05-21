<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 text-right">
            <x-filament::button type="submit" size="lg" class="px-8">
                Zapisz ustawienia
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
