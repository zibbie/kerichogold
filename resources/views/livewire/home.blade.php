<div class="flex flex-col gap-4 md:gap-12 pb-12 overflow-x-hidden min-w-0">
    <x-mobile-categories />
    <!-- Hero Section -->
    @if($hero['visible'])
    <section class="w-full bg-charcoal-900 overflow-hidden relative min-h-[300px] md:min-h-[450px] lg:h-[600px] flex items-center p-6 md:p-16">
        <div class="absolute inset-0 z-0">
            <img src="{{ $hero['image'] }}" 
                 alt="{{ $hero['title'] }}" 
                 fetchpriority="high"
                 width="1920" height="600"
                 class="w-full h-full object-cover">
            {{-- <div class="absolute inset-0 bg-gradient-to-r from-sage-700/60 to-transparent"></div> --}}
        </div>
        
        <div class="container-custom relative z-10">
            <div class="max-w-2xl">
                @php
                    $bgColor = $hero['text_bg']['color'] ?? '#000000';
                    $bgOpacity = ($hero['text_bg']['opacity'] ?? 0) / 100;
                    list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
                    $rgba = "rgba($r, $g, $b, $bgOpacity)";
                @endphp
                <div class="p-6 md:p-10 rounded-3xl backdrop-blur-sm" style="background-color: {{ $rgba }}">
                    <h1 class="text-3xl md:text-6xl font-heading font-bold mb-4 md:mb-6 leading-tight" style="color: {{ $hero['title_color'] }}">
                        {!! nl2br(e($hero['title'])) !!}
                    </h1>
                    <p class="text-sm md:text-xl mb-0 max-w-lg leading-relaxed opacity-90" style="color: {{ $hero['description_color'] }}">
                        {{ $hero['description'] }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-4 mt-6 md:mt-10">
                    <a href="{{ $hero['button_link'] }}" 
                       class="px-8 py-4 rounded-2xl font-heading font-bold transition-all text-sm shadow-lg hover:shadow-xl active:scale-95"
                       style="background-color: {{ $hero['button_bg_color'] }}; color: {{ $hero['button_text_color'] }}">
                        {{ $hero['button_text'] }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="container-custom">
        <div class="flex flex-col md:flex-row items-start gap-8 min-w-0">
            <!-- Sidebar Categories -->
            <aside class="hidden md:block md:w-72 shrink-0 overflow-hidden">
                <div class="bg-white rounded-3xl p-6 border border-oatmeal-200 shadow-soft sticky top-24">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sage-600" aria-hidden="true" data-icon="category"></span>
                        Kategorie
                    </h2>
                    <nav class="flex flex-col gap-2">
                        @foreach($categories as $category)
                        <a href="{{ route('category.details', $category->slug) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-heading font-semibold text-charcoal-900/70 hover:bg-sage-50 hover:text-sage-600 transition-all group">
                            <span class="material-symbols-outlined text-lg group-hover:scale-110 transition-transform" aria-hidden="true" data-icon="{{ $category->icon ?? 'potted_plant' }}"></span>
                            <span>{{ $category->name }}</span>
                        </a>
                        @endforeach
                    </nav>

                    @if($cta['visible'])
                    <div class="mt-8 p-5 rounded-[24px] shadow-lg" style="background-color: {{ $cta['bg_color'] }}; color: {{ $cta['text_color'] }}">
                        <h3 class="font-heading font-bold text-sm mb-2" style="color: {{ $cta['text_color'] }}">{{ $cta['title'] }}</h3>
                        <p class="text-xs mb-4 leading-relaxed opacity-80" style="color: {{ $cta['text_color'] }}">{{ $cta['description'] }}</p>
                        <a href="{{ $cta['button_link'] }}" class="inline-block bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-colors" style="color: {{ $cta['text_color'] }}">
                            {{ $cta['button_text'] }}
                        </a>
                    </div>
                    @endif
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col gap-4 md:gap-12 min-w-0">
                <!-- Our Hits -->
                @if($hits->count() > 0)
                <section class="-mx-4 md:mx-0">
                    <div class="bg-oatmeal-200 md:rounded-[40px] py-4 px-4 md:p-10 border-y md:border border-oatmeal-200">
                        <div class="flex justify-between items-end mb-4 md:mb-8 px-2 md:px-0">
                            <h2 class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Nasze Hity</h2>
                            <a href="{{ route('shop') }}" class="text-xs font-heading font-bold text-sage-600 hover:underline underline-offset-4 uppercase tracking-wider">Wszystkie</a>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            <!-- Featured Hit -->
                            @php $featuredHit = $hits->first(); @endphp
                            <div class="lg:col-span-7 bg-white rounded-3xl overflow-hidden relative min-h-[350px] flex items-center p-8 group shadow-soft border border-oatmeal-200">
                                <div class="absolute inset-0 z-0">
                                    <img src="{{ $featuredHit->main_image_url }}" 
                                         alt="{{ $featuredHit->name }}" 
                                         loading="lazy"
                                         width="800" height="400"
                                         class="max-w-full max-h-full w-auto h-auto m-auto absolute inset-0 opacity-[0.65] group-hover:opacity-[0.85] transition-opacity duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>
                                </div>
                                <div class="relative z-10 max-w-xs">
                                    <span class="inline-block bg-sage-50 text-sage-600 font-heading font-bold text-[10px] uppercase tracking-widest px-3 py-1 rounded-full mb-4">Bestseller</span>
                                    <h3 class="text-2xl font-heading font-bold text-charcoal-900 mb-2">{{ $featuredHit->name }}</h3>
                                    <p class="text-sm text-charcoal-900/60 mb-8 line-clamp-2">{{ $featuredHit->description_short }}</p>
                                    <div class="flex items-center gap-6">
                                        <span class="text-2xl font-heading font-bold text-sage-600">{{ number_format($featuredHit->price, 2, ',', ' ') }} zł</span>
                                        <a href="{{ route('product.details', $featuredHit->slug) }}" class="bg-sage-600 text-white px-8 py-3 rounded-2xl text-sm font-heading font-bold hover:bg-sage-700 transition-all shadow-lg">
                                            Odkryj
                                        </a>
                                    </div>
                                </div>

                                <div class="absolute top-8 right-8 z-10 group/tooltip">
                                    <button wire:click="addToCart({{ $featuredHit->id }})" wire:loading.attr="disabled" class="text-sage-600 bg-white/80 backdrop-blur-sm hover:bg-white p-3 rounded-full transition-all hover:scale-110 active:scale-95 group/cart relative cursor-pointer shadow-sm border border-oatmeal-100">
                                        <div class="flex items-center gap-0.5" wire:loading.remove wire:target="addToCart({{ $featuredHit->id }})">
                                            <span class="text-sm font-bold leading-none">+</span>
                                            <span class="material-symbols-outlined text-xl group-hover/cart:rotate-12 transition-transform">shopping_cart</span>
                                        </div>
                                        <span class="animate-spin material-symbols-outlined text-xl" wire:loading wire:target="addToCart({{ $featuredHit->id }})">progress_activity</span>
                                    </button>
                                    <div class="absolute bottom-full right-0 mb-2 hidden group-hover/tooltip:block bg-sage-600 text-white text-[10px] font-bold py-1 px-2 rounded shadow-lg whitespace-nowrap z-50">
                                        Dodaj do koszyka
                                    </div>
                                </div>
                            </div>

                            <!-- Side Hits -->
                            <div class="lg:col-span-5 flex flex-col gap-4">
                                @foreach($hits->skip(1)->take(3) as $hit)
                                <div class="bg-white rounded-2xl p-4 flex gap-4 items-center border border-oatmeal-200 shadow-sm hover:shadow-soft transition-all group relative">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 relative">
                                        <img src="{{ $hit->main_image_url }}" 
                                             alt="{{ $hit->name }}" 
                                             loading="lazy"
                                             width="80" height="80"
                                             class="max-w-full max-h-full w-auto h-auto m-auto absolute inset-0 group-hover:scale-102 transition-transform duration-500">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-heading font-bold text-charcoal-900 text-sm line-clamp-1 break-words">
                                            <a href="{{ route('product.details', $hit->slug) }}" class="after:absolute after:inset-0">{{ $hit->name }}</a>
                                        </h4>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="font-heading font-bold text-sage-600 text-sm">{{ number_format($hit->price, 2, ',', ' ') }} zł</span>
                                            <div class="relative group/tooltip">
                                                <button wire:click="addToCart({{ $hit->id }})" wire:loading.attr="disabled" class="text-sage-600 hover:bg-sage-50 p-2 rounded-full transition-all hover:scale-110 active:scale-95 group/cart relative z-10 cursor-pointer">
                                                     <div class="flex items-center gap-0.5" wire:loading.remove wire:target="addToCart({{ $hit->id }})">
                                                         <span class="text-sm font-bold leading-none">+</span>
                                                         <span class="material-symbols-outlined text-lg group-hover/cart:rotate-12 transition-transform">shopping_cart</span>
                                                     </div>
                                                     <span class="animate-spin material-symbols-outlined text-lg" wire:loading wire:target="addToCart({{ $hit->id }})">progress_activity</span>
                                                 </button>
                                                 <div class="absolute bottom-full right-0 mb-1 hidden group-hover/tooltip:block bg-sage-600 text-white text-[9px] font-bold py-1 px-2 rounded shadow-lg whitespace-nowrap z-50">
                                                    Dodaj do koszyka
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                @endif

                <!-- Bestsellers Section -->
                <section>
                    <div class="flex justify-between items-end mb-4 md:mb-8">
                        <h2 class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Bestsellery</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6 min-w-0">
                        @foreach($products as $product)
                            <livewire:product-card :product="$product" :key="'home-prod-' . $product->id" />
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>