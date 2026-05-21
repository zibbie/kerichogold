<x-layouts.app>
    <div class="flex flex-col gap-12 py-8">
        <!-- Hero Section -->
        <section class="container-custom">
            <div class="bg-sage-600 rounded-3xl overflow-hidden relative min-h-[400px] flex items-center p-8 md:p-16">
                <div class="absolute inset-0 z-0">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgWv9KS-hPHuh5egM4qGzxabvc2h-ZWLigFvYfrWcNrK8XDsnIbSOWz_eO4lt-b_Z5s3lve5lvXFvTbC6qvOhDEnG3yrIPRFW6c5z7vT7Uw56zntVR55YfQNcQIIJOSjSD9OaWf_ugwHkMdVNQX4-wMVbL0s5MYa0V66dTxN2NuqnbwciyGL7CUSm900B6uhFjPb6wMo1vJxTfGvJDwU5kp-8c9Y05RnrycXz65ECe_rupN0xUvGe9S8lDrpOxyt7oyU181v03iH06" 
                         alt="Garden" class="w-full h-full object-cover opacity-30">
                    <div class="absolute inset-0 bg-gradient-to-r from-sage-700/60 to-transparent"></div>
                </div>
                
                <div class="relative z-10 max-w-2xl">
                    <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-6 leading-tight">
                        Prawdziwa kenijska herbata <br> w Kericho Gold
                    </h1>
                    <p class="text-base md:text-lg text-white/80 mb-8 max-w-lg leading-relaxed">
                        Odkryj naszą ofertę najwyższej jakości herbat czarnych, zielonych i ziołowych.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-sage-600 text-white px-6 py-3 rounded-xl font-heading font-bold hover:bg-sage-700 transition-all shadow-md group">
                            Sprawdź ofertę
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container-custom">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Categories -->
                <aside class="w-full md:w-64 shrink-0">
                    <div class="bg-white rounded-3xl p-6 border border-oatmeal-200 shadow-soft sticky top-24">
                        <h2 class="text-xl font-heading font-bold text-charcoal-900 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sage-600">category</span>
                            Kategorie
                        </h2>
                        <nav class="flex flex-col gap-2">
                            @foreach([
                                ['title' => 'Zbiorniki IBC', 'icon' => 'potted_plant', 'url' => '/category/ibc'],
                                ['title' => 'Akcesoria IBC', 'icon' => 'hardware', 'url' => '/category/akcesoria'],
                                ['title' => 'Dom i Ogród', 'icon' => 'home', 'url' => '/category/dom-ogrod'],
                                ['title' => 'Doniczki', 'icon' => 'local_florist', 'url' => '/category/doniczki'],
                                ['title' => 'Nawadnianie', 'icon' => 'water_drop', 'url' => '/category/nawadnianie'],
                                ['title' => 'Narzędzia', 'icon' => 'content_cut', 'url' => '/category/narzedzia']
                            ] as $cat)
                            <a href="{{ $cat['url'] }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-heading font-semibold text-charcoal-900/70 hover:bg-sage-50 hover:text-sage-600 transition-all group">
                                <span class="material-symbols-outlined text-lg group-hover:scale-110 transition-transform">{{ $cat['icon'] }}</span>
                                <span>{{ $cat['title'] }}</span>
                            </a>
                            @endforeach
                        </nav>

                        <div class="mt-8 p-4 bg-sage-600 rounded-2xl text-white">
                            <h3 class="font-heading font-bold text-sm mb-2">Potrzebujesz pomocy?</h3>
                            <p class="text-xs text-white/80 mb-4">Nasi eksperci doradzą Ci w wyborze odpowiedniego zbiornika.</p>
                            <a href="/contact" class="text-xs font-bold underline underline-offset-4">Skontaktuj się</a>
                        </div>
                    </div>
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col gap-12">
                    <!-- Our Hits (Moved to Top) -->
                    <section>
                        <div class="bg-oatmeal-200 rounded-3xl p-6 md:p-8 border border-oatmeal-200">
                            <div class="flex justify-between items-end mb-8">
                                <h2 class="text-2xl font-heading font-bold text-charcoal-900">Nasze Hity</h2>
                                <a href="/catalog" class="text-sm font-heading font-bold text-sage-600 hover:underline underline-offset-4">Wszystkie hity</a>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                <!-- Featured Collection -->
                                <div class="lg:col-span-7 bg-white rounded-2xl overflow-hidden relative min-h-[300px] flex items-center p-8 group shadow-sm border border-oatmeal-200">
                                    <div class="absolute inset-0 z-0">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD03jRSVvX3ftCsD3Wp-y2RgFn1f02UEgZR3vzemTXkhEgQh3WrX2aIbtHxBKZzmo7xPsPd5lCrn_jp5i20B6z7Oc5ZfRHApH98cvSS0RnnKs56V4tFCisCIvco_reA-p_FJaaIbrCHcjTQLh8eTiTxoRh0RQxTkaIiJeWfInNZsPsInstI5jtA7NdA1aqInQXeZAflsK0AR90s695DmLPh4FSNraKMCLH7QTokyE7rJC7hAUTMygPWItt3xCxVuMx49F9rXk8SA6XI" 
                                             alt="Pottery" class="w-full h-full object-cover opacity-10 group-hover:opacity-20 transition-opacity duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>
                                    </div>
                                    <div class="relative z-10 max-w-xs">
                                        <span class="inline-block bg-terracotta-50 text-terracotta-600 font-heading font-bold text-[10px] uppercase tracking-widest px-3 py-1 rounded-full mb-4">Nowość</span>
                                        <h3 class="text-xl font-heading font-bold text-charcoal-900 mb-2">Akcesoria Premium</h3>
                                        <p class="text-sm text-charcoal-900/60 mb-6">Funkcjonalność w wyjątkowym stylu.</p>
                                        <button class="bg-terracotta-600 text-white px-6 py-3 rounded-xl text-sm font-heading font-bold hover:bg-terracotta-700 transition-colors">
                                            Odkryj
                                        </button>
                                    </div>
                                </div>

                                <!-- Side Hits -->
                                <div class="lg:col-span-5 flex flex-col gap-4">
                                    @foreach([
                                        ['title' => 'Konewka Metalowa', 'price' => '42.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAh3fMDErJ4Jnh4s9NBrAwMyct9Z0SN49sVZliNbH95pwzT0OrjGtkUt7WPGBy5t1CWb5F3_m3VcJ0aaGA_n50M3Gf1PDvXTG6iPqd7MyseqnQ-DSr5GoRXcinJgjSU2pVodioV0L3L0S-5jcFU-iEtrvITZa9geiSNzLOkaOOH9uIfxR77h0B96ppV7GTb5l2R-zZaxuYh-SKJc0FddbZIng6kA-lPhhUoB2Y9i1LdPF_ujbFyi6uNtwpndoisBs1vE-yUwYvie9k0'],
                                        ['title' => 'Ziemia Bio 5L', 'price' => '18.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDwDCVi12yYnQi5X8yz0YxqELr0w34TY-nNlWyh8VYtL1LtSb8Mt8WZZ8RhR3BPcBRFkZmI7XpYAY3oaLjTZjg2X8K63mDLpNyZL4bWTQEMzXBSHHe_7Qe5V2-zO2lJsOO3zBS3PqYuL9-Us4V3aqzYslHhcTaud0PUldG2UQVIL6mO1KnAW_QWM_tDtd6SbzQ-y9k0tcHX7LA7Ta1ZYXtA8tPaSAzW31O-WqAgyFN4WffzYsxc8gwUl6ELJw5j8f4mUbrfUGsffBac']
                                    ] as $hit)
                                    <div class="bg-white rounded-2xl p-4 flex gap-4 items-center border border-oatmeal-200 shadow-sm hover:shadow-soft transition-all group">
                                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                                            <img src="{{ $hit['img'] }}" alt="{{ $hit['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-heading font-bold text-charcoal-900 text-sm">{{ $hit['title'] }}</h4>
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="font-heading font-bold text-sage-600 text-sm">{{ $hit['price'] }} zł</span>
                                                <button class="text-sage-600 hover:bg-oatmeal-100 p-1.5 rounded-full transition-colors">
                                                    <span class="material-symbols-outlined text-base">add_circle</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bestsellers Section (Below Hits) -->
                    <section>
                        <div class="flex justify-between items-end mb-8">
                            <h2 class="text-2xl font-heading font-bold text-charcoal-900">Bestsellery</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach([
                                ['title' => 'Zestaw Przyłączeniowy IBC', 'price' => '89.00', 'desc' => 'Kompletny zestaw do zbierania deszczówki', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDgWv9KS-hPHuh5egM4qGzxabvc2h-ZWLigFvYfrWcNrK8XDsnIbSOWz_eO4lt-b_Z5s3lve5lvXFvTbC6qvOhDEnG3yrIPRFW6c5z7vT7Uw56zntVR55YfQNcQIIJOSjSD9OaWf_ugwHkMdVNQX4-wMVbL0s5MYa0V66dTxN2NuqnbwciyGL7CUSm900B6uhFjPb6wMo1vJxTfGvJDwU5kp-8c9Y05RnrycXz65ECe_rupN0xUvGe9S8lDrpOxyt7oyU181v03iH06'],
                                ['title' => 'Zawór IBC 2"', 'price' => '45.00', 'desc' => 'Mocny i szczelny zawór kulowy', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD3WXelL0MUVxrT-bYRfjho0ccUV1ZghKRaxYETD5Www31x2xXhRJTF-BAuum2p9nbxeacMH05D68TTBIHUjVJiZkWCZmNFF90wcvzX029_yplXS3kDnU-p4fAHYDzbdZnP-Q_bwHy3WXJT5UZQrJXApINGlkAAHc_Gvgz8fAwUX283rK5woVUZx11eOy2ikZ4-FCapX-KAL7ZiEfUzNEAaED659d05fze2vkaA_UYPitoSsqHekw9ku1CcBhgL2hCacHesExHm0_27']
                            ] as $best)
                            <div class="bg-white rounded-3xl overflow-hidden border border-oatmeal-200 shadow-soft group">
                                <div class="h-48 overflow-hidden relative">
                                    <img src="{{ $best['img'] }}" alt="{{ $best['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-white/90 backdrop-blur-sm text-sage-600 font-heading font-bold px-3 py-1 rounded-full text-[10px] uppercase tracking-widest shadow-sm">Bestseller</span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="font-heading font-bold text-charcoal-900">{{ $best['title'] }}</h3>
                                    <p class="text-xs text-charcoal-900/60 mt-1 mb-4">{{ $best['desc'] }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-heading font-bold text-sage-600">{{ $best['price'] }} zł</span>
                                        <button class="bg-sage-600 text-white p-2 rounded-xl hover:bg-sage-700 transition-colors">
                                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
