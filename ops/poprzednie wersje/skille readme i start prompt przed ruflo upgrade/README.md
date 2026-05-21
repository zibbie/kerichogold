# [Nazwa Twojego Projektu]

## Opis Systemu

Projekt jest rozwijany z wykorzystaniem architektury wieloagentowej (Antigravity Cluster) koordynowanej przez centralnego Orkiestratora. System opiera się na asynchronicznej wymianie zadań poprzez dedykowane foldery i rygorystycznej separacji kontekstu.

## Stos Technologiczny i AI Stack

* **Agent 1 (Orkiestrator):** Gemini 3.1 Pro (Web) — Czyste wnioskowanie, planowanie architektury, trasowanie zadań (Planning Mode).
* **Agent 2 (Fast Editor):** Gemini 3 Flash (Antigravity Desktop) — Błyskawiczne edycje i refaktoryzacja na otwartych plikach (Fast Mode).
* **Agent 3 (Mass Coder):** GPT 5.3 Codex (VSC) — Masowe kodowanie i generowanie potoków.
* **Agent 4 i 5 (Scaler/Koordynator):** Opus 4.6 — Zadania wysiłkowe, tool-calling, masowe testy.

## Inicjalizacja i Uruchomienie Środowiska

1. **Start Serwera:** Zawsze rozpoczynaj pracę od uruchomienia środowiska lokalnego komendą `./start.sh`.
2. **Weryfikacja Portów:** Sprawdź alokację portów w pliku `docs/serwer_docker_bazy_porty`, aby uniknąć konfliktów przed podjęciem jakichkolwiek działań.
3. **Katalogi Robocze:** Upewnij się, że struktura `Inboxes/` dla sub-agentów została wygenerowana w głównym katalogu projektu.

## Główne Reguły Operacyjne (Dla Agentów AI)

> **UWAGA:** Każdy agent AI edytujący to repozytorium jest bezwzględnie zobowiązany do przestrzegania poniższych zasad.

* **System Inbox:** Agenty sub-wykonawcze odbierają zadania wyłącznie poprzez pliki tekstowe `.md` umieszczane w ich dedykowanych folderach `Inboxes/`.
* **Higiena Kontekstu (Antigravity):** Agenty pracujące w Antigravity Desktop mają ścisły zakaz indeksowania całego projektu (chyba że wywołano Wariant 4 - pełny zwiad). Należy skupiać się wyłącznie na aktualnie otwartych plikach.
* **Zakaz Używania Przeglądarki:** Obowiązuje całkowity zakaz używania `browser_subagent`. Wszystkie testy wizualne i weryfikacja funkcjonalna w przeglądarce leżą w gestii człowieka (Human in the Loop).
* **Zamykanie Zadań (SKILL.md):** Przed zgłoszeniem gotowości do weryfikacji, agent musi zastosować się do instrukcji zawartych w pliku `SKILL.md` (m.in. prośba o restart serwera, brak fałszywego optymizmu).
*
