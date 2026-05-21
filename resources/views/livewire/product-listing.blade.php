<div class="flex flex-col gap-4 md:gap-12 pt-0 md:pt-8 pb-8 min-w-0 overflow-x-hidden" wire:key="product-listing-{{ $mode }}">
    @section('seo_title', $title)
    @section('seo_description', 'Przeglądaj naszą ofertę: ' . $title . '. Najlepszej jakości zbiorniki IBC i akcesoria ogrodowe.')
    
    <x-mobile-categories />

    <div class="container-custom">
        <div class="flex flex-col md:flex-row items-start gap-8 min-w-0">
            <!-- Sidebar Categories -->
            <aside class="hidden md:block w-full md:w-72 shrink-0 overflow-hidden">
                <div class="bg-white rounded-3xl p-6 border border-oatmeal-200 shadow-soft md:sticky md:top-24">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sage-600">category</span>
                        Kategorie
                    </h2>
                    
                    <!-- Desktop Vertical -->
                    <nav class="flex flex-col gap-2">
                        @foreach($categories as $cat)
                        <a href="{{ route('category.details', $cat->slug) }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-heading font-semibold text-charcoal-900/70 hover:bg-sage-50 hover:text-sage-600 transition-all group">
                            <span class="material-symbols-outlined text-lg group-hover:scale-110 transition-transform">
                                {{ $cat->icon ?? 'potted_plant' }}
                            </span>
                            <span>{{ $cat->name }}</span>
                        </a>
                        @endforeach
                    </nav>

                    <div class="mt-8 p-4 bg-sage-600 rounded-2xl text-white">
                        <h3 class="font-heading font-bold text-sm mb-2">Potrzebujesz pomocy?</h3>
                        <p class="text-xs text-white/80 mb-4">Skontaktuj się z nami, jeśli nie widzisz produktu, którego szukasz.</p>
                        <a href="/page/kontakt" class="inline-block bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-colors text-white">
                            Kontakt
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div id="listing-content" class="flex-1 flex flex-col gap-8 min-w-0" wire:loading.class="opacity-50 transition-opacity">
                <!-- Page Header -->
                <div class="bg-white p-6 md:p-8 rounded-3xl shadow-soft border border-oatmeal-200 scroll-mt-24">
                    <h1 class="text-2xl md:text-3xl font-heading font-bold text-charcoal-900">{{ $title }}</h1>
                    <p class="mt-2 text-xs md:text-sm text-charcoal-900/60 leading-relaxed">
                        {{ $mode === 'latest' ? 'Zobacz nasze najnowsze produkty i akcesoria.' : 'Przeglądaj pełną ofertę zbiorników IBC i osprzętu.' }}
                    </p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 min-w-0">
                    @forelse($this->products as $product)
                        <livewire:product-card :product="$product" :key="'listing-prod-' . $product->id" />
                    @empty
                        <div class="col-span-full py-24 text-center bg-white rounded-3xl border border-dashed border-oatmeal-300">
                            <span class="material-symbols-outlined text-4xl text-oatmeal-300 mb-4">inventory_2</span>
                            <p class="text-charcoal-900/40 font-heading font-bold">Obecnie brak produktów do wyświetlenia.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $this->products->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
    <script wire:ignore>
        if (typeof gtag === 'function') {
            gtag('event', 'view_item_list', {
                item_list_id: '{{ $mode }}',
                item_list_name: '{{ $title }}',
                items: [
                    @foreach($this->products as $product)
                    {
                        item_id: '{{ $product->id }}',
                        item_name: '{{ addslashes($product->name) }}',
                        item_brand: 'Nevro',
                        price: {{ $product->price }},
                        index: {{ $loop->index + 1 }}
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ]
            });
        }
    </script>
</div>
