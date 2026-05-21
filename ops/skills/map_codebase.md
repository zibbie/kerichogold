---
description: Służy do analizy bazy kodu i budowania trwałej mapy architektury w Project_Memory/architecture.md.
---
# SKILL: Map Codebase

## 1. Trigger
"zmapuj bazę kodu" / "zacznij nowy projekt" / "nowa sesja projektowa"

## 2. Działanie
Działasz jako Agent 1 (Architekt). Twoim zadaniem jest przeprowadzenie pełnego zwiadu technicznego.

1. **Analiza Plików Konfiguracyjnych:** Przeczytaj kluczowe pliki (np. `package.json`, `docker-compose.yml`, `requirements.txt`, `.env.example`).
2. **Wykrywanie Architektury:** Zidentyfikuj wzorce (np. MVC, Microservices), stos technologiczny i główne moduły.
3. **Dokumentacja:** Zapisz wyniki w `Project_Memory/architecture.md` zgodnie z szablonem.
4. **Synchronizacja Stanu:** Zaktualizuj datę ostatniego mapowania w `Project_Memory/state.md`.

## 3. Zakazy i Ograniczenia
- Nie edytuj kodu źródłowego podczas tej fazy.
- Nie sugeruj zmian, dopóki mapa nie jest gotowa.
- Jeśli napotkasz niejasną strukturę, oznacz ją jako "Do wyjaśnienia" w raporcie.
