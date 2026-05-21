<div class="py-12">
    <div class="container-custom">
        <header class="mb-12">
            <h1 class="text-4xl font-heading font-bold text-charcoal-900 mb-4">Zamówienie</h1>
            <p class="text-charcoal-900/60 font-sans">Dokończ swoje zakupy wypełniając poniższe dane.</p>
        </header>

        @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-[32px] flex items-start gap-4 shadow-sm animate-pulse">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <div>
                <h3 class="font-heading font-bold text-red-900 text-lg mb-1">Wystąpiły błędy w formularzu</h3>
                <p class="text-red-700 text-sm font-sans mb-3">Proszę poprawić poniższe pola przed złożeniem zamówienia:</p>
                <ul class="list-disc list-inside text-red-600 text-xs space-y-1 font-sans">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form wire:submit.prevent="placeOrder" method="POST" action="#" class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Details -->
            <div class="flex-1 space-y-8">
                
                <!-- Contact Info -->
                <section class="bg-white rounded-[32px] p-8 border border-oatmeal-200 shadow-soft">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-sage-600">contact_mail</span>
                        Dane kontaktowe
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">E-mail</label>
                            <input type="email" id="email" name="email" wire:model="email" inputmode="email" autocomplete="shipping email" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                            @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="phone" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Telefon</label>
                            <input type="tel" id="phone" name="phone" wire:model="phone" inputmode="tel" autocomplete="shipping tel" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                            @error('phone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <!-- Shipping Address -->
                <section class="bg-white rounded-[32px] p-8 border border-oatmeal-200 shadow-soft">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-sage-600">local_shipping</span>
                        Adres dostawy
                    </h2>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="name" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Imię i nazwisko / Firma</label>
                            <input type="text" id="name" name="name" wire:model="name" autocomplete="shipping name" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                            @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="address" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Ulica i numer</label>
                            <input type="text" id="address" name="address" wire:model="address" autocomplete="shipping street-address" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                            @error('address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="zip" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Kod pocztowy</label>
                                <input type="text" id="zip" name="zip" wire:model="zip" inputmode="numeric" autocomplete="shipping postal-code" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                                @error('zip') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="city" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Miasto</label>
                                <input type="text" id="city" name="city" wire:model="city" autocomplete="shipping address-level2" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans">
                                @error('city') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method Selection -->
                    <div class="mt-8 pt-8 border-t border-oatmeal-100">
                        <label class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest block mb-4">Metoda dostawy</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($shipping_methods as $key => $method)
                            <label class="cursor-pointer relative">
                                <input type="radio" wire:model.live="selected_shipping" value="{{ $key }}" class="peer sr-only">
                                <div class="p-4 rounded-2xl border-2 border-oatmeal-200 bg-white peer-checked:border-sage-600 peer-checked:bg-sage-50 transition-all group">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-oatmeal-100 flex items-center justify-center text-sage-600 group-peer-checked:bg-sage-600 group-peer-checked:text-white transition-colors">
                                                <span class="material-symbols-outlined text-sm">{{ $key === 'paczkomat' ? 'package' : 'local_shipping' }}</span>
                                            </div>
                                            <span class="font-heading font-bold text-sm text-charcoal-900">{{ $method['name'] }}</span>
                                        </div>
                                        <span class="font-heading font-bold text-sm text-sage-600">{{ number_format($method['price'], 2, ',', ' ') }} zł</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <div class="mt-4 p-4 rounded-xl bg-sage-50 border border-sage-100 flex items-center gap-3">
                            <span class="material-symbols-outlined text-sage-600 text-sm">info</span>
                            <p class="text-[10px] text-sage-800 font-sans">Produkty pochodzą z różnych magazynów. Twoje zamówienie może zostać wysłane w kilku oddzielnych przesyłkach.</p>
                        </div>

                        <!-- Paczkomat Selection UI -->
                        @if($selected_shipping === 'paczkomat')
                        <div class="mt-6 p-6 rounded-3xl bg-oatmeal-100/50 border border-oatmeal-200">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-sage-600">
                                        <span class="material-symbols-outlined">map</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-charcoal-900/40 uppercase font-bold tracking-widest">Wybrany Paczkomat</p>
                                        <p class="font-heading font-bold text-charcoal-900">
                                            {{ $parcel_locker ?: 'Nie wybrano punktu' }}
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="window.dispatchEvent(new CustomEvent('open-inpost-map'))"
                                        class="bg-white hover:bg-sage-50 text-sage-600 border border-sage-200 px-6 py-3 rounded-xl font-heading font-bold text-sm shadow-sm transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">location_on</span>
                                    Wybierz na mapie
                                </button>
                            </div>
                            @error('parcel_locker') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>
                </section>

                <!-- InPost Script Listener -->
                <script>
                    window.addEventListener('inpost-point-selected', (event) => {
                        @this.setParcelLocker(event.detail);
                    });

                    // Capture GA Client ID
                    document.addEventListener('livewire:initialized', () => {
                        function captureGA() {
                            if (typeof gtag === 'function') {
                                gtag('get', '{{ $google_analytics_id }}', 'client_id', (clientId) => {
                                    if (clientId) {
                                        @this.set('ga_client_id', clientId);
                                    }
                                });
                            } else {
                                // Fallback to cookie extraction if gtag not ready
                                var match = document.cookie.match(/_ga=(?:GA1\.\d\.)?([^;]+)/);
                                if (match) {
                                    @this.set('ga_client_id', match[1]);
                                }
                            }
                        }
                        // Try immediately and after a short delay
                        captureGA();
                        setTimeout(captureGA, 2000);
                    });
                </script>

                <!-- Payment Method -->
                <section class="bg-white rounded-[32px] p-8 border border-oatmeal-200 shadow-soft">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-sage-600">account_balance_wallet</span>
                        Metoda płatności
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" wire:model.live="payment_method" value="BLIK" class="peer sr-only">
                            <div class="p-5 rounded-2xl border-2 border-oatmeal-200 bg-white peer-checked:border-sage-600 peer-checked:bg-sage-50 transition-all flex items-center gap-4">
                                <div class="w-12 h-8 bg-charcoal-900 rounded flex items-center justify-center text-[10px] text-white font-bold">BLIK</div>
                                <span class="font-heading font-bold text-sm text-charcoal-900">BLIK</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" wire:model.live="payment_method" value="P24" class="peer sr-only">
                            <div class="p-5 rounded-2xl border-2 border-oatmeal-200 bg-white peer-checked:border-sage-600 peer-checked:bg-sage-50 transition-all flex items-center gap-4">
                                <div class="w-12 h-8 bg-red-600 rounded flex items-center justify-center text-[8px] text-white font-bold italic">P24</div>
                                <span class="font-heading font-bold text-sm text-charcoal-900">Przelewy24</span>
                            </div>
                        </label>
                        @if(\App\Models\Setting::get('paypo_enabled', true))
                        <label class="cursor-pointer relative">
                            <input type="radio" wire:model.live="payment_method" value="PAYPO" class="peer sr-only">
                            <div class="p-5 rounded-2xl border-2 border-oatmeal-200 bg-white peer-checked:border-sage-600 peer-checked:bg-sage-50 transition-all flex items-center gap-4">
                                <div class="w-12 h-8 bg-white border border-blue-100 rounded flex items-center justify-center p-1">
                                    <img src="https://paypo.pl/assets/img/logo-paypo.svg" class="w-full h-auto">
                                </div>
                                <span class="font-heading font-bold text-sm text-charcoal-900">PayPo (BNPL)</span>
                            </div>
                        </label>
                        @endif
                        <label class="cursor-pointer relative md:col-span-2">
                            <input type="radio" wire:model.live="payment_method" value="COD" class="peer sr-only">
                            <div class="p-5 rounded-2xl border-2 border-oatmeal-200 bg-white peer-checked:border-sage-600 peer-checked:bg-sage-50 transition-all flex items-center gap-4">
                                <div class="w-12 h-8 bg-sage-600 rounded flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-sm">payments</span>
                                </div>
                                <div class="flex-1">
                                    <span class="font-heading font-bold text-sm text-charcoal-900">Za pobraniem</span>
                                    <span class="text-[10px] text-charcoal-900/40 ml-2">(+{{ number_format($cod_fee, 2, ',', ' ') }} zł)</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- Invoice -->
                <section class="bg-white rounded-[32px] p-8 border border-oatmeal-200 shadow-soft">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="wants_invoice" class="peer sr-only">
                            <div class="w-6 h-6 border-2 border-oatmeal-200 rounded-lg group-hover:border-sage-600 transition-colors peer-checked:bg-sage-600 peer-checked:border-sage-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-sm scale-0 peer-checked:scale-100 transition-transform">check</span>
                            </div>
                        </div>
                        <span class="font-heading font-bold text-sm text-charcoal-900">Chcę otrzymać fakturę VAT</span>
                    </label>

                    @if($wants_invoice)
                    <div class="mt-6 space-y-2">
                        <label for="nip" class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">NIP</label>
                        <input type="text" id="nip" name="nip" wire:model.live="nip" class="w-full bg-oatmeal-100 border border-oatmeal-200 rounded-2xl px-5 py-4 focus:ring-sage-600 focus:border-sage-600 outline-none text-charcoal-900 font-sans" placeholder="np. 1234567890">
                        @error('nip') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </section>
            </div>

            <!-- Right Column: Summary -->
            <aside class="w-full lg:w-[400px] shrink-0">
                <div class="bg-white rounded-[32px] p-8 border border-oatmeal-200 sticky top-28 shadow-soft">
                    <h2 class="font-heading font-bold text-2xl text-charcoal-900 mb-8">Twoje zamówienie</h2>
                    
                    <div class="max-h-64 overflow-y-auto mb-8 pr-2 custom-scrollbar">
                        <div class="flex flex-col gap-4">
                            @foreach($cart['items'] as $item)
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-12 rounded-lg bg-oatmeal-100 overflow-hidden shrink-0 flex items-center justify-center p-1">
                                    <img src="{{ $item['product_image'] }}" class="max-w-full max-h-full w-auto h-auto">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-heading font-bold text-charcoal-900 line-clamp-1">{{ $item['product_name'] }}</h4>
                                    <p class="text-[10px] text-charcoal-900/40 uppercase font-bold">{{ $item['quantity'] }} x {{ number_format($item['price'], 2, ',', ' ') }} zł</p>
                                </div>
                                <span class="text-xs font-heading font-bold text-charcoal-900 whitespace-nowrap">{{ number_format($item['total'], 2, ',', ' ') }} zł</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4 pt-8 border-t border-oatmeal-100 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-charcoal-900/60 font-sans text-sm">Wartość produktów</span>
                            <span class="font-heading font-bold text-charcoal-900 text-sm">{{ number_format($cart['subtotal'], 2, ',', ' ') }} zł</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-charcoal-900/60 font-sans text-sm">Dostawa ({{ $shipping_methods[$selected_shipping]['name'] ?? 'Wybierz...' }})</span>
                                @if($payment_method === 'COD')
                                <span class="text-[10px] text-sage-600 font-bold uppercase tracking-tight">Zawiera opłatę pobraniową (+{{ number_format($cod_fee, 2, ',', ' ') }} zł)</span>
                                @endif
                            </div>
                            <span class="font-heading font-bold text-charcoal-900 text-sm">
                                {{ isset($shipping_methods[$selected_shipping]) ? number_format($cart['shipping_total'], 2, ',', ' ') : '0,00' }} zł
                            </span>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-oatmeal-100">
                        <div class="flex flex-col items-end mb-10">
                            <div class="flex justify-between items-center w-full">
                                <span class="font-heading font-bold text-charcoal-900">Razem</span>
                                <span class="font-heading font-bold text-3xl text-sage-600">
                                    {{ number_format($cart['total'], 2, ',', ' ') }} <span class="text-sm">zł</span>
                                </span>
                            </div>
                            <span class="text-[10px] text-charcoal-900/40 font-heading font-bold uppercase tracking-widest mt-1">cena z VAT</span>
                        </div>

                        @if(session()->has('error'))
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-xs mb-6 border border-red-100">
                            {{ session('error') }}
                        </div>
                        @endif

                        <button type="submit" wire:loading.attr="disabled" class="w-full bg-sage-600 hover:bg-sage-700 text-white font-heading font-bold py-5 rounded-2xl flex items-center justify-center gap-3 transition-all shadow-lg hover:shadow-xl active:scale-95 group disabled:opacity-50">
                            <span wire:loading.remove wire:target="placeOrder">
                                @php
                                    $experimentService = app(\App\Services\ExperimentService::class);
                                    $mode = \App\Models\Setting::get('checkout_button_mode', 'standard');
                                    $isAction = ($mode === 'action') || ($mode === 'ab_test' && $experimentService->isVariant('checkout-button-text', 'action'));
                                @endphp
                                
                                @if($isAction)
                                    Odbierz zamówienie
                                @else
                                    Zapłać i zamów
                                @endif
                            </span>
                            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                <span class="animate-spin material-symbols-outlined">progress_activity</span>
                                Przetwarzanie...
                            </span>
                            <span wire:loading.remove wire:target="placeOrder" class="material-symbols-outlined group-hover:translate-x-1 transition-transform">payments</span>
                        </button>
                    </div>

                    <p class="mt-6 text-center text-[10px] text-charcoal-900/40 leading-relaxed font-sans">
                        Klikając "Zapłać i zamów", akceptujesz regulamin sklepu oraz politykę prywatności.
                    </p>

                    @php
                        $experimentService = app(\App\Services\ExperimentService::class);
                        $mode = \App\Models\Setting::get('trust_badges_mode', 'hidden');
                        $showBadges = ($mode === 'visible') || ($mode === 'ab_test' && $experimentService->isVariant('trust-badges-visibility', 'visible'));
                    @endphp

                    @if($showBadges)
                    <div class="mt-8 pt-8 border-t border-oatmeal-100 flex justify-center gap-6">
                        <div class="flex flex-col items-center gap-1 opacity-40 hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sage-600">verified_user</span>
                            <span class="text-[8px] font-heading font-bold uppercase tracking-tight text-charcoal-900">Bezpieczne SSL</span>
                        </div>
                        <div class="flex flex-col items-center gap-1 opacity-40 hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sage-600">assignment_return</span>
                            <span class="text-[8px] font-heading font-bold uppercase tracking-tight text-charcoal-900">30 dni zwrotu</span>
                        </div>
                        <div class="flex flex-col items-center gap-1 opacity-40 hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sage-600">encrypted</span>
                            <span class="text-[8px] font-heading font-bold uppercase tracking-tight text-charcoal-900">Dane chronione</span>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </form>
    </div>
</div>
