# Project State

*Aktualizowany automatycznie przez Orkiestratora i Agenty po każdej zmianie statusu zadania.*

## Current Status
- **GA4/GTM Integration**: 100% Verified. Consent Mode v2 implemented, Server-Side Purchase event active with profit tracking, GA Client ID parsed correctly.
- **SEO & Feed**: GMC feed sanitized (234 images), 301 redirects for legacy URLs active, JSON-LD optimized.
- **Dynamic Notifications**: Admin emails are retrieved dynamically from the DB settings rather than hardcoded in the codebase, enabling live admin lists.
- **Unified Spacing Grid**: Rigorous 16px/32px vertical spacing grid applied to mobile categories, Our Hits card, and Bestsellers.
- **Responsive Category Headers**: Clean transparent headers on mobile (uppercase grey `text-xs`) automatically morph into high-end white card headers on desktop (`text-3xl`) with correct padding overrides.
- **Payment Infrastructure**: Przelewy24 live production payments fully active on `https://nevro-wm.pl` (P24 Sandbox deactivated). Staging (`https://shop.nevro-wm.pl`) remains in Sandbox mode.
- **Cart & Checkout Stability**: Implemented relative quantity updates (+/- 1) to eliminate Livewire state overwrites during rapid clicking. Integrated double-submit lock in checkout and graceful handling of `Insufficient stock` exceptions (try-catch with user alerts) to prevent 500 Internal Server Errors.
- **Infrastructure**: Production server (212.227.75.28) synchronized with staging updates. Asset pipelines rebuilt and optimized.

## Recent Changes
- **Live Payments Restoration & DB Cleanup**: Restored production Przelewy24 live credentials in the `.env` file, restored the database state to the official pre-sandbox-test backup (removing temporary sandbox test orders 106-125), and restarted production app/queue containers.
- **Graceful Insufficient Stock Handling**: Obounded Livewire cart modification methods (`Cart`, `CartPage`, `ProductDetail`) in try-catch blocks to prevent 500 Internal Server Errors when stock thresholds are exceeded. Display elegant closeable error alert banners on the front-end.
- **Safari Reader Mode (a11y) Bypass**: Resolved accessibility bug where Safari Reader Mode (CMD+SHIFT+R) extracted raw icon ligatures as text (e.g., rendering "category Kategorie" instead of just "Kategorie"). Added aria-hidden="true" to all presentational Material Symbol icon spans across templates.
- **Material Symbols FOUT/CLS Prevention**: Resolved font display issue where Material Symbol ligatures rendered as raw words (like "shopping_cart", "menu") during page refresh, causing unstyled text flashing and layout shifts. Switched GFonts to display=block and enforced width/height limits on icon spans.
- **COD Surcharge Calculation Sync**: Fixed the discrepancy where the 5 PLN cash-on-delivery (COD) surcharge was correctly calculated in the checkout cart but dropped during conversion to the final Order due to server-side shipping recalculation without payment context.
- **Filament Address Object Object Fix**: Resolved the critical bug where Filament attempted to display array-based shipping addresses as a string in the textarea, resulting in "[object Object]" and threatening data loss upon save.
- **Live Payments Activation**: Deactivated sandbox mode by setting `P24_ENV=production` in the production `.env` and restarted web and queue containers.
- **Responsive Category Page Header**: Redesigned category details headers using layout specificity fixes (`px-2 py-0 md:px-8 md:py-8`) to display clean labels on mobile and gorgeous premium white cards on desktop.
- **Rigorous Spacing Grid Alignment**: Replaced wide mobile gaps with unified `gap-4` (16px) layout grids, symmetrically styling section titles and the "Nasze Hity" card.
- **Dynamic Admin E-mail Notification Routing**: Replaced static arrays with `Setting::get('admin_emails')` in `OrderPaid` and `PaymentFailed` notification classes, enabling settings control.
- **Syncing Staging to Production**: Merged `staging` branch into `master`, successfully deployed to production, rebuilt assets using Rollup/Vite, and cleared caches.

## Next Steps
- **Production Order Monitoring**: Monitor live incoming Przelewy24 transactions and webhook callback logging.
- **Performance Max Campaigns**: Initiate ad campaigns using optimized GMC product catalog export now that tracking is flawless.
- **Clean Checkout Orphans (Future)**: Implement automatic removal of newly created Order records from the database if payment gateway transaction registration (P24/Tpay) fails. (Currently left as-is on purpose to serve as a diagnostic tool for API errors).

## [UKOŃCZONE]
- [2026-05-21] Naprawa sortowania kategorii w adminie (Filament) i na froncie: Wdrożenie jawnego sortowania `orderBy('position', 'asc')` w relacjach i zapytaniach oraz automatycznego czyszczenia pamięci podręcznej `global_view_data` przy zapisie/usunięciu kategorii, stron i ustawień, a także przy drag-and-drop w Filament.
- [2026-05-20] Przywrócenie oficjalnych danych produkcyjnych Przelewy24 w .env na produkcji, przywrócenie stanu bazy danych (oczyszczenie tymczasowych zamówień sandboxowych 106-125) i restart kontenerów aplikacji/kolejek.
- [2026-05-19] Wdrożenie try-catch i obsługa wyjątków braku stanu magazynowego (`Insufficient stock`) w komponentach koszyka Livewire i dodanie banerów z błędami na froncie.
- [2026-05-19] Rozwiązanie problemu wyścigu stanów koszyka (relatywna inkrementacja/dekrementacja ilości w Livewire) oraz double-submit w kasie na Stagingu.
- [2026-05-19] Rozwiązanie problemu ekstrakcji surowych ligatur ikon w trybie czytnika Safari (Reader Mode) poprzez masowe dodanie atrybutów aria-hidden="true" w widokach.
- [2026-05-19] Eliminacja błysków czcionek ikonowych (FOUT) i skoków layoutu (CLS) poprzez zmianę sposobu renderowania na display=block i dodanie stylów ochronnych dla Material Symbols.
- [2026-05-18] Rozwiązanie problemu braku doliczania opłaty pobraniowej (COD) w zamówieniu: Dodanie obsługi opłaty pobraniowej do serwerowej rekalkulacji kosztów dostawy w CartService.
- [2026-05-18] Rozwiązanie problemu zapisu/wyświetlania "[object Object]" w adresie dostawy w Filament: Naprawa poprzez formatStateUsing/dehydrateStateUsing w OrderResource.
- [2026-05-10] Upgrade systemu ops do wersji orchestrator-wzorzec-2.0.
- [2026-05-10] Aktualizacja linków w całym systemie z `orchestrator/` na `ops/`.
- [2026-05-10] Inicjalizacja audytu SEO, GA4, Ads i GMC.
- [2026-05-10] Implementacja flagi `google_merchant_center_export` w produktach (Baza danych, Model, Filament, Controller).
- [2026-05-10] Wdrożenie poprawek SEO (Faza 1): Meta opisy, tytuły, canonicals.
- [2026-05-10] Ujednolicenie śledzenia GA4 (Faza 2): ID produktów, brand, `view_item_list`.
- [2026-05-10] Optymalizacja Google Ads i Merchant Center (Faza 3 i 4): Dodanie GPC, view_cart event, dynamiczny brand.
- [2026-05-10] Wdrożenie Zaawansowanej Analizy Logów (Faza 1 Planu Przyszłości): Model, Parser, Dashboard w Filament.
- [2026-05-10] Wdrożenie Protokołu WebMCP (Faza 2 Planu Przyszłości): API Discovery, McpController, JSON-RPC Tools.
- [2026-05-10] Wdrożenie Modułu A/B Testingu (Faza 3 Planu Przyszłości): Middleware, Zarządzanie w Filament, Statystyki.
- [2026-05-13] Przeprowadzenie audytu technicznego (Security, Performance, Architecture) dla kluczowych serwisów i modeli.
- [2026-05-13] Optymalizacja wydajności: Dodanie indeksu na `categories.position` oraz optymalizacja generowania slugów (LIKE + count).
- [2026-05-13] Bezpieczeństwo: Wdrożenie walidacji własności koszyka (IDOR fix) w ShippingService.
- [2026-05-13] Architektura: Wydzielenie konfiguracji wysyłki do `config/shipping.php` i ujednolicenie obliczeń (brak double-charging).
- [2026-05-14] Naprawa mailingu: Restart kontenera `v2-queue`, wdrożenie obsługi webhooków Tpay w `PaymentController`, dodanie BCC dla obu adresów admina (`info@`, `biuro@`) oraz ujednolicenie powiadomień.
- [2026-05-15] Pełna stabilizacja GMC: Wdrożenie czystych linków zdjęć, fallbacków wagi oraz poprawnej flagi `identifier_exists`.
- [2026-05-15] Wdrożenie serwerowego śledzenia zakupów (Measurement Protocol) z obsługą zysku (Profit) i pełnych danych e-commerce.
- [2026-05-15] Optymalizacja Mobile UX: Poziome kategorie, gest swipe w koszyku, ulepszona wyszukiwarka na górze menu.
- [2026-05-15] Stabilizacja Autofill: Pełna kompatybilność z autouzupełnianiem Android/iOS w formularzu zamówienia.
- [2026-05-15] Immediate Post-Mortem: Konsolidacja wiedzy o GMC i audycie technicznym we wzorcach `ops/Knowledge_Graph`.
- [2026-05-16] Stabilizacja Staging: Naprawa kompilacji Tailwind v4 poprzez zmianę ścieżek `@source` na relatywne i wdrożenie `npm run build` w kontenerze.
- [2026-05-16] Unifikacja UI: Przywrócenie produkcyjnej typografii na listingach przy jednoczesnym zachowaniu usprawnień mobile (ukryty sidebar, nowa nawigacja).
- [2026-05-16] Debugging Layoutu: Naprawa błędów strukturalnych (domknięcia divów) w sekcji "Nasze Hity" na stronie głównej.
- [2026-05-16] Ujednolicenie Tytułów: Przekształcenie tytułów sekcji ("Nasze Hity", "Bestsellery", "Kategorie" oraz strony kategorii) do eleganckiego text-xs bold uppercase z tracking-widest.
- [2026-05-16] Visual Hint Kategorie: Dodanie poziomego wskaźnika przesunięcia w formie pulsującego tekstu "PRZESUŃ", animowanej strzałki i bocznych gradientów maskujących.
- [2026-05-16] Oczyszczenie Szukajki: Likwidacja podwójnej lupki w menu mobilnym i zachowanie pojedynczej lupki wewnątrz pola wyszukiwania globalnego.
- [2026-05-16] Wdrożenie shop.nevro-wm.pl: Zidentyfikowanie mapowania proxy na kontener `staging-app` (katalog `/var/www/staging`) i wdrożenie tam pełnego zestawu poprawek UX.
- [2026-05-19] Zmapowanie architektury bazy kodu (SKILL: map_codebase) i zaktualizowanie pliku architecture.md.

## [W TOKU]
- Weryfikacja spójności Knowledge_Graph (Inboxy -> Patterns).
- Monitoring wdrożenia nowej metodologii Mesh 2.0.

## [NASTĘPNE]
- Audyt spójności sitemap.xml z aktualnym stanem feedu GMC.

---
*Ostatnia aktualizacja: 2026-05-21*

<!--
HISTORIA WDROŻENIA WZORCA (orchestrator-wzorzec):
Faza 1-4: Ukończono w wersji wzorcowej 2026-05-08.
Faza 5: Implementacja w nevro-shop-v2 (upgrade z v1): 2026-05-10
-->
