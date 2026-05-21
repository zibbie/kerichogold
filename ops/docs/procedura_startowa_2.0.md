# Procedura Startowa (Pattern 2.0)

W nowym systemie sesja nie zaczyna się od bezpośredniego kodowania, lecz od **synchronizacji stanu** i **walidacji wymagań**. Oto jak przebiega start:

## 1. Działania Użytkownika (USER)

Użytkownik inicjuje proces, podając cel lub zgłaszając problem.

1.  **Fraza startowa:** Rozpocznij od frazy: `"Rozpocznij sesję: [opis zadania]"` lub `"Nowe zadanie: [opis]"`.
2.  **Kontekst zewnętrzny:** Jeśli zadanie wynika z błędów na produkcji, dołącz logi (np. z `storage/logs/laravel.log`) lub screenshoty/opisy błędów z przeglądarki.
3.  **Akceptacja Planu:** Po fazie `Plan`, Orkiestrator przedstawi trajektorię (Fale zadań). Użytkownik musi ją zatwierdzić (np. słowem "Działaj" lub "OK"), zanim agenci zaczną pracę.

## 2. Działania Agenta (ORKIESTRATOR / VPS MAIN)

Orkiestrator wykonuje sekwencję kroków zgodnie z nowymi Skillami:

### Krok A: Inicjalizacja Pamięci
*   **Analiza Stanu:** Czyta `Project_Memory/state.md`, aby wiedzieć, gdzie skończyliśmy poprzednio.
*   **Analiza Wzorców:** Przeszukuje `Knowledge_Graph/Patterns/` pod kątem podobnych zadań.

### Krok B: Faza Discuss (Skrypt `discuss_phase.md`)
*   Jeśli zadanie jest niejasne, Orkiestrator zadaje pytania o logikę biznesową, UX lub architekturę.
*   Wynik: Aktualizacja `Project_Memory/requirements.md`.

### Krok C: Faza Plan (Skrypt `plan_phase.md`)
*   Orkiestrator tworzy plan podzielony na **Fale (Waves)**.
*   **Fala 1:** Zadania niezależne (mogą być robione równolegle).
*   **Fala 2:** Zadania zależne od wyników Fali 1.
*   Wynik: Aktualizacja sekcji `[W TOKU]` i `[NASTĘPNE]` w `Project_Memory/state.md`.

### Krok D: Delegacja Mesh i Deployment
*   Zapisuje instrukcje (Handoffs) do odpowiednich folderów w `Inboxes/`.
*   Każdy handoff zawiera listę atomowych commitów i wymóg walidacji (Linter/Tests).
*   **Zasada Staging First:** Każda poprawka musi trafić najpierw na **Staging**. Dopiero po akceptacji przez Użytkownika, Orkiestrator wykonuje deployment na produkcję.

---

> [!TIP]
> Dzięki tej procedurze unikamy "dryfowania projektu" i zapominania o ustaleniach z poprzednich dni. Wszystko jest zapisane w `Project_Memory`.
