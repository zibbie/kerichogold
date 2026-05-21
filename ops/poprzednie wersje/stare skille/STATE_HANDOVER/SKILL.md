---
name: Manual Verification (No Browser)
description: Strict rule banning the use of browser tools in the AI Detector project.
---
# ZASADA: ZAKAZ UŻYWANIA PRZEGLĄDARKI

**Reguła**: Agent ma CAŁKOWITY ZAKAZ korzystania z narzędzia `browser_subagent` (browser view) również w obrębie podprojektu Core (Zarówno przy budowie Backend-u jak i aplikacji mobilnej Expo).

**Podział ról**:

1. **Agent**: Zajmuje się wyłącznie architekturą, konfiguracją, kodowaniem (implementacja backendu i React Native) oraz testowaniem przez `curl` gdzie to możliwe. Realnie ocenia sytuację, pisze czysty kod i dokumentuje braki - nie udaje optymizmu po omacku.
2. **Użytkownik**: Sprawdza postępy w weryfikacji manualnej, tj. w mobilnym symulatorze (Expo Go / iOS Simulator) oraz poprzez testowe strzały REST z zewnętrznych narzędzi. Dostarcza spostrzeżeń.

**Instrukcja postępowania**:

- Po wykonaniu zmian w API (Backend) lub UI (Mobile), zgłoś gotowość do weryfikacji.
- **Pod żadnym pozorem nie uruchamiaj testu przeglądarkowego czy próby uruchomienia symulacji UI z poziomu Agenta.**
- Oczekuj na feedback od użytkownika.

**Zasada Contentu vs Designu**: Nigdy nie używaj tekstów z projektu graficznego (np. od Stitcha) jako wskazania do zmian merytorycznych na stronie. Projekty graficzne pokazują wyłącznie warstwę wizualną i estetyczną, a nie docelowy content. Treści na stronie (nazwy usług, opisy, itp.) mają pozostać zgodne z logiką biznesową projektu (np. nazwa "Core albo Studio itp"), a nie z placeholderami z makiet.

**Zasada "Studio Alpha" (Premium & Deep Analysis) Design System**:

- **Aesthetic**: Interfejs ma mieć charakter profesjonalny, premium i analityczny.
- **Terminologia**:
- **Zero Tolerance**:
- **Visuals**: Skup się na precyzji, danych i rzetelności (Evidence-based data, but NOT "Forensic evidence").
- **Typografia**: Nagłówki (`font-headline`) powinny być wyraziste (Manrope, black, tracking-tighter).
- **Obsidian Iconography**: Używaj fontu **Material Symbols Outlined** z systemem lokalnego hostingu.

**Zasada "Parallel browser tool calls remain banned"**:

- To techniczny status zgodności. Oznacza on, że agent świadomie przestrzega zakazu równoległego uruchamiania narzędzi przeglądarkowych (wymóg systemowy Antigravity), co jest spójne z nadrzędną zasadą **ZAKAZU UŻYWANIA PRZEGLĄDARKI** w tym projekcie.

## PROCEDURA RESTARTU & DEPLOYMENTU

Po wprowadzeniu zmian do logiki biznesowej lub infrastruktury UI:

1. **Backend**: `docker-compose restart backend` (lub uvicorn reload).
2. **Web (Frontend)**: Zawsze wykonuj **rebuild z czyszczeniem cache** (`docker compose build --no-cache frontend`) oraz restart z wymuszeniem podmiany kontenera (`docker compose up -d --force-recreate frontend`). Zapobiega to serwowaniu starych warstw obrazu.
3. **Mobile**: Wciśnięcie `r` w konsoli Expo.

## ARCHITEKTURA APLIKACJI
