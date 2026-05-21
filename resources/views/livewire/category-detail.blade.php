@section('seo_title', $category->name . ' - Nevro-Shop')
@section('seo_description', $category->description_short ?? 'Przeglądaj kategorię ' . $category->name . ' w Nevro-Shop.')
<div class="flex flex-col gap-4 md:gap-12 pt-0 md:pt-8 pb-8 min-w-0 overflow-x-hidden">
    <x-mobile-categories :activeCategoryId="$category->id" />

    <div class="container-custom">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex text-charcoal-900/40 font-heading font-semibold text-xs uppercase tracking-widest mb-12">
            <ol class="flex flex-wrap items-center gap-y-2">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-sage-600 transition-colors">Sklep</a>
                </li>
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-sm mx-2">chevron_right</span>
                    <span class="text-charcoal-900">{{ $category->name }}</span>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row items-start gap-8">
            <!-- Sidebar Categories -->
            <aside class="hidden md:block md:w-72 shrink-0">
                <div class="bg-white rounded-3xl p-6 border border-oatmeal-200 shadow-soft md:sticky md:top-24">
                    <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sage-600">category</span>
                        Kategorie
                    </h2>
                    <ul class="space-y-3">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('category.details', $cat->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-xl transition-all {{ $category->id === $cat->id ? 'bg-sage-50 text-sage-600 font-bold' : 'text-charcoal-900/60 hover:bg-oatmeal-50' }}">
                                <span class="text-sm">{{ $cat->name }}</span>
                                <span class="material-symbols-outlined text-xs">chevron_right</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div id="listing-content" class="flex-1 flex flex-col gap-8 min-w-0" wire:loading.class="opacity-50 transition-opacity">
                <!-- Category Header -->
                <div class="flex justify-between items-center mb-4 md:mb-8 px-2 py-0 md:px-8 md:py-8 scroll-mt-24 md:bg-white md:rounded-3xl md:shadow-soft md:border md:border-oatmeal-200">
                    <div>
                        <h1 class="text-xs md:text-2xl lg:text-3xl font-heading font-bold text-charcoal-900/40 md:text-charcoal-900 uppercase md:normal-case tracking-widest md:tracking-normal">{{ $category->name }}</h1>
                        @if($category->description_short)
                        <p class="mt-2 text-xs md:text-sm text-charcoal-900/60 leading-relaxed hidden md:block">
                            {{ $category->description_short }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[10px] font-heading font-bold text-charcoal-900/40 uppercase tracking-widest hidden sm:inline-block">Sortuj</span>
                        <select class="bg-oatmeal-100 border border-oatmeal-200 text-charcoal-900 text-xs rounded-xl px-3 py-1.5 focus:ring-sage-600 focus:border-sage-600 outline-none cursor-pointer">
                            <option>Domyślnie</option>
                            <option>Cena: rosnąco</option>
                            <option>Cena: malejąco</option>
                            <option>Nowości</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 min-w-0">
                    @forelse($this->products as $product)
                        <livewire:product-card :product="$product" :key="'cat-prod-' . $product->id" />
                    @empty
                        <div class="col-span-full py-24 text-center bg-white rounded-3xl border border-dashed border-oatmeal-300">
                            <span class="material-symbols-outlined text-4xl text-oatmeal-300 mb-4">inventory_2</span>
                            <p class="text-charcoal-900/40 font-heading font-bold">Brak produktów w tej kategorii.</p>
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
</div>
