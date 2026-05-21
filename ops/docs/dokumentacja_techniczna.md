### Dokumentacja Techniczna: Klastrowe Zarządzanie Projektami AI

#### 1. Architektura Systemu

* **Agent Centralny (Orkiestrator):** Rola przypisana do Gemini 3.1 Pro działającego w przeglądarce. Analizuje logi, ustala strategię i deleguje zadania, nie edytując kodu bezpośrednio.
* **Agenty Wykonawcze:** Wyspecjalizowane jednostki uruchamiane w edytorach (VSC, Antigravity, CLI) rozwiązujące czyste podproblemy. Należą do nich m.in. modele do szybkiego refaktoru (Gemini Flash), masowego kodowania (GPT 5.3 Codex) oraz zadań wysiłkowych (Opus 4.6).
* **Human in the Loop (Most Komunikacyjny):** Użytkownik ręcznie kopiuje zadania od Orkiestratora do lokalnych folderów, weryfikuje efekty pracy w przeglądarce i zatwierdza kod (merge).

#### 2. Cykl Życia Zadania (System Inbox)

* **Task Splitting:** Złożone zadania są dzielone na wąskie klastry i oznaczane identyfikatorami (np. B1 dla backendu, F1 dla frontendu).
* **Delegacja (Handoff):** Orkiestrator generuje instrukcje w formacie Markdown.
* **Asynchroniczność:** Instrukcje trafiają do przypisanych folderów `Inboxes/` poszczególnych agentów. Agent po starcie czyta zadanie wyłącznie ze swojego folderu.

#### 3. Trasowanie Zadań (Routing)

* **Problemy Wnioskowania:** Złożona logika i architektura kierowana jest do modeli typu "czysty wnioskowacz" (Gemini 3.1 Pro) z włączonym maksymalnym poziomem rozumowania. Pracują one w trybie `Planning Mode`.
* **Problemy Szybkościowe:** Szybkie edycje, style czy poprawki lintera realizowane są w trybie `Fast Mode` przez mniejsze, szybsze modele.
* **Problemy Wysiłkowe/Koordynacyjne:** Skalowanie testów lub audyty przydzielane są do agentów zoptymalizowanych pod tool-calling i utrzymanie długiego kontekstu (Opus 4.6).

#### 4. Rygorystyczne Reguły Operacyjne

* **Higiena Kontekstu:** W środowisku Antigravity Desktop agenty mają bezwzględny zakaz indeksowania całego projektu. Analizują wyłącznie otwarte pliki robocze. Wyjątkiem jest jedynie faza pełnego zwiadu (Wariant 4).
* **Zakaz Używania Przeglądarki:** Żaden agent nie może korzystać z narzędzia `browser_subagent` w celu symulacji UI. Weryfikacja wizualna jest wyłączną domeną użytkownika.
* **Zamykanie Zadania (Kontrakt):** Przed uznaniem zadania za gotowe, każdy sub-agent musi zastosować instrukcje z pliku `SKILL.md`. Polega to na obiektywnej weryfikacji logiki, poproszeniu o manualny restart serwera oraz przekazaniu statusu i diffów z powrotem do człowieka bez fałszywego optymizmu.
