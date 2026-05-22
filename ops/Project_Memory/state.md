# Project State

*Aktualizowany automatycznie przez Orkiestratora i Agenty po każdej zmianie statusu zadania.*

## Current Status
- **GA4/GTM Integration**: 100% Verified. Consent Mode v2 implemented, Server-Side Purchase event active with profit tracking, GA Client ID parsed correctly.
- **SEO & Feed**: GMC feed sanitized, 301 redirects for legacy URLs active, JSON-LD optimized.
- **Dynamic Notifications**: Admin emails are retrieved dynamically from the DB settings rather than hardcoded in the codebase, enabling live admin lists.
- **Unified Spacing Grid**: Rigorous 16px/32px vertical spacing grid applied to mobile categories, Our Hits card, and Bestsellers.
- **Responsive Category Headers**: Clean transparent headers on mobile (uppercase grey `text-xs`) automatically morph into high-end white card headers on desktop (`text-3xl`) with correct padding overrides.
- **Payment Infrastructure**: Tpay payments with JWS signature verification fully active. Staging (`https://sklep2.kerichogold.com.pl`) and Production (`https://sklep.kerichogold.com.pl`) signature verification successfully passes automated security tests.
- **Cart & Checkout Stability**: Implemented relative quantity updates (+/- 1) to eliminate Livewire state overwrites during rapid clicking. Integrated double-submit lock in checkout and graceful handling of `Insufficient stock` exceptions (try-catch with user alerts) to prevent 500 Internal Server Errors.
- **Infrastructure**: Debian 13 VPS (`85.215.169.120`) running Docker. Production (`https://sklep.kerichogold.com.pl`) and Staging (`https://sklep2.kerichogold.com.pl`) run successfully over HTTPS via Nginx Proxy Manager. Staging environment `.env` updated to use container-name hostnames (`kericho-db`, `kericho-redis`, `kericho-meilisearch`) for service routing on the shared external network, resolving DNS resolution errors and enabling the queue worker (`kericho-staging-queue`) to run successfully.

- **Storefront Logo Update**: Replaced the legacy green "NEVRO" logo with the official "Kericho Gold" logo (featuring the tea leaves emblem and custom branding font) fetched directly from the merchant's live site, updating it across both production and staging environments.

## Recent Changes
- **Storefront Logo Update**: Replaced the legacy `public/images/logo.png` with the official Kericho Gold storefront logo to complete the storefront rebranding.
- **Staging Database & Cache Hostname Fixes**: Replaced legacy `db` and `redis` hostnames in staging `.env` with actual container names `kericho-db` and `kericho-redis` to allow staging containers to resolve external services.
- **Queue Worker Stabilization**: Verified and restarted `kericho-staging-queue` container, which is now up, running, and successfully connected to Redis without connection exceptions.
- **Let's Encrypt SSL Mapping via NPM**: Programmatically configured and verified NPM routes for `sklep.kerichogold.com.pl` and `sklep2.kerichogold.com.pl` with automatic Let's Encrypt registration.
- **JWS Signature Verification Validation**: Verified signature checking routines on both production and staging application servers using `php artisan test:tpay-security` command (both PASS).
- **Graceful Insufficient Stock Handling**: Bounded Livewire cart modification methods in try-catch blocks to prevent 500 Errors. Display elegant closeable error alert banners on the front-end.
- **Safari Reader Mode (a11y) Bypass**: Resolved accessibility bug where Safari Reader Mode extracted raw icon ligatures as text. Added aria-hidden="true" to all presentational Material Symbol icon spans across templates.
- **Material Symbols FOUT/CLS Prevention**: Resolved font display issue where Material Symbol ligatures rendered as raw words during page refresh. Switched GFonts to display=block and enforced width/height limits on icon spans.
- **COD Surcharge Calculation Sync**: Fixed the discrepancy where the 5 PLN cash-on-delivery (COD) surcharge was dropped during conversion to the final Order due to server-side shipping recalculation without payment context.
- **Filament Address Object Object Fix**: Resolved the critical bug where Filament attempted to display array-based shipping addresses as a string in the textarea, resulting in "[object Object]".
- 
## Next Steps
- **Admin Users Creation**: Seed initial admin users for the Filament dashboard on both production and staging environments.
- **Product Catalog Load**: Verify product sync and catalog setup for the tea products in the Filament dashboard.
- **Tpay Credentials & Live Mode**: Enter official production Tpay credentials in `.env` once provided by the merchant (currently using placeholders/sandbox keys).
 
## [UKOŃCZONE]
- [2026-05-22] Wymiana logo sklepu w obu środowiskach (produkcyjnym i testowym) na oficjalne logo Kericho Gold (pobrane ze strony sklepu kerichogold.pl).
- [2026-05-22] Wdrożenie nowej instalacji sklepu Kericho Gold (produkcja i staging) na nowy serwer VPS (85.215.169.120) pod domenami sklep.kerichogold.com.pl i sklep2.kerichogold.com.pl na systemie Debian 13 (Trixie).
- [2026-05-22] Rozwiązanie problemów z rozdzielczością DNS kontenerów w środowisku stagingowym (poprawa hostów bazy i redisa w pliku .env) i pełne uruchomienie kontenera staging queue worker.
- [2026-05-22] Skonfigurowanie Nginx Proxy Manager (NPM) na porcie 80/443 z automatycznym Let's Encrypt certyfikatem SSL dla obu domen.
- [2026-05-22] Uruchomienie i weryfikacja poprawności testów bezpieczeństwa Tpay (JWS validation check) na obu kontenerach aplikacyjnych.
- [2026-05-21] Zmapowanie architektury bazy kodu i zaktualizowanie dokumentacji konfiguracji serwera VPS.
- [2026-05-20] Naprawa sortowania kategorii w adminie (Filament) i na froncie.
- [2026-05-19] Wdrożenie try-catch i obsługa wyjątków braku stanu magazynowego (`Insufficient stock`) w komponentach koszyka Livewire.
- [2026-05-19] Rozwiązanie problemu wyścigu stanów koszyka oraz double-submit w kasie na Stagingu.
- [2026-05-19] Rozwiązanie problemu ekstrakcji surowych ligatur ikon w trybie czytnika Safari.
- [2026-05-19] Eliminacja błysków czcionek ikonowych (FOUT) i skoków layoutu (CLS).
- [2026-05-18] Rozwiązanie problemu braku doliczania opłaty pobraniowej (COD) w zamówieniu.
- [2026-05-18] Rozwiązanie problemu zapisu/wyświetlania "[object Object]" w adresie dostawy w Filament.

## [W TOKU]
- Weryfikacja spójności Knowledge_Graph (Inboxy -> Patterns).
- Synchronizacja lokalnych plików konfiguracyjnych i dokumentacji z repozytorium zdalnym `zibbie/kerichogold`.

## [NASTĘPNE]
- Audyt spójności sitemap.xml z aktualnym stanem feedu GMC.

---
*Ostatnia aktualizacja: 2026-05-22*
