<div x-data="{ open: @entangle('isOpen') }" 
     x-show="open" 
     @keydown.escape.window="open = false" 
     class="fixed inset-0 z-[100] overflow-hidden" 
     style="display: none;">
    
    <div class="absolute inset-0 overflow-hidden">
        <!-- Backdrop -->
        <div @click="open = false" 
             x-show="open"
             x-transition:enter="ease-in-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-charcoal-900/40 backdrop-blur-sm transition-opacity"></div>
        
    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div x-show="open" 
                 x-data="{ 
                    touchStartX: 0,
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        const touchEndX = e.changedTouches[0].screenX;
                        if (touchEndX - this.touchStartX > 50) {
                            open = false;
                        }
                    }
                 }"
                 @touchstart="handleTouchStart($event)"
                 @touchend="handleTouchEnd($event)"
                 x-transition:enter="transform transition ease-in-out duration-500" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transform transition ease-in-out duration-500" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="w-full max-w-md">
                
                <div class="h-full flex flex-col bg-white shadow-2xl rounded-l-[40px] border-l border-oatmeal-200">
                    <!-- Header -->
                    <div class="px-8 py-8 flex items-center justify-between border-b border-oatmeal-100">
                        <h2 class="text-2xl font-heading font-bold text-charcoal-900 flex items-center gap-3">
                            <span class="material-symbols-outlined text-sage-600">shopping_bag</span>
                            Twój Koszyk
                        </h2>
                        <button @click="open = false" class="p-2 text-charcoal-900/30 hover:text-charcoal-900 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="flex-1 py-6 overflow-y-auto px-8 custom-scrollbar">
                        @if($errorMessage)
                            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm font-sans flex justify-between items-center relative">
                                <span class="pr-6">{{ $errorMessage }}</span>
                                <button wire:click="$set('errorMessage', null)" class="text-red-400 hover:text-red-600 absolute right-4 top-4">
                                    <span class="material-symbols-outlined text-base">close</span>
                                </button>
                            </div>
                        @endif

                        @php
                            $experimentService = app(\App\Services\ExperimentService::class);
                            $mode = \App\Models\Setting::get('free_shipping_mode', 'hidden');
                            $showBar = ($mode === 'visible') || ($mode === 'ab_test' && $experimentService->isVariant('cart-free-shipping-bar', 'bar'));
                            
                            $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 300);
                            $currentTotal = $cart['total'];
                            $progress = min(100, ($currentTotal / $freeShippingThreshold) * 100);
                            $remaining = max(0, $freeShippingThreshold - $currentTotal);
                        @endphp

                        @if($showBar)
                            <div class="mb-8 p-4 bg-sage-50 rounded-2xl border border-sage-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[10px] font-heading font-bold text-sage-600 uppercase tracking-widest">
                                        @if($remaining > 0)
                                            Brakuje Ci {{ number_format($remaining, 2, ',', ' ') }} zł do darmowej dostawy
                                        @else
                                            Gratulacje! Masz darmową dostawę!
                                        @endif
                                    </span>
                                    <span class="material-symbols-outlined text-sage-600 text-sm">local_shipping</span>
                                </div>
                                <div class="w-full h-2 bg-oatmeal-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-sage-600 transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flow-root">
                            <ul role="list" class="-my-6 divide-y divide-oatmeal-100">
                                @forelse($cart['items'] as $item)
                                    <li class="py-6 flex gap-6 group">
                                        <div class="shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-oatmeal-100 border border-oatmeal-200">
                                            <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                        </div>

                                        <div class="flex-1 flex flex-col">
                                            <div class="flex justify-between items-start">
                                                <h3 class="font-heading font-bold text-sm text-charcoal-900 group-hover:text-sage-600 transition-colors">
                                                    <a href="{{ route('product.details', $item['product_slug']) }}">{{ $item['product_name'] }}</a>
                                                </h3>
                                                <div class="text-right">
                                                    <p class="font-heading font-bold text-sm text-sage-600 whitespace-nowrap">{{ number_format($item['total'], 2, ',', ' ') }} zł</p>
                                                    <p class="text-[9px] text-charcoal-900/40 font-heading font-medium leading-none mt-0.5">z VAT</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 flex items-end justify-between mt-4">
                                                <div class="flex items-center bg-oatmeal-100 rounded-xl p-0.5 border border-oatmeal-200">
                                                    <button wire:click="decrementQuantity({{ $item['id'] }})" class="w-8 h-8 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600">
                                                        <span class="material-symbols-outlined text-sm">remove</span>
                                                    </button>
                                                    <span class="font-heading font-bold text-xs w-6 text-center text-charcoal-900">{{ $item['quantity'] }}</span>
                                                    <button wire:click="incrementQuantity({{ $item['id'] }})" class="w-8 h-8 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600">
                                                        <span class="material-symbols-outlined text-sm">add</span>
                                                    </button>
                                                </div>

                                                <button wire:click="removeItem({{ $item['id'] }})" type="button" class="text-xs font-heading font-bold text-charcoal-900/30 hover:text-red-500 transition-colors uppercase tracking-widest">
                                                    Usuń
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="py-20 text-center">
                                        <span class="material-symbols-outlined text-4xl text-oatmeal-300 mb-4">shopping_basket</span>
                                        <p class="text-charcoal-900/40 font-heading font-bold text-sm">Koszyk jest pusty</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    @if(count($cart['items']) > 0)
                    <div class="border-t border-oatmeal-100 px-8 py-10 bg-oatmeal-50 rounded-bl-[40px]">
                        <div class="flex justify-between items-center mb-8">
                            <span class="font-heading font-bold text-charcoal-900">Łącznie:</span>
                            <div class="text-right">
                                <span class="text-3xl font-heading font-bold text-sage-600 block leading-none">{{ number_format($cart['total'], 2, ',', ' ') }} <span class="text-sm">zł</span></span>
                                <span class="text-[10px] text-charcoal-900/40 font-heading font-medium mt-1 inline-block">cena zawiera VAT</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="/cart" class="w-full bg-white border border-oatmeal-200 text-charcoal-900 font-heading font-bold py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-oatmeal-100 transition-all shadow-sm">
                                Zobacz koszyk
                            </a>
                            <a href="/checkout" class="w-full bg-sage-600 text-white font-heading font-bold py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-sage-700 transition-all shadow-lg active:scale-95 group">
                                Zamawiam
                                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                        
                        <p class="mt-6 text-center text-[10px] text-charcoal-900/40 font-heading font-bold uppercase tracking-widest">
                            Dostawa obliczana w kolejnym kroku
                        </p>
                    </div>
                    @else
                    <div class="px-8 py-10 border-t border-oatmeal-100">
                        <button @click="open = false" class="w-full bg-sage-600 text-white font-heading font-bold py-4 rounded-2xl flex items-center justify-center gap-2">
                            Zacznij zakupy
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
