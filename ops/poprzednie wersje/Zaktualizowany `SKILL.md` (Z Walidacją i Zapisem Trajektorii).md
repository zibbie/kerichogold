description: Procedura twardej weryfikacji i zapisu doświadczenia agenta po wywołaniu fraz "zweryfikuj", "gotowe" lub "przekaż dalej".
---

# ZASADA: WALIDACJA I ZAPIS WZORCA

## 1. Bramka Egzekucyjna (Pre-Handoff)
Przed poinformowaniem użytkownika o zakończeniu pracy, musisz:
1. Uruchomić dostępne narzędzia testowe/lintery w terminalu.
2. Jeśli testy nie przechodzą, nie zgłaszaj gotowości — napraw błędy w pętli wewnętrznej.
3. Dokonaj chłodnej oceny sytuacji: czy kod jest rzetelny, czy to tylko "naiwny optymizm".

## 2. Zapis Trajektorii (Pattern Storage)
Jeśli podczas pracy rozwiązałeś niestandardowy problem (np. błąd sieci Docker, trudna konfiguracja Nginx):
- Stwórz plik `.md` w `Knowledge_Graph/Patterns/`.
- Nazwij go precyzyjnie (np. `docker_ipv6_fix.md`).
- Opisz krótko: Problem -> Rozwiązanie -> Kod.

## 3. Procedura Restartu i Weryfikacji
- Poinformuj użytkownika o konieczności restartu serwera/kontenera (np. `docker-compose restart`).
- Całkowity zakaz używania `browser_subagent`. Oczekuj na feedback od człowieka po jego manualnej weryfikacji w przeglądarce.
