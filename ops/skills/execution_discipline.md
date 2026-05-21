---
description: Wymusza rygorystyczne zasady TDD, izolacji pracy (worktrees) oraz atomowych commitów podczas egzekucji zadań.
---
# SKILL: Execution Discipline

## 1. Trigger
Przed rozpoczęciem implementacji zadania technicznego z handoffu.

## 2. Checklist Przed Kodowaniem (Red Phase)
Zanim zaczniesz zmieniać kod źródłowy:
1. **Izolacja:** Czy zadanie modyfikuje >3 pliki? Jeśli tak, stwórz `git worktree`.
2. **TDD:** Jeśli zadanie dodaje nową logikę, napisz najpierw test (Red Phase). Upewnij się, że test zawodzi przed Twoimi zmianami.
3. **Kontekst:** Przeczytaj `Project_Memory/architecture.md`, aby uniknąć łamania istniejących konwencji.

## 3. Podczas Kodowania (Green Phase)
1. **Atomowość:** Skup się wyłącznie na JEDNYM pod-zadaniu z planu.
2. **Commity:** Po zakończeniu każdego pod-zadania wykonaj atomowy commit:
   `git commit -m "task-[ID]: [krótki opis zmiany]"`
3. **Pionowe Cięcie:** Jeśli pracujesz nad "Vertical Slice", implementuj od razu backend i frontend dla danej małej funkcji.

## 4. Checklist Po Kodowaniu (Verify Phase)
1. **Bramka Egzekucyjna:** Uruchom komendę walidacji przypisaną do Twojego agenta (np. `npm run lint` lub `npm run test`).
2. **Kontrakt:** Przygotuj plik handoff w `Inboxes/` zgodnie z `SKILL.md`.
3. **Patterns:** Jeśli rozwiązanie błędu było nieszablonowe, zasygnalizuj potrzebę stworzenia nowego wzorca w `Patterns/`.
4. **Staging First:** Bezwzględny wymóg wdrożenia na **Staging** przed jakąkolwiek próbą publikacji na produkcję. Produkcja wymaga potwierdzenia człowieka.
