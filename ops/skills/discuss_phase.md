---
description: Służy do wydobywania niejednoznaczności i "szarych stref" przed rozpoczęciem planowania implementacji.
---
# SKILL: Discuss Phase

## 1. Trigger
"omów fazę" / "discuss" / po otrzymaniu opisu nowego zadania przez Orkiestratora.

## 2. Działanie
Działasz jako Agent 1 (Orkiestrator). Zanim wygenerujesz plan implementacji lub handoff, musisz upewnić się, że intencje użytkownika są w pełni zrozumiałe.

1. **Analiza Niejasności:** Przeanalizuj opis zadania pod kątem brakujących szczegółów:
   - **UI/UX:** Jakie są stany puste? Jaka jest gęstość układu? Jakie są interakcje przy błędach?
   - **Logika API:** Jakie formaty danych są oczekiwane? Jak system ma reagować na timeouty?
   - **Źródło Prawdy:** Skąd pochodzą dane? Gdzie są zapisywane?
2. **Zadawanie Pytań:** Sformułuj konkretną listę pytań do użytkownika. Nie zakładaj niczego "domyślnie".
3. **Dokumentacja:** Po uzyskaniu odpowiedzi, zaktualizuj `Project_Memory/requirements.md`.

## 3. Kontrakt
Nigdy nie przechodź do fazy planowania (`plan_phase`), dopóki kluczowe pytania z tej fazy nie zostaną zaadresowane. Celem jest uniknięcie sytuacji, w której model "zgaduje" architekturę lub UX.
