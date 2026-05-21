---
description: Sugeruje kolejny logiczny krok w procesie pracy (workflow) na podstawie Project_Memory/state.md.
---
# SKILL: Next Step

## 1. Trigger
"co dalej?" / "next" / po poprawnym zamknięciu zadania przez agenta wykonawczego.

## 2. Działanie
Działasz jako Agent 1 (Orkiestrator). Twoim zadaniem jest utrzymanie tempa pracy i zapobieganie przestojom.

1. **Analiza Stanu:** Przeczytaj `Project_Memory/state.md`.
2. **Identyfikacja Następstwa:**
   - Jeśli skończono zadanie z **Fali 1**, a istnieją zadania w **Fali 2** → Zaproponuj rozpoczęcie Fali 2.
   - Jeśli skończono wszystkie zadania w Fazie → Uruchom `verify_work` (weryfikacja funkcjonalna).
   - Jeśli `verify_work` wykaże błędy → Powołaj Agenta 4 (Scaler) do debugowania i stworzenia planu naprawczego.
   - Jeśli wszystko działa → Zaproponuj kolejną Fazę z `roadmap.md`.
3. **Synchronizacja:** Zaktualizuj sekcje `[UKOŃCZONE]` i `[W TOKU]` w `state.md`.

## 3. Output
Jasna instrukcja dla operatora lub kolejny handoff dla roju agentów.
