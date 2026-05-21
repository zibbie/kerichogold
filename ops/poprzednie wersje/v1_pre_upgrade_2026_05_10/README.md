# Orchestrator Nevro-Shop v2
 
## Opis Systemu i Topologia Hybrydowa (VPS-Local)
 
Projekt wykorzystuje architekturę hybrydową dostosowaną do zarządzania nowoczesnym sklepem opartym na Laravel (v11/PHP 8.3):
 
1.  **Główny Agent (Orchestrator)**: Monitoruje stan produkcji na VPS (`212.227.75.28`), analizuje logi kontenerów `v2-app` i `v2-web`, oraz zarządza procesem deploymentu.
2.  **Agenci Wykonawczy (Sub-agents)**: Działają w lokalnym folderze roboczym `/Volumes/Third/Users/zbyszek/nevro-shop-v2`. Odpowiadają za rozwój funkcji TALL stack i migrację danych.
 
## Dzielona Pamięć (Shared Pattern Store)
 
W katalogu `Knowledge_Graph/Patterns/` gromadzone są wzorce specyficzne dla Laravel i środowiska Docker:
*   Optymalizacja zapytań Eloquent.
*   Konfiguracja Nginx dla Laravel.
*   Zarządzanie stanem w Livewire.
 
## Bramki Egzekucyjne (Enforcement Gates)
 
Przed zgłoszeniem zadania, Agent musi zweryfikować kod lokalnie (np. `php artisan coach:lint`). Standardem jest pełna kompatybilność z Linuxem.
 
## Główne Reguły Operacyjne
 
* **Higiena Kontekstu:** Skupienie na plikach modyfikowanych, a nie na całych katalogach systemowych.
* **Zakaz Przeglądarki:** Całkowity zakaz używania `browser_subagent` (chyba że polecenie brzmi: "sprawdź stronę publiczną").
* **Kontrakt Wyjściowy:** Każde nieszablonowe rozwiązanie musi zostać udokumentowane w `Patterns/`.
* **Żelazna Tarcza:** Trzymaj się zasad z pliku: `orchestrator/zelazna_tarcza_instrukcja_dla_agenta_ai.md`

## Historia Sprintów (Log)
- **Sprint 2026-05-07**: Wdrożenie logiki dropshippingu, kosztów wysyłki, płatności COD, reorganizacja kategorii, oraz poprawki licznika koszyka i szablonów e-mail. Pełny raport i instrukcje dla Agenta: zobacz `session_handoff_2026_05_07.md`.
- **Sprint 2026-05-06**: CMS Stabilization & Payment Integration.
    - Full stabilization of Hero Banner module (dynamic BG, opacity, null-safety).
    - New CMS modules: "Kontakt Home" (CTA section) and "Stopka" (Footer content).
    - Przelewy24 Production Fix: domain enforcement (localhost block mitigation) and credential update.
    - UI Refinement: Pagination localization and "delicate" restyling.
