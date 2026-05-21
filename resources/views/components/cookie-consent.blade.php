<div 
    x-data="{ 
        show: !localStorage.getItem('cookie_consent'),
        settings: false,
        stats: true,
        marketing: true,
        acceptAll() {
            localStorage.setItem('cookie_consent', 'accepted_all');
            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'ad_storage': 'granted',
                    'ad_user_data': 'granted',
                    'ad_personalization': 'granted',
                    'analytics_storage': 'granted',
                });
            }
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({'event': 'cookie_consent_updated'});
            this.show = false;
        },
        denyAll() {
            localStorage.setItem('cookie_consent', 'denied_all');
            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'ad_storage': 'denied',
                    'ad_user_data': 'denied',
                    'ad_personalization': 'denied',
                    'analytics_storage': 'denied',
                });
            }
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({'event': 'cookie_consent_updated'});
            this.show = false;
        },
        saveSettings() {
            const preferences = {
                functional: true,
                stats: this.stats,
                marketing: this.marketing
            };
            localStorage.setItem('cookie_consent', JSON.stringify(preferences));
            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'analytics_storage': this.stats ? 'granted' : 'denied',
                    'ad_storage': this.marketing ? 'granted' : 'denied',
                    'ad_user_data': this.marketing ? 'granted' : 'denied',
                    'ad_personalization': this.marketing ? 'granted' : 'denied',
                });
            }
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({'event': 'cookie_consent_updated'});
            this.show = false;
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-12"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed bottom-0 left-0 w-full z-[9999] p-4 md:p-8"
    x-cloak
>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl border border-oatmeal-200 p-6 md:p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden text-charcoal-900" style="background-color: rgba(247, 250, 245, 0.96);">
            
            {{-- Accent Line (Sage) --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-sage-600"></div>

            {{-- Main View --}}
            <div x-show="!settings" class="flex flex-col lg:flex-row items-center gap-8">
                <div class="flex-1">
                    <h3 class="text-xl md:text-2xl font-heading font-bold mb-4 text-charcoal-900">
                        {{ $cookie_consent_title }}
                    </h3>
                    <p class="text-charcoal-900/60 text-sm leading-relaxed max-w-4xl">
                        {{ $cookie_consent_description }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 shrink-0">
                    <button @click="acceptAll()" class="bg-sage-600 hover:bg-sage-700 text-white px-8 py-4 rounded-full font-heading font-bold text-sm transition-all active:scale-95 cursor-pointer">
                        Akceptuj wszystko
                    </button>
                    <button @click="denyAll()" class="bg-transparent hover:bg-sage-50 text-sage-600 border border-sage-600/30 px-8 py-4 rounded-full font-heading font-bold text-sm transition-all active:scale-95 cursor-pointer">
                        Odrzuć opcjonalne
                    </button>

                    <div class="flex flex-col gap-2 ml-2">
                        <button @click="settings = true" class="text-[10px] uppercase tracking-widest text-charcoal-900/40 hover:text-charcoal-900 transition-colors text-left cursor-pointer font-bold">
                            Ustawienia
                        </button>
                        <a href="{{ $cookie_consent_policy_url }}" class="text-[10px] uppercase tracking-widest text-charcoal-900/40 hover:text-charcoal-900 transition-colors font-bold">
                            Polityka Cookies
                        </a>
                    </div>
                </div>
            </div>

            {{-- Settings View --}}
            <div x-show="settings" style="display: none;">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl md:text-2xl font-heading font-bold text-charcoal-900">
                        Ustawienia plików cookie
                    </h3>
                    <button @click="settings = false" class="text-charcoal-900/40 hover:text-charcoal-900 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    {{-- Functional --}}
                    <div class="bg-charcoal-900/5 p-6 rounded-3xl border border-charcoal-900/5">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-heading font-bold uppercase text-xs tracking-widest text-charcoal-900">Niezbędne</span>
                            <span class="text-sage-600 text-[10px] font-bold uppercase">Zawsze aktywne</span>
                        </div>
                        <p class="text-xs text-charcoal-900/50 leading-relaxed">
                            Pliki te są konieczne do działania strony, np. do logowania czy obsługi koszyka.
                        </p>
                    </div>

                    {{-- Statistics --}}
                    <div class="bg-charcoal-900/5 p-6 rounded-3xl border border-charcoal-900/5">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-heading font-bold uppercase text-xs tracking-widest text-charcoal-900">Analityka</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="stats" class="sr-only peer">
                                <div class="w-11 h-6 bg-charcoal-900/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sage-600"></div>
                            </label>
                        </div>
                        <p class="text-xs text-charcoal-900/50 leading-relaxed">
                            Pozwalają nam mierzyć ruch na stronie i ulepszać nasze usługi.
                        </p>
                    </div>

                    {{-- Marketing --}}
                    <div class="bg-charcoal-900/5 p-6 rounded-3xl border border-charcoal-900/5">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-heading font-bold uppercase text-xs tracking-widest text-charcoal-900">Marketing</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="marketing" class="sr-only peer">
                                <div class="w-11 h-6 bg-charcoal-900/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sage-600"></div>
                            </label>
                        </div>
                        <p class="text-xs text-charcoal-900/50 leading-relaxed">
                            Używane do dopasowania reklam do Twoich zainteresowań.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button @click="settings = false" class="bg-transparent hover:bg-charcoal-900/5 text-charcoal-900 border border-charcoal-900/20 px-6 py-3 rounded-full font-heading font-bold text-sm transition-all cursor-pointer">
                        Wróć
                    </button>
                    <button @click="saveSettings()" class="bg-sage-600 hover:bg-sage-700 text-white px-8 py-3 rounded-full font-heading font-bold text-sm transition-all active:scale-95 cursor-pointer">
                        Zapisz ustawienia
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
