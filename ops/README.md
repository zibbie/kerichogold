# Orchestrator Kericho Gold (Pattern 2.0)

## Opis Systemu i Topologia Roju

System zarządzania projektem `kerichogold` wykorzystuje architekturę wieloagentową z dynamicznym trasowaniem zadań (Mesh 2.0). Orkiestrator rezyduje na VPS (`85.215.169.120`), a agenci wykonawczy pracują w środowisku lokalnym.

## Dzielona Pamięć (Shared Pattern Store)

W katalogu `Knowledge_Graph/Patterns/` gromadzone są "trajektorie sukcesu" — pliki `.md` opisujące specyficzne rozwiązania dla Laravel 11, TALL Stack i integracji płatności.
Plik `Project_Memory/state.md` zawiera aktualny stan projektu i trajektorię rozwoju.

## Bramki Egzekucyjne (Enforcement Gates)

Każde zadanie musi przejść weryfikację:
- **Linter:** `php artisan coach:lint`
- **Testy:** `php artisan test`

## Główne Reguły Operacyjne

* **Higiena Kontekstu:** Zakaz indeksowania całego projektu (tylko otwarte pliki).
* **Git Atomicity:** Commit per task z prefixem `task-[ID]:`.
* **Zasada Staging First:** Poprawki wdrażane są najpierw na środowisko **Staging**. Deployment na **Produkcję** następuje wyłącznie po wyraźnym wskazaniu i akceptacji przez Użytkownika.
* **VPS Handoff:** Orkiestrator wykonuje deployment po udanym `git push` i potwierdzeniu stanu Stagingu.

## Role Agentów

- **Agent 1 (Architect):** Planowanie i architektura (Gemini 3.1 Pro).
- **Agent 2 (Fast Editor):** Szybkie zmiany UI (Gemini 3 Flash).
- **Agent 3 (Mass Coder):** Nowe funkcje, TDD (Gemini 3.1 Pro).
- **Agent 4 (Scaler):** Prace masowe i narzędziowe.
- **Agent 5 (Mentor):** Wsparcie i koordynacja (Gemini 3.1 Pro).
