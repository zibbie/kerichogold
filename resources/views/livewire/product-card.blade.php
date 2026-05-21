<article class="bg-white border border-oatmeal-200 rounded-[24px] overflow-hidden flex flex-col shadow-soft hover:shadow-lg transition-all duration-300 group h-full relative max-w-[450px] mx-auto w-full">
    <div class="relative aspect-square w-full overflow-hidden bg-oatmeal-100 flex items-center justify-center">
        <a href="{{ route('product.details', $product->slug) }}" class="relative block w-full h-full flex items-center justify-center p-4">
            <img src="{{ $product->main_image_url }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy"
                 width="500" height="500"
                 class="max-w-full max-h-full w-auto h-auto transition-transform duration-700 group-hover:scale-102">
        </a>
        
        @if($product->is_hit)
        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full font-heading font-bold text-[10px] text-sage-600 uppercase tracking-widest shadow-sm z-10">
            Hit
        </div>
        @endif

        <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0 z-30 group/tooltip">
            <button wire:click.stop="addToCart" wire:loading.attr="disabled" class="text-sage-600 bg-white/90 backdrop-blur-sm hover:bg-sage-50 p-3 rounded-full shadow-md transition-all hover:scale-110 active:scale-95 cursor-pointer pointer-events-auto group/btn">
                <div wire:loading.remove wire:target="addToCart" class="flex items-center gap-0.5">
                    <span class="text-lg font-bold leading-none">+</span>
                    <span class="material-symbols-outlined text-xl group-hover/btn:rotate-12 transition-transform">shopping_cart</span>
                </div>
                <span wire:loading wire:target="addToCart" class="animate-spin material-symbols-outlined text-xl">progress_activity</span>
            </button>
            <div class="absolute bottom-full right-0 mb-2 hidden group-hover/tooltip:block bg-sage-600 text-white text-[10px] font-bold py-1 px-2 rounded shadow-lg whitespace-nowrap z-50">
                Dodaj do koszyka
            </div>
        </div>
    </div>

    <div class="p-5 flex flex-col flex-grow">
        <div class="mb-2">
            <a href="{{ route('product.details', $product->slug) }}" class="font-heading font-bold text-charcoal-900 hover:text-sage-600 transition-colors text-xs md:text-base line-clamp-2 break-words after:absolute after:inset-0 after:z-20">
                {{ $product->name }}
            </a>
            @if($product->category)
            <p class="text-[10px] text-charcoal-900/40 uppercase tracking-widest font-heading font-semibold">{{ $product->category->name }}</p>
            @endif
        </div>
        
        <p class="font-sans text-sm text-charcoal-900/60 mb-4 flex-grow line-clamp-2">
            {{ $product->description_short }}
        </p>
        
        <div class="flex justify-between items-center mt-auto pt-4 border-t border-oatmeal-100">
            <div class="flex flex-col">
                <span class="font-heading font-bold text-xl text-sage-600">
                    {{ number_format($product->price, 2, ',', ' ') }} <span class="text-xs ml-0.5">zł</span>
                </span>
                @if(\App\Models\Setting::get('paypo_enabled', true))
                <div class="flex items-center gap-1 mt-0.5">
                    <img src="https://paypo.pl/assets/img/logo-paypo.svg" alt="PayPo" class="h-2 w-auto opacity-70">
                    <span class="text-[9px] text-charcoal-900/40 font-bold uppercase">odrocz płatność</span>
                </div>
                @endif
            </div>
            <span class="material-symbols-outlined text-xl text-charcoal-900/40 group-hover:text-sage-600 transition-colors">arrow_forward</span>
        </div>
    </div>
</article>