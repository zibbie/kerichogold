<div class="relative w-full" x-data="{ open: false }" @click.away="open = false">
    <div class="relative group flex items-center gap-3 w-full bg-sage-50 md:bg-white border border-sage-100 md:border-oatmeal-200 rounded-full px-4 py-2 focus-within:ring-4 focus-within:ring-sage-600/5 focus-within:border-sage-600/20 transition-all duration-300 shadow-sm md:shadow-none">
        <div class="shrink-0 text-sage-600">
            <span class="material-symbols-outlined text-2xl md:text-xl">search</span>
        </div>
        
        <div class="relative flex-grow">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="query"
                @focus="open = true"
                placeholder="Szukaj produktów..." 
                class="w-full bg-transparent border-none text-charcoal-900 placeholder-charcoal-900/60 focus:outline-none focus:ring-0 p-0 text-base"
                id="global-search-input"
                autofocus
            >
            
            <div wire:loading class="absolute right-0 top-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-sage-600/20 border-t-sage-600"></div>
            </div>
        </div>
        
        <button type="button" class="hidden xl:block bg-sage-600 text-white px-5 py-1.5 rounded-full font-heading font-bold text-xs hover:bg-sage-700 transition-all active:scale-95 cursor-pointer whitespace-nowrap">
            Szukaj
        </button>
    </div>

    @if(strlen($query) >= 2)
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden z-[100]"
        >
            <div class="p-2">
                @forelse($results as $product)
                    <a 
                        href="{{ route('product.details', ['slug' => $product->slug]) }}" 
                        class="flex items-center gap-4 p-3 hover:bg-oatmeal-50 rounded-xl transition-colors duration-200 group"
                    >
                        <div class="w-12 h-12 bg-oatmeal-100 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="text-charcoal-900 font-medium truncate group-hover:text-sage-600 transition-colors duration-200">{{ $product->name }}</h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-sage-600 font-semibold">{{ number_format($product->price, 2) }} zł</span>
                                @if($product->brand)
                                    <span class="text-charcoal-900/10 text-xs">•</span>
                                    <span class="text-charcoal-900/40 text-xs">{{ $product->brand }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <div class="text-charcoal-900/10 mb-2">
                            <span class="material-symbols-outlined text-4xl">sentiment_dissatisfied</span>
                        </div>
                        <p class="text-charcoal-900/40 text-sm">Nie znaleźliśmy produktów pasujących do "{{ $query }}"</p>
                    </div>
                @endforelse
            </div>
            
            @if($results->count() > 0)
                <div class="bg-oatmeal-50 p-3 border-t border-oatmeal-100 text-center">
                    <a href="{{ route('shop', ['search' => $query]) }}" class="text-sage-600 text-sm font-bold hover:text-sage-700 transition-colors">
                        Zobacz wszystkie wyniki
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
