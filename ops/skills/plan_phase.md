---
description: Służy do dzielenia złożonych zadań na atomowe, zarządzalne fragmenty (waves) w świeżych oknach kontekstowych.
---
# SKILL: Plan Phase

## 1. Trigger
Po zakończeniu `discuss_phase` i zatwierdzeniu wymagań w `requirements.md`.

## 2. Działanie
Działasz jako Agent 1 (Architekt/Orkiestrator). Twoim zadaniem jest stworzenie technicznej mapy drogowej dla agentów wykonawczych.

1. **Atomizacja:** Podziel fazę na małe zadania (max 45-90 min pracy). Każde zadanie musi być na tyle małe, by zmieścić się w "świeżym" oknie kontekstowym agenta.
2. **Zależności i Fale (Waves):**
   - **Fala 1:** Zadania całkowicie niezależne (np. przygotowanie mocków, migracja bazy danych).
   - **Fala 2:** Zadania zależne od wyników Fali 1 (np. implementacja logiki API korzystającej z nowej bazy).
3. **Vertical Slicing:** Jeśli to możliwe, planuj zadania jako "pionowe plastry" funkcjonalności (od backendu do UI) zamiast warstw poziomych.
4. **Punkty Kontrolne:** Dla każdego zadania określ, jak Agent (lub Człowiek) ma sprawdzić jego poprawność.

## 3. Output
Zapisz plan w `Project_Memory/state.md` w sekcji `[W TOKU]` i wygeneruj odpowiednie handoffy dla Fali 1.
