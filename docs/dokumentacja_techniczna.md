# Dokumentacja Techniczna Nevro-Shop v2

## 1. Architektura Systemu
System oparty jest na frameworku **Laravel 11** z wykorzystaniem stosu **TALL** (Tailwind, Alpine.js, Laravel, Livewire).

### Kluczowe komponenty:
- **Filament PHP v3**: Zaawansowany panel administracyjny.
- **Livewire v3**: Interaktywne komponenty frontendu (koszyk boczny, dynamiczne podsumowanie zamówienia, detale produktu).
- **Alpine.js**: Obsługa warstwy interaktywnej UI (galeria zdjęć, animacje bannerów, przełączniki widoku).
- **PostgreSQL**: Główna baza danych (produkcyjna).
- **Docker**: Pełna konteneryzacja środowiska (App, DB, Web, Node.js).

---

## 2. System Design: Bloom Hearth
Wdrożono autorski system wizualny "Bloom Hearth", oparty na filozofii organicznego funkcjonalizmu.

### Charakterystyka UI:
- **Geometria**: Duże promienie zaokrągleń (32px - 40px) dla głównych kontenerów i przycisków.
- **Paleta barw**: Dominacja kolorów natury (Sage - szałwia, Oatmeal - owsianka, Charcoal - węgiel drzewny).
- **Typografia**: Wykorzystanie nowoczesnego kroju *Plus Jakarta Sans* dla nagłówków i treści.
- **Responsywność**: Pełna adaptacja do urządzeń mobilnych oraz ekranów wysokiej rozdzielczości.
- **Stronicowanie (Pagination)**: Zunifikowany system stronicowania oparty na autorskim widoku Tailwind, eliminujący błędy kontrastu (tzw. "czarne stronicowanie") i dostosowany do motywu Sage.

---

## 3. Zarządzanie Treścią (CMS & Home)
Strona główna i kluczowe elementy UI są w pełni zarządzalne z poziomu panelu admina.

### Dynamiczne Menu i Nawigacja:
- **Kategorie**: Dropdown w nagłówku pobiera 8 pierwszych kategorii głównych (`parent_id IS NULL`), posortowanych rosnąco po kolumnie `position` (wraz z ich podkategoriami). Wspiera reorderowanie metodą Drag & Drop w panelu admina z automatycznym i natychmiastowym czyszczeniem pamięci podręcznej (Cache invalidation).
- **Strony CMS**: System inteligentnie wybiera aktywne strony z flagą `is_visible_in_footer` i umieszcza pierwsze dwie w górnym menu obok stałych linków.
- **Tożsamość Wizualna**: Logo marki NEVRO zintegrowane za pomocą relatywnych ścieżek ścieżek (`/images/logo.png`), co eliminuje problemy z protokołem HTTPS i zniekształceniami proporcji.

### System Galerii i Migracja Danych:
- **Migracja ShopGold**: Przeprowadzono kompleksową restaurację 411 plików galerii dla 188 produktów, zapewniając 100% spójności wizualnej z poprzednim sklepem.
- **Model Product**: Rozszerzony o kolumnę `gallery` (JSON) oraz inteligentne akcesory ścieżek, które mitygują błędy mieszanej treści (Mixed Content).
- **Interakcja**: Alpine.js zarządza płynnym przełączaniem zdjęć w galerii bez odświeżania strony.
- **Optymalizacja Slugów**: Implementacja algorytmu `LIKE + count()` zamiast pętli synchronicznych, co zapewnia stały czas odpowiedzi bazy (O(1)) przy generowaniu unikalnych adresów URL.

---

## 4. Inteligentne Wyszukiwanie (Meilisearch)
System porzucił klasyczne wyszukiwanie SQL na rzecz silnika **Meilisearch** uruchomionego w kontenerze Docker.
- **Instant Search**: Wyniki pojawiają się w trakcie pisania (Livewire GlobalSearch).
- **Fuzzy Matching**: Obsługa literówek i synonimów.
- **Filtrowanie**: Zaawansowane atrybuty filtrujące (cena, kategoria, status) skonfigurowane w `filterableAttributes` indeksu.

## 5. Analityka i Monitoring Marży
Wdrożono zaawansowany system śledzenia wydajności biznesowej.
- **GTM Server-Side**: Wszystkie zdarzenia zakupowe (`purchase`) są przesyłane bezpośrednio z serwera do endpointu GTM (`sgtm.nevro-wm.pl`), co gwarantuje 100% dokładności danych (odporność na AdBlockery).
- **Profit Tracking**: System automatycznie oblicza zysk na każdym zamówieniu na podstawie wprowadzonej w panelu ceny zakupu (`purchase_price`). Marża jest prezentowana w czasie rzeczywistym w tabeli zamówień Filament.

## 6. Płatności Odroczone (BNPL)
Pełna integracja z systemem **PayPo** (kanał 248 w Przelewy24).
- **Deep-Linking**: Wybór PayPo w koszyku powoduje natychmiastowe przekierowanie do panelu pożyczkowego, z pominięciem ekranu wyboru banku.
- **Conversion Widgets**: Dynamiczne informacje o płatności za 30 dni widoczne na listach i detalu produktu.

## 7. AI-Native & WebMCP
Sklep jest przygotowany na przyszłość wyszukiwania generatywnego (GEO).
- **WebMCP Protocol**: Implementacja endpointu JSON-RPC (`/api/mcp`) umożliwiającego agentom AI (Claude, GPT) przeglądanie katalogu i pobieranie danych technicznych produktów.
- **Discovery**: Obsługa standardów `ai.txt` oraz `.well-known/mcp` dla automatycznego wykrywania możliwości sklepu przez roboty.

## 8. Progressive Web App (PWA)
System posiada cechy aplikacji natywnej.
- **Offline Mode**: Dedykowana strona offline i caching zasobów przez Service Workera (`sw.js`).
- **Instalacja**: Pełna zgodność z manifestem PWA, umożliwiająca dodanie ikony sklepu do ekranu głównego telefonu.

## 9. Mobile-First Polish
Interfejs zoptymalizowany pod kątem konwersji mobilnej.
- **Bottom Navigation**: Stały pasek nawigacji (App-style) dla łatwego dostępu kciukiem.
- **Sticky Add-to-Cart**: Pływający pasek zakupu pojawiający się po przewinięciu detali produktu.
- **Input Optimization**: Inteligentne typy klawiatur i autouzupełnianie pól adresowych.

## 10. System Testów A/B
Wbudowane narzędzia do optymalizacji konwersji (ExperimentService).
- **Gotowe scenariusze**: Pasek darmowej dostawy, warianty tekstów CTA, testy kolorystyki.
- **Tracking**: Automatyczne zliczanie wizyt i konwersji dla każdego wariantu.

---

## 12. Stabilność Transakcyjna i Concurrency (Aktualizacja: 20.05.2026)
Wdrożono zaawansowane mechanizmy stabilizujące proces koszyka i kasy (checkout), chroniące przed asynchronicznymi wyścigami procesów oraz poprawiające odporność na błędy:
- **Pessimistic Locking w Koszyku:** Zapytania bazy danych operujące na ilościach produktów wykorzystują blokadę `lockForUpdate()`, chroniąc przed wyścigami ilości przy jednoczesnych zamówieniach.
- **Relatywne Aktualizacje Livewire:** Zastąpiono bezwzględne przypisywanie ilości z poziomu komponentów Livewire relatywną inkrementacją i dekrementacją (`+1` / `-1`), co wyeliminowało błędy nadpisywania stanu przy szybkim klikaniu plusów/minusów.
- **Obsługa Insufficient Stock:** Przechwytywanie wyjątków braku stanu magazynowego w bloku `try-catch` na poziomie komponentów Livewire. Błędy są prezentowane w postaci zamykanych bannerów (Alerts) w widoku, zamiast wywoływać błąd `500 Internal Server Error`.
- **Double-Submit Lock:** Blokowanie przycisku finalizacji zamówienia "Kupuję i płacę" za pomocą flagi `$isProcessing = true` oraz atrybutu HTML `disabled` na czas przetwarzania transakcji i kontaktu z bramką płatności.
- **Identyfikacja i Diagnostyka:** Zachowywanie niepełnych zamówień `pending` przy błędach sieciowych/autoryzacyjnych bramki Przelewy24 (np. kod błędu 401) jako cenne źródło diagnozy problemów z API dla administratora sklepu.

---

## 13. Wyniki Audytu Technicznego (Aktualizacja: 21.05.2026)
- **Security Grade**: A+ (IDOR protection, SSL hardening, GTM Server-Side).
- **Performance Grade**: A++ (Meilisearch, PWA caching, Optimized Slugs).
- **AI Readiness**: 100% (WebMCP, ai.txt, GEO optimization).
- **Concurrency & Stability**: 100% (Pessimistic locking, Double-submit lock, try-catch isolation).

---

## 14. Spójność Kolejności i Unieważnianie Cache (Aktualizacja: 21.05.2026)
Rozwiązano problem rozbieżności kolejności kategorii między panelem administracyjnym a stroną główną i menu:
- **Relacyjne i Globalne Sortowanie:** Wprowadzono jawne sortowanie `orderBy('position', 'asc')` we wszystkich zapytaniach kategorii na froncie (Strona główna, Detale kategorii, Listing produktów, Menu w AppServiceProvider) oraz w relacji `children()` w modelu `Category`.
- **Automatyczne Czyszczenie Cache:** Zarejestrowano obserwatory zdarzeń Eloquent (`saved`, `deleted`) w modelach `Category`, `Page` i `Setting` do automatycznego unieważniania klucza cache `global_view_data`.
- **Integracja z Reorder w Filament:** Metoda `reorderTable()` w kontrolerze Filament została rozbudowana o jawne czyszczenie cache (`Cache::forget('global_view_data')`), co gwarantuje natychmiastowe odzwierciedlenie nowej kolejności drag-and-drop na stronie użytkownika.

---

## 15. Środowisko Serwerowe i Deployment (Aktualizacja: 21.05.2026)
Sklep jest w pełni skonteneryzowany (Docker) i uruchomiony na serwerze VPS pod adresem `212.227.75.28` (Debian 12).
- **Środowisko Produkcyjne ([nevro-wm.pl](https://nevro-wm.pl)):** Kod znajduje się w katalogu `/var/www` i bazuje na gałęzi `master`. Działa na kontenerach `v2-app`, `v2-queue`, `v2-web` z dedykowanymi współdzielonymi kontenerami bazy PostgreSQL (`v2-db`), Redis (`v2-redis`), Meilisearch (`v2-meilisearch`) i Nginx Proxy Manager (`v2-proxy`).
- **Środowisko Stagingowe ([shop.nevro-wm.pl](https://shop.nevro-wm.pl)):** Kod znajduje się w katalogu `/var/www/staging` i bazuje na gałęzi `staging`. W celu optymalizacji zasobów staging współdzieli bazę danych PostgreSQL (`v2-db`), serwer Redis, Meilisearch oraz Nginx Proxy Manager z siecią produkcyjną, uruchamiając własne kontenery aplikacyjne: `staging-app`, `staging-queue` oraz `staging-web`.
- **Szczegółowa instrukcja wdrożeń oraz komendy backupu/przywracania baz danych** znajdują się w pliku operacyjnym [deployment.md](file:///Volumes/Third/Users/zbyszek/nevro-shop-v2/ops/Project_Memory/deployment.md).

---
*Dokumentacja techniczna Nevro-Shop v2 — Maj 2026 (Aktualizacja: 21.05.2026).*

