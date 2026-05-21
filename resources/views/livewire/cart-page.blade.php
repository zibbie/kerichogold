<div class="py-12">
    <div class="container-custom">
        <header class="mb-12">
            <h1 class="text-4xl font-heading font-bold text-charcoal-900 mb-4">Twój Koszyk</h1>
            <p class="text-charcoal-900/60 font-sans">
                @if(count($cart['items']) > 0)
                    Masz {{ count($cart['items']) }} {{ count($cart['items']) == 1 ? 'produkt' : (in_array(count($cart['items']) % 10, [2,3,4]) && !in_array(count($cart['items']) % 100, [12,13,14]) ? 'produkty' : 'produktów') }} w koszyku.
                @else
                    Twój koszyk jest obecnie pusty.
                @endif
            </p>
        </header>

        @if(count($cart['items']) > 0)
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Bag Items -->
            <div class="flex-1 flex flex-col gap-6">
                @if($errorMessage)
                    <div class="p-4 bg-red-50 text-red-600 rounded-3xl border border-red-100 text-sm font-sans flex justify-between items-center relative shadow-soft">
                        <span class="pr-6">{{ $errorMessage }}</span>
                        <button wire:click="$set('errorMessage', null)" class="text-red-400 hover:text-red-600 absolute right-4 top-4">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                @endif

                @foreach($cart['items'] as $item)
                <article class="flex flex-col sm:flex-row items-start sm:items-center gap-8 p-6 bg-white rounded-3xl border border-oatmeal-200 shadow-soft relative group transition-all hover:shadow-lg">
                    <div class="shrink-0 w-full sm:w-32 h-40 sm:h-32 rounded-2xl overflow-hidden bg-oatmeal-100 border border-oatmeal-200 flex items-center justify-center p-4">
                        <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="max-w-full max-h-full w-auto h-auto">
                    </div>
                    
                    <div class="flex-1 flex flex-col gap-1 w-full">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('product.details', $item['product_slug']) }}" class="font-heading font-bold text-xl text-charcoal-900 hover:text-sage-600 transition-colors">
                                    {{ $item['product_name'] }}
                                </a>
                                <p class="text-xs text-charcoal-900/40 uppercase tracking-widest font-heading font-semibold mt-1">SKU: {{ $item['product_sku'] }}</p>
                            </div>
                            <button wire:click="removeItem({{ $item['id'] }})" class="text-charcoal-900/30 hover:text-red-500 transition-colors p-2 -mr-2 -mt-2">
                                <span class="material-symbols-outlined text-xl">close</span>
                            </button>
                        </div>

                        <div class="flex justify-between items-end mt-6">
                            <div class="flex items-center bg-oatmeal-100 rounded-2xl p-1 border border-oatmeal-200 shadow-inner">
                                <button wire:click="decrementQuantity({{ $item['id'] }})" class="w-10 h-10 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600 transition-colors">
                                    <span class="material-symbols-outlined text-lg">remove</span>
                                </button>
                                <span class="font-heading font-bold text-sm w-10 text-center text-charcoal-900">{{ $item['quantity'] }}</span>
                                <button wire:click="incrementQuantity({{ $item['id'] }})" class="w-10 h-10 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600 transition-colors">
                                    <span class="material-symbols-outlined text-lg">add</span>
                                </button>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-charcoal-900/40 font-heading font-bold uppercase tracking-widest mb-1">Suma</p>
                                <span class="font-heading font-bold text-2xl text-sage-600">
                                    {{ number_format($item['total'], 2, ',', ' ') }} <span class="text-sm ml-0.5">zł</span>
                                </span>
                                <p class="text-[10px] text-charcoal-900/40 font-heading font-medium">cena zawiera VAT</p>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach

                <div class="mt-8 flex justify-between items-center px-4">
                    <a href="/" class="flex items-center gap-2 text-charcoal-900/60 hover:text-sage-600 transition-colors font-heading font-bold text-sm">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Kontynuuj zakupy
                    </a>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <aside class="w-full lg:w-[400px] shrink-0">
                <div class="bg-white rounded-[32px] p-8 border border-oatmeal-200 sticky top-28 shadow-soft">
                    <h2 class="font-heading font-bold text-2xl text-charcoal-900 mb-8">Podsumowanie</h2>
                    
                    <div class="flex flex-col gap-6 mb-8">
                            <span class="text-charcoal-900/60 font-sans">Suma częściowa</span>
                            <div class="text-right">
                                <span class="font-heading font-bold text-charcoal-900 block">{{ number_format($cart['subtotal'], 2, ',', ' ') }} zł</span>
                                <span class="text-[10px] text-charcoal-900/40 font-heading font-medium">cena zawiera VAT</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-charcoal-900/60 font-sans">Dostawa</span>
                            <span class="text-charcoal-900/40 text-xs uppercase tracking-widest font-heading font-bold">Obliczana dalej</span>
                        </div>
                    </div>

                    <div class="border-t border-oatmeal-100 my-8 pt-8">
                        <div class="flex justify-between items-center mb-10">
                            <span class="font-heading font-bold text-charcoal-900">Łącznie</span>
                            <div class="text-right">
                                <span class="font-heading font-bold text-4xl text-sage-600 block">{{ number_format($cart['total'], 2, ',', ' ') }} <span class="text-lg">zł</span></span>
                                <span class="text-[10px] text-charcoal-900/40 font-heading font-medium">cena zawiera VAT</span>
                            </div>
                        </div>

                        <a href="/checkout" class="w-full bg-sage-600 hover:bg-sage-700 text-white font-heading font-bold py-5 rounded-2xl flex items-center justify-center gap-3 transition-all shadow-lg hover:shadow-xl active:scale-95 group">
                            Przejdź do zamówienia
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </div>

                    <!-- Reassurance Badges -->
                    <div class="space-y-6 pt-8 border-t border-oatmeal-100 mt-8">
                        <div class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-sage-600">local_shipping</span>
                            <div>
                                <h4 class="font-heading font-bold text-sm text-charcoal-900">Szybka dostawa</h4>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-sage-600">verified_user</span>
                            <div>
                                <h4 class="font-heading font-bold text-sm text-charcoal-900">Bezpieczne płatności</h4>
                                <p class="text-xs text-charcoal-900/60 mt-1">Twoje dane są w pełni bezpieczne i szyfrowane.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        @else
        <div class="py-24 text-center bg-white rounded-[40px] border border-dashed border-oatmeal-300 shadow-soft">
            <div class="w-24 h-24 bg-oatmeal-100 rounded-full flex items-center justify-center mx-auto mb-8">
                <span class="material-symbols-outlined text-5xl text-oatmeal-300">shopping_basket</span>
            </div>
            <h2 class="text-2xl font-heading font-bold text-charcoal-900 mb-4">Twój koszyk świeci pustkami</h2>
            <p class="text-charcoal-900/60 max-w-sm mx-auto mb-10 font-sans">
                Wygląda na to, że nie masz jeszcze nic w koszyku. Odkryj naszą ofertę i znajdź coś dla siebie!
            </p>
            <a href="/" class="bg-sage-600 text-white px-10 py-4 rounded-2xl font-heading font-bold hover:bg-sage-700 transition-all shadow-lg inline-block">
                Wróć do sklepu
            </a>
        </div>
        @endif
    </div>
</div>
