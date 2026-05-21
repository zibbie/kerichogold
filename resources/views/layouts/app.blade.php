<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(request()->getHost() === 'shop.nevro-wm.pl')
    <meta name="robots" content="noindex, nofollow">
    @endif

    <!-- Google Tracking Stack (Consent Mode v2 ABSOLUTE TOP) -->
    @if($google_tag_manager_id || $google_analytics_id || $google_ads_id)
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      
      var storedConsent = localStorage.getItem('cookie_consent');
      var consentDefaults = {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied',
        'functionality_storage': 'granted',
        'personalization_storage': 'denied',
        'security_storage': 'granted',
        'wait_for_update': 500
      };

      if (storedConsent === 'accepted_all') {
        consentDefaults.ad_storage = 'granted';
        consentDefaults.ad_user_data = 'granted';
        consentDefaults.ad_personalization = 'granted';
        consentDefaults.analytics_storage = 'granted';
      } else if (storedConsent && storedConsent !== 'denied_all') {
        try {
          var prefs = JSON.parse(storedConsent);
          if (prefs.stats) consentDefaults.analytics_storage = 'granted';
          if (prefs.marketing) {
            consentDefaults.ad_storage = 'granted';
            consentDefaults.ad_user_data = 'granted';
            consentDefaults.ad_personalization = 'granted';
          }
        } catch(e) {}
      }
      gtag('consent', 'default', consentDefaults);
    </script>
    @endif

    <title>{{ $title ?? 'Nevro-Shop — Zbiorniki IBC i akcesoria ogrodowe' }}</title>
    <meta name="description" content="@yield('seo_description', 'Nevro-Shop — Twój zaufany dostawca rozwiązań dla zbiorników IBC i akcesoriów ogrodowych. Sprawdź naszą ofertę!')">
    <!-- AI Discovery & WebMCP -->
    <link rel="mcp" type="application/json" href="{{ url('/api/mcp') }}">
    <link rel="ai-discovery" type="text/plain" href="{{ url('/ai.txt') }}">
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4a654e">
    <link rel="apple-touch-icon" href="/images/pwa/icon-192x192.png">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <link rel="canonical" href="@yield('canonical', request()->url())">

    <!-- Web Model Context Protocol (WebMCP) Discovery -->
    <meta name="webmcp-endpoint" content="/api/mcp">

    <!-- Open Graph -->
    <meta property="og:site_name" content="Nevro-Shop">
    <meta property="og:locale" content="pl_PL">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('seo_title', config('app.name'))">
    <meta property="og:description" content="@yield('seo_description', 'Nevro-Shop — zbiorniki IBC i akcesoria ogrodowe')">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    @endif
    @hasSection('canonical')
    <meta property="og:url" content="@yield('canonical')">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet"/>

    <!-- GTM & gtag.js Stack -->
    @if($google_tag_manager_id)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $google_tag_manager_id }}');</script>
    @endif

    @if($google_analytics_id || $google_ads_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google_analytics_id ?? $google_ads_id }}"></script>
    <script>
      gtag('js', new Date());
      @if($google_analytics_id) gtag('config', '{{ $google_analytics_id }}'); @endif
      @if($google_ads_id) gtag('config', '{{ $google_ads_id }}'); @endif
      
      window.addEventListener('gtag-event', event => {
          if (typeof gtag === 'function' && event.detail && event.detail.event) {
              gtag('event', event.detail.event, event.detail.data || {});
          }
      });
    </script>
    @endif

    <!-- Global JSON-LD: Organization + WebSite -->
    @php
        $seoService = app(\App\Services\SeoService::class);
    @endphp
    {!! $seoService->renderJsonLd($seoService->organizationSchema()) !!}
    {!! $seoService->renderJsonLd($seoService->webSiteSchema()) !!}
    @stack('jsonld')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- InPost Geowidget v5 -->
    <link rel="stylesheet" href="https://geowidget.inpost.pl/inpost-geowidget.css" />
    <script src="https://geowidget.inpost.pl/inpost-geowidget.js" defer></script>

    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased bg-oatmeal-100 text-charcoal-900" x-data="{ mobileMenuOpen: false }">
    @if(!empty($google_tag_manager_id))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $google_tag_manager_id }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-oatmeal-200 shadow-soft">
        <div class="container-custom">
            <div class="flex justify-between items-center h-20">
                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            aria-label="Menu"
                            class="text-sage-600 p-2 rounded-full hover:bg-oatmeal-100 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined" x-show="!mobileMenuOpen" aria-hidden="true">menu</span>
                        <span class="material-symbols-outlined" x-show="mobileMenuOpen" x-cloak aria-hidden="true">close</span>
                    </button>
                </div>

                <div class="flex items-center flex-shrink-0 mr-8">
                    <a href="/" class="flex items-center">
                        <img src="/images/logo.png" alt="Nevro-Shop" width="180" height="48" class="h-10 md:h-12 w-auto transition-all">
                    </a>
                </div>
                
                <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 font-heading font-semibold text-sm">
                    <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'text-sage-600 border-b-2 border-sage-600 pb-1' : 'text-charcoal-900/60 hover:text-sage-600 transition-colors' }}">Sklep</a>
                    
                    <!-- Categories Dropdown -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 text-charcoal-900/60 hover:text-sage-600 transition-colors py-1 cursor-pointer">
                            Kategorie
                            <span class="material-symbols-outlined text-[16px] transition-transform duration-200" :class="{'rotate-180': open}" aria-hidden="true">expand_more</span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 mt-2 w-64 bg-white border border-oatmeal-200 rounded-2xl shadow-xl py-3 z-50" x-cloak>
                            @foreach($nav_categories as $category)
                                <a href="{{ route('category.details', $category->slug) }}" class="block px-5 py-2.5 text-sm text-charcoal-900/70 hover:bg-oatmeal-50 hover:text-sage-600 transition-colors">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('new-arrivals') }}" class="{{ request()->routeIs('new-arrivals') ? 'text-sage-600 border-b-2 border-sage-600 pb-1' : 'text-charcoal-900/60 hover:text-sage-600 transition-colors' }}">Nowości</a>
                    
                    @foreach($footer_pages->take(2) as $page)
                        <a href="/page/{{ $page->slug }}" class="text-charcoal-900/60 hover:text-sage-600 transition-colors hidden lg:block">{{ $page->title }}</a>
                    @endforeach
                </nav>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <div class="hidden lg:block w-72 xl:w-96">
                        <livewire:global-search />
                    </div>
                    
                    <a href="/cart" @click.prevent="$dispatch('open-cart')" class="text-sage-600 hover:bg-oatmeal-100 p-2 rounded-full transition-colors relative cursor-pointer">
                        <span class="material-symbols-outlined" aria-hidden="true">shopping_cart</span>
                        <livewire:cart-counter />
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-b border-oatmeal-200 shadow-xl"
             x-cloak>
            <nav class="flex flex-col p-6 gap-4 font-heading font-semibold">
                <!-- Prominent Mobile Search -->
                <div class="mb-6 -mx-6 p-6 bg-sage-50 border-y border-sage-100">
                    <livewire:global-search />
                </div>

                <a href="{{ route('shop') }}" @click="mobileMenuOpen = false" class="{{ request()->routeIs('shop') ? 'text-sage-600' : 'text-charcoal-900/60' }} flex items-center gap-3">
                    <span class="material-symbols-outlined" aria-hidden="true">shopping_bag</span>
                    Sklep
                </a>
                @foreach($nav_categories as $category)
                    <div x-data="{ subOpen: false }" class="flex flex-col">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('category.details', $category->slug) }}" @click="mobileMenuOpen = false" class="{{ request()->is('category/'.$category->slug) ? 'text-sage-600' : 'text-charcoal-900/60' }} flex items-center gap-3">
                                <span class="material-symbols-outlined" aria-hidden="true">category</span>
                                {{ $category->name }}
                            </a>
                            @if($category->children->count() > 0)
                                <button @click="subOpen = !subOpen" class="text-charcoal-900/60 p-1 flex items-center justify-center">
                                    <span class="material-symbols-outlined transition-transform duration-200" :class="{'rotate-180': subOpen}" aria-hidden="true">expand_more</span>
                                </button>
                            @endif
                        </div>
                        @if($category->children->count() > 0)
                            <div x-show="subOpen" class="flex flex-col gap-3 mt-3 border-l-2 border-oatmeal-200 ml-3 pl-4" x-cloak>
                                @foreach($category->children as $child)
                                    <a href="{{ route('category.details', $child->slug) }}" @click="mobileMenuOpen = false" class="{{ request()->is('category/'.$child->slug) ? 'text-sage-600' : 'text-charcoal-900/60' }} flex items-center">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                <a href="{{ route('new-arrivals') }}" @click="mobileMenuOpen = false" class="{{ request()->routeIs('new-arrivals') ? 'text-sage-600' : 'text-charcoal-900/80' }} flex items-center gap-3">
                    <span class="material-symbols-outlined" aria-hidden="true">new_releases</span>
                    Nowości
                </a>
                <div class="mt-4 pt-4 border-t border-oatmeal-100 flex flex-col gap-4">
                    <a href="/cart" @click="mobileMenuOpen = false; $dispatch('open-cart')" class="text-charcoal-900/60 flex items-center gap-3">
                        <span class="material-symbols-outlined" aria-hidden="true">shopping_cart</span>
                        Koszyk
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-oatmeal-200 mt-20">
        <div class="container-custom py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-heading font-bold text-sage-600 italic mb-6">Nevro-Shop</h3>
                    <p class="text-charcoal-900/80 max-w-sm leading-relaxed">
                        {!! nl2br(e($footer_description)) !!}
                    </p>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-charcoal-900 mb-6 uppercase tracking-widest text-xs">Informacje</h3>
                    <ul class="space-y-4 text-sm text-charcoal-900/80">
                        @foreach($footer_pages ?? [] as $page)
                            <li><a href="/page/{{ $page->slug }}" class="hover:text-sage-600 transition-colors">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-charcoal-900 mb-6 uppercase tracking-widest text-xs">Kontakt</h3>
                    <div class="space-y-4 text-sm text-charcoal-900/80">
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            {{ $footer_email }}
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">phone</span>
                            {{ $footer_phone }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="border-t border-oatmeal-200 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs uppercase tracking-widest text-charcoal-900/70">
                <p>{{ $footer_copyright }}</p>
                <div class="flex gap-6">
                    <a href="/page/polityka-prywatnosci" class="hover:text-sage-600">Polityka prywatności</a>
                    <a href="/page/regulamin" class="hover:text-sage-600">Regulamin</a>
                </div>
            </div>
        </div>
    </footer>

    @if($cookie_consent_active)
        <x-cookie-consent />
    @endif

    @livewireScripts
    @stack('scripts')
    <livewire:cart />

    <!-- Mobile Bottom Navigation -->
    <style>
        @media (min-width: 1024px) {
            .mobile-bottom-nav { display: none !important; }
        }
    </style>
    <div class="lg:hidden mobile-bottom-nav fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-oatmeal-200 z-[90] px-4 pb-safe-offset-2 pt-2">
        <div class="flex justify-between items-center max-w-md mx-auto">
            <a href="/" class="flex flex-col items-center gap-1 p-2 text-charcoal-900/60 hover:text-sage-600 transition-colors">
                <span class="material-symbols-outlined text-2xl">home</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Główna</span>
            </a>
            <a href="{{ route('shop') }}" class="flex flex-col items-center gap-1 p-2 text-charcoal-900/60 hover:text-sage-600 transition-colors">
                <span class="material-symbols-outlined text-2xl">grid_view</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Sklep</span>
            </a>
            <button @click="mobileMenuOpen = true; $nextTick(() => document.getElementById('global-search-input').focus())" class="flex flex-col items-center gap-1 p-2 text-charcoal-900/60 hover:text-sage-600 transition-colors">
                <span class="material-symbols-outlined text-2xl">search</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Szukaj</span>
            </button>
            <button @click="$dispatch('open-cart')" class="flex flex-col items-center gap-1 p-2 text-charcoal-900/60 hover:text-sage-600 transition-colors relative">
                <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Koszyk</span>
                <livewire:cart-counter />
            </button>
        </div>
    </div>

    <!-- Padding for bottom nav -->
    <div class="h-20 lg:hidden mobile-bottom-nav"></div>

    <!-- Global InPost Geowidget Modal (v5) -->
    <div x-data="{ 
            showMap: false,
            init() {
                window.addEventListener('open-inpost-map', () => {
                    this.showMap = true;
                });
            },
            handlePointSelect(event) {
                const pointName = event.detail.name;
                window.dispatchEvent(new CustomEvent('inpost-point-selected', { detail: pointName }));
                this.showMap = false;
            }
         }"
         class="relative z-[100]">
        
        <div x-show="showMap" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-0 md:p-4 bg-charcoal-900/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div @click.away="showMap = false" 
                 class="bg-white rounded-none md:rounded-[32px] w-full max-w-5xl h-full md:h-[85vh] flex flex-col overflow-hidden shadow-2xl relative">
                
                <div class="p-6 border-b border-oatmeal-100 flex justify-between items-center bg-white relative z-10">
                    <div>
                        <h3 class="font-heading font-bold text-xl text-charcoal-900">Wybierz Paczkomat</h3>
                        <p class="text-xs text-charcoal-900/40 font-sans">Znajdź najwygodniejszy punkt odbioru (v5)</p>
                    </div>
                    <button @click="showMap = false" class="p-2 hover:bg-oatmeal-100 rounded-full transition-colors cursor-pointer group">
                        <span class="material-symbols-outlined text-charcoal-900 group-hover:rotate-90 transition-transform">close</span>
                    </button>
                </div>

                <div class="flex-1 w-full h-full bg-oatmeal-50" wire:ignore>
                    <inpost-geowidget 
                        token="{{ config('services.inpost.geowidget_token') }}" 
                        language="pl" 
                        config="parcelCollect"
                        onpoint="onpointselect"
                        environment="{{ config('services.inpost.geowidget_env') }}"
                        style="height: 100%; width: 100%; display: block;"
                        x-on:onpointselect="handlePointSelect($event)">
                    </inpost-geowidget>
                </div>
            </div>
        </div>
    </div>
</body>
</html>