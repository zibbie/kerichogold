# System Architecture Map

*Dokument generowany przez SKILL: map_codebase.*

## Stos Technologiczny
- **Język/Framework:** PHP 8.2+ / Laravel 11.x, Livewire 3.x, Filament 3.x
- **Frontend / Styling:** Tailwind CSS v4.0.0 (kompilacja via `@tailwindcss/vite` w Vite 8.x)
- **Baza danych:** PostgreSQL 15 (produkcja & lokalny Docker)
- **Kolejki & Cache:** Redis (alpine)
- **Wyszukiwarka:** Meilisearch v1.7
- **Serwer WWW & Proxy:** Nginx (alpine), Nginx Proxy Manager (jc21/nginx-proxy-manager)

## Struktura Katalogów
- `/app` — Klasy aplikacji (Models, Services, Controllers, Livewire Components, Filament Resources)
- `/config` — Pliki konfiguracyjne Laravela (np. `shipping.php`, `database.php`)
- `/database` — Migracje, Seedery i Fabryki modeli Eloquent
- `/docker` — Konfiguracje kontenerów (Dockerfile, Nginx, wolumeny proxy)
- `/ops` — Pliki operacyjne, pamięć projektu (Project_Memory), instrukcje i wzorce (Knowledge_Graph)
- `/public` — Publiczne assety, punkt wejścia index.php, skompilowane pliki w `build/`
- `/resources` — Widoki Blade, komponenty Livewire Blade, pliki źródłowe CSS/JS
- `/routes` — Definicje tras (web.php, api.php, console.php)
- `/tests` — Testy jednostkowe i integracyjne PHPUnit

## Konwencje Projektowe
- **Nazewnictwo:** PascalCase dla komponentów Livewire i klas PHP, snake_case dla plików widoków Blade, kebab-case dla slugów/routingu.
- **Odpowiedzialność:** Services (`app/Services`) jako serce logiki biznesowej; modele Eloquent posiadające wyłącznie relacje i casty.
- **Git Atomicity:** Commit per task z prefixem `task-[ID]:`.
- **Wdrożenia:** Najpierw wdrożenie i testy na staging (`shop.nevro-wm.pl`), a po zatwierdzeniu przez użytkownika — produkcja (`nevro-wm.pl`).

## Zależności Zewnętrzne
- **Bramka Płatności:** Przelewy24 (obsługiwana produkcyjnie na domenie głównej, sandbox na stagingu)
- **Google Merchant Center:** Generowanie feedu XML (`GoogleFeedController`)
- **Google Analytics 4 / Tag Manager:** Śledzenie eCommerce i Consent Mode v2
