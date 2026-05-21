<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Translate Password Reset Email to Polish
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = ResetPassword::$createUrlCallback
                ? call_user_func(ResetPassword::$createUrlCallback, $notifiable, $token)
                : url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false));

            return (new MailMessage)
                ->subject('Resetowanie hasła - Nevro Shop')
                ->greeting('Witaj!')
                ->line('Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zresetowanie hasła dla Twojego konta.')
                ->action('Resetuj hasło', $url)
                ->line('Ten link do zresetowania hasła wygaśnie za 60 minut.')
                ->line('Jeśli to nie Ty prosiłeś o zresetowanie hasła, nie musisz podejmować żadnych dalszych działań.')
                ->salutation("Pozdrawiamy,\nZespół Nevro Shop");
        });

        // Set dynamic timezone from settings
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $timezone = \App\Models\Setting::get('timezone', 'Europe/Warsaw');
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        \Illuminate\Pagination\Paginator::useTailwind();
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.tailwind');

        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            config(['session.secure' => true]);
            config(['session.http_only' => true]);
        }

        \Illuminate\Support\Facades\Auth::provider('legacy', function ($app, array $config) {
            return new \App\Auth\LegacyUserProvider($app['hash'], $config['model']);
        });

        // Default values for views (to prevent 500 errors if DB is not ready/migrated)
        \Illuminate\Support\Facades\View::share('nav_categories', collect());
        \Illuminate\Support\Facades\View::share('footer_pages', collect());
        \Illuminate\Support\Facades\View::share('shop_logo', '/images/logo.png');
        \Illuminate\Support\Facades\View::share('currency_symbol', 'zł');
        \Illuminate\Support\Facades\View::share('google_ads_id', null);
        \Illuminate\Support\Facades\View::share('google_analytics_id', null);
        \Illuminate\Support\Facades\View::share('google_tag_manager_id', null);
        \Illuminate\Support\Facades\View::share('footer_description', 'Twój zaufany dostawca rozwiązań dla zbiorników IBC i akcesoriów ogrodowych.');
        \Illuminate\Support\Facades\View::share('footer_email', 'kontakt@nevro-wm.pl');
        \Illuminate\Support\Facades\View::share('footer_phone', '+48 123 456 789');
        \Illuminate\Support\Facades\View::share('footer_copyright', '© 2026 Nevro-Shop. Wyhodowano z miłością.');
        \Illuminate\Support\Facades\View::share('cookie_consent_active', true);
        \Illuminate\Support\Facades\View::share('cookie_consent_title', 'Zarządzaj zgodą na pliki cookie');
        \Illuminate\Support\Facades\View::share('cookie_consent_description', 'Używamy plików cookie, aby poprawić komfort przeglądania...');
        \Illuminate\Support\Facades\View::share('cookie_consent_policy_url', '/page/polityka-prywatnosci');

        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $globalData = \Illuminate\Support\Facades\Cache::remember('global_view_data', 3600, function() {
                return [
                    'settings' => \App\Models\Setting::all()->pluck('value', 'key'),
                    'footer_pages' => \App\Models\Page::where('is_visible_in_footer', true)->where('is_active', true)->get(),
                    'nav_categories' => \App\Models\Category::with('children')->where('status', true)->whereNull('parent_id')->orderBy('position', 'asc')->take(8)->get(),
                ];
            });

            $settings = collect($globalData['settings'] ?? []);

            if ($settings->isNotEmpty()) {
                \Illuminate\Support\Facades\View::share('shop_logo', $settings->get('logo_url') ?: '/images/logo.png');
                \Illuminate\Support\Facades\View::share('currency_symbol', $settings->get('currency_symbol', 'zł'));
                \Illuminate\Support\Facades\View::share('footer_pages', $globalData['footer_pages'] ?? collect());
                
                // Marketing Tracking
                \Illuminate\Support\Facades\View::share('google_ads_id', $settings->get('google_ads_id'));
                \Illuminate\Support\Facades\View::share('google_analytics_id', $settings->get('google_analytics_id'));
                \Illuminate\Support\Facades\View::share('google_tag_manager_id', $settings->get('google_tag_manager_id'));
                \Illuminate\Support\Facades\View::share('nav_categories', $globalData['nav_categories'] ?? collect());
                
                // Footer Settings
                \Illuminate\Support\Facades\View::share('footer_description', $settings->get('footer_description', 'Twój zaufany dostawca rozwiązań dla zbiorników IBC i akcesoriów ogrodowych.'));
                \Illuminate\Support\Facades\View::share('footer_email', $settings->get('footer_email', 'kontakt@nevro-wm.pl'));
                \Illuminate\Support\Facades\View::share('footer_phone', $settings->get('footer_phone', '+48 123 456 789'));
                \Illuminate\Support\Facades\View::share('footer_copyright', $settings->get('footer_copyright', '© 2026 Nevro-Shop. Wyhodowano z miłością.'));
    
                // Cookie Consent Settings
                \Illuminate\Support\Facades\View::share('cookie_consent_active', (bool) $settings->get('cookie_consent_active', true));
                \Illuminate\Support\Facades\View::share('cookie_consent_title', $settings->get('cookie_consent_title', 'Zarządzaj zgodą na pliki cookie'));
                \Illuminate\Support\Facades\View::share('cookie_consent_description', $settings->get('cookie_consent_description', 'Używamy plików cookie, aby poprawić komfort przeglądania...'));
                \Illuminate\Support\Facades\View::share('cookie_consent_policy_url', $settings->get('cookie_consent_policy_url', '/page/polityka-prywatnosci'));
            }
        }
    }
}
