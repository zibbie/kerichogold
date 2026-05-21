<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-filament::section class="md:col-span-2">
            <x-slot name="heading">
                Konfiguracja Feed'u Produktowego
            </x-slot>

            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">
                    Poniższy link należy skopiować i wkleić w panelu <strong>Google Merchant Center</strong> w sekcji <em>Pliki danych (Feeds)</em>.
                </p>

                <div class="flex items-center gap-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <code class="flex-1 text-sm font-mono break-all text-primary-600 dark:text-primary-400">
                        {{ $feedUrl }}
                    </code>
                    <x-filament::button 
                        color="gray" 
                        size="sm"
                        icon="heroicon-o-clipboard"
                        x-on:click="window.navigator.clipboard.writeText('{{ $feedUrl }}'); $tooltip('Skopiowano do schowka', { timeout: 2000 })"
                    >
                        Kopiuj
                    </x-filament::button>
                </div>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 text-blue-700 dark:text-blue-300">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-heroicon-s-information-circle class="h-5 w-5 text-blue-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">
                                Plik jest generowany automatycznie i zawiera wszystkie aktywne produkty, które mają zaznaczoną opcję "Eksportuj do Google Merchant Center".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">Statystyki Eksportu</x-slot>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Wszystkie produkty:</span>
                        <span class="font-bold">{{ $totalProducts }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">W eksporcie (GMC):</span>
                        <span class="font-bold text-success-600">{{ $exportedProducts }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-success-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-center text-gray-500 italic">
                        {{ $percentage }}% Twojej oferty jest widoczne w Google Ads.
                    </p>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Wskazówki SEO</x-slot>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc pl-4">
                    <li>Upewnij się, że produkty mają uzupełnione kody EAN (GTIN).</li>
                    <li>Każdy produkt powinien mieć przypisaną kategorię Google.</li>
                    <li>Zdjęcia główne powinny być na białym tle (wymóg Google).</li>
                </ul>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
