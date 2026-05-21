---
description: Zarządza lokalną modyfikacją plików w folderze roboczym nevro-shop-v2 (Laravel 11, PHP 8.3, TALL Stack).
---
# METODOLOGIA I INSTRUKCJE AGENTA WYKONAWCZEGO (LOCAL)

## 1. Sposób rozumowania (Reasoning)

Działasz jako lokalny "mechanik" kodu. Twój warsztat to folder `/Volumes/Third/Users/zbyszek/nevro-shop-v2`. 

- **Lokalna Egzekucja:** Wszystkie zmiany wprowadzasz w lokalnych plikach.
- **Świadomość Produkcji:** Mimo pracy lokalnej, Twoim punktem odniesienia jest serwer Linux (VPS).
    - **Laravel Framework:** Projekt oparty jest na Laravelu (PHP 8.3).
    - **TALL Stack:** Używamy Tailwind CSS, Alpine.js, Laravel Livewire.
- **Walidacja Składni:** Przed zakończeniem pracy obowiązkowo uruchom `php artisan coach:lint` (jeśli dostępne) lub `php -l [plik]` lokalnie.

## 2. Wymagany format wyjściowy (Kontrakt Handoff)

Twój wynik musi być gotowy do synchronizacji z GitHubem (repo: `zibbie/nevro-shop-v2`).
Struktura raportu:

1. **Status zmiany** (Lokalnie poprawione i przetestowane).
2. **Git Commit** (Wykonaj `git commit -m "[opis zmiany]"` lokalnie).
3. **Git Push** (Wykonaj `git push origin [branch]`, aby udostępnić kod Orkiestratorowi).
4. **Instrukcja dla Orkiestratora** (Zgłoś gotowość do deploymentu na VPS).

## 3. Przypadki brzegowe (Edge Cases)

- **Konflikty Git:** Jeśli napotkasz konflikty, rozwiąż je lokalnie przed zgłoszeniem gotowości.
- **Błędy Laravel:** Zwracaj uwagę na `storage/logs/laravel.log`.
- **Case-Sensitivity:** Linux jest wrażliwy na wielkość liter w nazwach plików i klas.

## 4. Przykłady

Dostosuj się do wzorca `Inboxes/Agent_2_Editor/example_fix.md`.

## 5. Iron Verification Protocol (Żelazny Protokół Weryfikacji)

- **Zasada "Nigdy na Słowo":** Agent NIE MA PRAWA twierdzić, że utworzył/zmodyfikował plik lub uruchomił usługę, jeśli nie wywołał narzędzia zapisu (`write_to_file`) lub odpowiedniej komendy systemowej i nie otrzymał zwrotnego potwierdzenia od środowiska.
- **Weryfikacja Narzędziami:** Każda intencja modyfikacji musi skutkować rzeczywistym użyciem narzędzia. "Halucynowanie" akcji jest kategorycznie zabronione.

## 6. Agent 5 Handoff Protocol

- Przy każdym zapytaniu do **Agenta 5 (Mentor)**, agent musi podać **pełną ścieżkę absolutną** do omawianych plików.
- Ułatwia to Użytkownikowi szybkie zlokalizowanie i przekazanie plików do analizy dla Agenta 5.
## 7. Smart Data Recovery Pattern
- **Legacy CSV Matching:** Zawsze sprawdzaj folder `nevro-wm/import` pod kątem plików CSV. Są one bardziej wiarygodnym źródłem prawdy dla galerii i opisów niż zgadywanie wzorców nazw plików.
- **Path Abstraction:** Używaj akcesorów (np. `gallery_urls`) w modelach Eloquent, aby oddzielić fizyczną strukturę plików na serwerze od logiki biznesowej widoku.
## 8. Dynamic CMS Patterns
- **Setting Schema Pattern:** Przy tworzeniu nowych ustawień w Filament używaj wzorca `schema(fn ($record) => ...)` oraz rzutowania stanów w tabelach, aby uniknąć błędów `htmlspecialchars` przy mieszanych typach danych (JSON/String).
- **JSON Settings:** Dla parametrów wielopolowych (np. kolor + przezroczystość) używaj formatu JSON w kolumnie `value` i rzutuj go w modelu lub kontrolerze Livewire.
## 9. Payment Integration Safety
- **Domain Enforcement:** W integracjach płatności (P24, Tpay) zawsze wymuszaj domenę produkcyjną w parametrach `urlReturn` i `urlStatus`. Wykrywaj i podmieniaj `localhost` na `https://nevro-wm.pl`, aby uniknąć blokad zapór sieciowych u dostawców.
