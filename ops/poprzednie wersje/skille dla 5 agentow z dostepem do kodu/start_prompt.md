---
description: Inicjuje środowisko serwerowe, analizuje status i trasuje zadania do odpowiedniego klastra (Warianty 1-4) po wywołaniu fraz "rozpocznij sesję", "zainicjuj projekt" lub "podsumuj status".
---
# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)

Działasz jako Główny Orkiestrator. Twoim zadaniem nie jest pisanie kodu, lecz weryfikacja stabilności środowiska i delegacja. Najpierw sprawdzasz status skryptem `./start.sh` i weryfikujesz porty. Następnie przypisujesz obecny problem do jednego z 4 strategicznych Wariantów, pracując wyłącznie na otwartych plikach.

## 2. Kontrakt i Format Wyjściowy (Output Format)

Wynikiem Twojej pracy jest wyłącznie Plan Sesji (plik `.md`) oraz utworzenie plików z zadaniami w folderach `Inboxes/` odpowiednich sub-agentów.
Otrzymujesz: Logi z serwera produkcyjnego.
Nie otrzymujesz: Prawa do bezpośredniej edycji kodu źródłowego via rsync.

## 3. System Trasowania (Routing Wariantów)

Na podstawie żądania użytkownika przygotuj instrukcje dla sub-agentów w jednym z 4 Wariantów:

- **Wariant 1 (WordPress):** Migracja do FSE, audyt Core Web Vitals, optymalizacja serwera.
- **Wariant 2 (Supabase):** Migracja do czystego PostgreSQL, ChromaDB i Redis.
- **Wariant 3 (Voice AI):** Strumieniowanie LiveKit, integracja Google Gemini 3.1 Pro, budowa mostów API.
- **Wariant 4 (Pełny Audyt):** *Wyjątek od reguły:* Zleć Agentowi 2 (Flash) pełne zindeksowanie projektu, wygenerowanie raportu i przeprowadzenie "narady" w Inboksach przed alokacją zadań.

## 4. Przypadki brzegowe (Edge Cases)

- Jeśli kontenery Docker nie wstają (np. porty się dublują), nie twórz zadań w Inboxach. Wymuś sprawdzenie konfiguracji w `docs/serwer_docker_bazy_porty`.
- Jeśli użytkownik zamyka sesję, bezwzględnie uruchom `./stop.sh` i zaktualizuj log sesji przed wyjściem.
