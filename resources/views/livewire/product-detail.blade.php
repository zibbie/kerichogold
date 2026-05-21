<div class="py-8">
    @section('seo_description', $product->seo_description)
    @section('canonical', $product->canonical)
    @section('og_type', 'product')
    @section('seo_title', $product->seo_title)
    @section('og_image', $product->main_image_url)
    
    @push('jsonld')
        @php
            $seoService = app(\App\Services\SeoService::class);
        @endphp
        {!! $seoService->renderJsonLd($seoService->productSchema($product)) !!}
        {!! $seoService->renderJsonLd($seoService->breadcrumbSchema([
            ['name' => 'Sklep', 'url' => route('shop')],
            ['name' => $product->category?->name ?? 'Produkty', 'url' => $product->category ? route('category.details', $product->category->slug) : route('shop')],
            ['name' => $product->name],
        ])) !!}
    @endpush
    <div class="container-custom">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex text-charcoal-900/40 font-heading font-semibold text-xs uppercase tracking-widest mb-12">
            <ol class="flex flex-wrap items-center gap-y-2">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-sage-600 transition-colors">Sklep</a>
                </li>
                @if($product->category)
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-sm mx-2">chevron_right</span>
                    <a href="{{ route('category.details', $product->category->slug) }}" class="hover:text-sage-600 transition-colors">{{ $product->category->name }}</a>
                </li>
                @endif
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-sm mx-2">chevron_right</span>
                    <span class="text-charcoal-900">{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">
            <!-- Left: Product Imagery -->
            <div class="flex flex-col gap-6" x-data="{ currentImage: '{{ $product->main_image_url }}' }">
                <!-- Main Image -->
                <div class="w-full max-w-md mx-auto aspect-square rounded-[40px] overflow-hidden bg-white border border-oatmeal-200 shadow-soft relative group flex items-center justify-center p-8">
                    <img :src="currentImage" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="max-w-full max-h-full w-auto h-auto transition-all duration-700">
                    
                    @if($product->is_hit)
                    <div class="absolute top-6 left-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-[10px] font-heading font-bold text-sage-600 bg-white/90 backdrop-blur-md border border-oatmeal-200 shadow-sm uppercase tracking-widest">
                            <span class="material-symbols-outlined text-sm mr-2">stars</span> Produkt Dnia
                        </span>
                    </div>
                    @endif
                </div>
                
                <!-- Thumbnails -->
                @if($product->gallery_urls && count($product->gallery_urls) > 0)
                <div class="flex flex-wrap gap-4">
                    <!-- Main Image Thumbnail -->
                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white cursor-pointer transition-all duration-300 border-2 shadow-sm flex items-center justify-center p-2"
                         :class="currentImage === '{{ $product->main_image_url }}' ? 'border-sage-600 scale-105 shadow-md' : 'border-oatmeal-200 hover:border-sage-400'"
                         @click="currentImage = '{{ $product->main_image_url }}'">
                        <img src="{{ $product->main_image_url }}" class="max-w-full max-h-full w-auto h-auto">
                    </div>
                    
                    <!-- Gallery Images -->
                    @foreach($product->gallery_urls as $imgUrl)
                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white cursor-pointer transition-all duration-300 border-2 shadow-sm flex items-center justify-center p-2"
                             :class="currentImage === '{{ $imgUrl }}' ? 'border-sage-600 scale-105 shadow-md' : 'border-oatmeal-200 hover:border-sage-400'"
                             @click="currentImage = '{{ $imgUrl }}'">
                            <img src="{{ $imgUrl }}" class="max-w-full max-h-full w-auto h-auto">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Product Details -->
            <div class="flex flex-col pt-4 lg:pt-8 relative">
                <div class="absolute -top-1 lg:top-3 right-0 z-20 group/tooltip">
                    <button wire:click="addToCart" wire:loading.attr="disabled" class="text-sage-600 hover:bg-sage-50 p-3 rounded-full transition-all hover:scale-110 active:scale-95 group relative shadow-sm border border-oatmeal-100 bg-white cursor-pointer">
                        <div class="flex items-center gap-0.5">
                            <span class="text-sm font-bold leading-none">+</span>
                            <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform" wire:loading.remove wire:target="addToCart">shopping_cart</span>
                            <span class="animate-spin material-symbols-outlined text-xl" wire:loading wire:target="addToCart">progress_activity</span>
                        </div>
                    </button>
                    <div class="absolute top-full right-0 mt-2 hidden group-hover/tooltip:block bg-sage-600 text-white text-[10px] font-bold py-1 px-2 rounded shadow-lg whitespace-nowrap z-50">
                        Dodaj do koszyka
                    </div>
                </div>

                <div class="mb-4 text-sage-600 font-heading font-bold text-xs uppercase tracking-widest">
                    {{ $product->category?->name ?? 'Nevro-Shop' }}
                </div>
                
                <h1 class="text-4xl md:text-5xl font-heading font-bold text-charcoal-900 mb-6 leading-tight">
                    {{ $product->name }}
                </h1>
                
                <div class="flex flex-col gap-2 mb-8">
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl font-heading font-bold text-sage-600">
                            {{ number_format($product->price, 2, ',', ' ') }} <span class="text-xl ml-1">zł</span>
                        </span>
                    </div>
                    
                    <!-- PayPo Widget -->
                    @if(\App\Models\Setting::get('paypo_enabled', true))
                    <div class="flex items-center gap-3 bg-blue-50/50 border border-blue-100 rounded-2xl p-4 mt-2 max-w-sm">
                        <div class="bg-white p-2 rounded-xl shadow-sm">
                            <img src="https://paypo.pl/assets/img/logo-paypo.svg" alt="PayPo" class="h-4 w-auto">
                        </div>
                        <div class="text-xs text-blue-900 leading-snug">
                            <span class="font-bold">Kup teraz, zapłać za 30 dni</span><br>
                            bez dodatkowych kosztów
                        </div>
                    </div>
                    @endif
                </div>

                <div class="prose prose-sm prose-sage mb-10 text-charcoal-900/70 font-sans leading-relaxed">
                    {!! $product->description !!}
                </div>

                @if($errorMessage)
                    <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm font-sans flex justify-between items-center relative">
                        <span class="pr-6">{{ $errorMessage }}</span>
                        <button wire:click="$set('errorMessage', null)" class="text-red-400 hover:text-red-600 absolute right-4 top-4">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col gap-6 mb-12">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center border border-oatmeal-200 rounded-2xl bg-white h-16 w-40 px-2 shadow-soft">
                            <button wire:click="decrementQuantity" class="w-12 h-12 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600 transition-colors">
                                <span class="material-symbols-outlined text-xl">remove</span>
                            </button>
                            <input type="text" wire:model.live="quantity" class="w-full text-center bg-transparent border-none p-0 focus:ring-0 font-heading font-bold text-charcoal-900" readonly>
                            <button wire:click="incrementQuantity" class="w-12 h-12 flex items-center justify-center text-charcoal-900/40 hover:text-sage-600 transition-colors">
                                <span class="material-symbols-outlined text-xl">add</span>
                            </button>
                        </div>
                        
                        @php
                            $btnColor = app(\App\Services\ExperimentService::class)->isVariant('product-add-to-cart-color', 'terracotta') 
                                ? 'bg-terracotta-600 hover:bg-red-800' 
                                : 'bg-sage-600 hover:bg-sage-700';
                        @endphp
                        
                        <button wire:click="addToCart" wire:loading.attr="disabled" class="flex-1 {{ $btnColor }} text-white rounded-2xl h-16 font-heading font-bold flex items-center justify-center gap-3 transition-all shadow-lg hover:shadow-xl active:scale-95 group">
                            <span wire:loading.remove wire:target="addToCart" class="material-symbols-outlined group-hover:rotate-12 transition-transform">shopping_bag</span>
                            <span wire:loading wire:target="addToCart" class="animate-spin material-symbols-outlined">progress_activity</span>
                            <span class="hidden sm:inline">Dodaj do koszyka</span>
                            <span class="sm:hidden">Dodaj</span>
                        </button>
                    </div>
                </div>

                <!-- Extra Info Accordion-style -->
                <div class="space-y-4 border-t border-oatmeal-200 pt-8">
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-oatmeal-200 overflow-hidden shadow-sm">
                        <button @click="open = !open" class="w-full p-5 flex justify-between items-center hover:bg-oatmeal-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sage-600">local_shipping</span>
                                <span class="font-heading font-bold text-sm text-charcoal-900">Dostawa i zwroty</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" x-collapse class="px-5 pb-5 text-sm text-charcoal-900/60 leading-relaxed font-sans">
                            Czas dostawy: **{{ $product->delivery_time ?? '24h' }}**.  
                            Oferujemy szybką wysyłkę kurierską na terenie całej Polski. Masz 14 dni na zwrot produktu bez podania przyczyny. Produkty wielkogabarytowe wysyłamy na paletach.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-oatmeal-200 overflow-hidden shadow-sm">
                        <button @click="open = !open" class="w-full p-5 flex justify-between items-center hover:bg-oatmeal-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sage-600">shield</span>
                                <span class="font-heading font-bold text-sm text-charcoal-900">Gwarancja jakości</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" x-collapse class="px-5 pb-5 text-sm text-charcoal-900/60 leading-relaxed font-sans">
                            {!! App\Models\Setting::get('quality_guarantee', 'Wszystkie nasze produkty są objęte pełną gwarancją producenta. Dbamy o to, aby każdy element osprzętu spełniał najwyższe normy bezpieczeństwa.') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GA4 / GTM Scripts -->
    <!-- Sticky Mobile Add to Cart -->
    <div x-data="{ 
            visible: false,
            init() {
                window.addEventListener('scroll', () => {
                    this.visible = window.scrollY > 600;
                });
            }
         }"
         x-show="visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="lg:hidden fixed bottom-[72px] left-0 right-0 bg-white border-t border-oatmeal-200 p-4 z-[80] shadow-[0_-10px_30px_rgba(0,0,0,0.05)]"
         x-cloak>
        <div class="flex items-center gap-4">
            <div class="flex-grow">
                <h4 class="text-xs font-bold text-charcoal-900 line-clamp-1">{{ $product->name }}</h4>
                <p class="text-sage-600 font-bold text-sm">{{ number_format($product->price, 2, ',', ' ') }} zł</p>
            </div>
            <button wire:click="addToCart" wire:loading.attr="disabled" class="{{ $btnColor }} text-white px-6 py-3 rounded-xl font-heading font-bold text-sm shadow-md active:scale-95 flex items-center gap-2">
                <span wire:loading.remove wire:target="addToCart" class="material-symbols-outlined text-lg">shopping_cart</span>
                <span wire:loading wire:target="addToCart" class="animate-spin material-symbols-outlined text-lg">progress_activity</span>
                Dodaj
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // view_item event
            gtag('event', 'view_item', {
                currency: 'PLN',
                value: {{ $product->price }},
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ addslashes($product->name) }}',
                    item_brand: 'Nevro',
                    item_category: '{{ addslashes($product->category?->name ?? "") }}',
                    price: {{ $product->price }},
                    quantity: 1
                }]
            });
        });

        // add_to_cart event listener
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('product-added', (params) => {
                const data = Array.isArray(params) ? params[0] : params;
                const qty = data?.quantity || {{ $quantity ?? 1 }};
                const price = data?.price || {{ $product->price }};
                gtag('event', 'add_to_cart', {
                    currency: 'PLN',
                    value: price * qty,
                    items: [{
                        item_id: '{{ $product->id }}',
                        item_name: '{{ addslashes($product->name) }}',
                        item_brand: 'Nevro',
                        item_category: '{{ addslashes($product->category?->name ?? "") }}',
                        price: price,
                        quantity: qty
                    }]
                });
            });
        });
    </script>
    @endpush
</div>