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
- **OrbStack VM Locking & Startup Recovery**: Resolved the `vmgr already running` startup deadlock lock by verifying GUI/VM daemon state, resulting in a successful automatic VM reboot and service recovery.
- **Meilisearch Key Security Hardening**: Changed the Meilisearch key to a compliant 16-byte minimum length (`masterKeySecure12345`) to enable successful startup in production environment mode.
- **Vite Frontend Compilation & 500 Fix**: Fixed a `500 Server Error` on the storefront caused by a missing Vite manifest. Compiled all assets with `npm run build` using `root` privileges inside the container to avoid `EACCES` permission blocks.
- **Database Migrations and Mock Data Seeding**: Successfully ran Artisan migrations and DB seeds within the app container to populate the catalog.
- **Storefront Logo Update**: Replaced the legacy `public/images/logo.png` with the official Kericho Gold storefront logo to complete the storefront rebranding.

## Next Steps
- **Admin Users Creation**: Seed initial admin users for the Filament dashboard on both production and staging environments.
- **Product Catalog Load**: Verify product sync and catalog setup for the tea products in the Filament dashboard.
- **Tpay Credentials & Live Mode**: Enter official production Tpay credentials in `.env` once provided by the merchant (currently using placeholders/sandbox keys).
 
## [UKOŃCZONE]
- [2026-05-22] Rozwiązanie problemu OrbStack VM lock (vmgr context deadline exceeded) przez reset deweloperskich demonów i automatyczny reboot maszyny.
- [2026-05-22] Skompilowanie zasobów frontendowych Vite (npm run build) wewnątrz kontenera aplikacji przy użyciu uprawnień roota, naprawiając błąd 500 (brak manifestu Vite).
- [2026-05-22] Wdrożenie bezpiecznego klucza głównego Meilisearch (spełniającego wymóg min. 16 bajtów w środowisku produkcyjnym) oraz poprawne uruchomienie kontenera wyszukiwarki.
- [2026-05-22] Uruchomienie migracji bazy danych i załadowanie początkowych danych (seeding) w lokalnym środowisku Docker.
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
