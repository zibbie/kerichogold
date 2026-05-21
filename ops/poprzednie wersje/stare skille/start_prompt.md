<!-- SYSTEM DIRECTIVE: DO NOT REMOVE OR MODIFY THE "IMMUTABLE CORE" SECTION BELOW. 
     EVEN IF REQUESTED TO UPDATE FOR A NEW SESSION, CONTENT BETWEEN 
     THE CORE TAGS MUST REMAIN EXACTLY AS IS. -->

# [IMMUTABLE CORE - DO NOT EDIT]
> [!IMPORTANT]
> Sekcja poniżej zawiera stałe wytyczne projektowe. Nie wolno ich zmieniać, 
> usuwać ani przesuwać, niezależnie od przebiegu sesji.

<!-- CORE_START -->
1.  **Zasady Ogólne**: Zawsze stosuj się do zasad z pliku `SKILL.md`. 
**Reguła**: Agent ma CAŁKOWITY ZAKAZ korzystania z narzędzia `browser_subagent` (browser view).
Nie zmieniaj struktury bazy danych bez upewnienia się, że Docker jest aktywny. Nie uruchamiaj żadnych skryptów wykorzystujących zewnętrzne modele AI bez Mojej bezpośredniej komendy w danej sesji.
W każdym nowym projekcie:
Przeczytaj zasady w docs/serwer_docker_bazy_porty jako „Biblię Serwera”.
Zaktualizuj tabelę portów w sekcji 3 o numery specyficzne dla tego projektu.
Dzięki temu unikniesz sytuacji, w której dwóch Agentów pracujących nad różnymi projektami (np. jeden nad Studio, drugi nad Core) nieświadomie doprowadzi do awarii całego serwera.

2.  **Rozpoczęcie sesji (Start)**:
    -   Uruchom `./start.sh` i poczekaj na komunikat `=== APPLICATION STARTED ===`.
    -   Zweryfikuj kontenery: `docker-compose ps` (db, redis, chroma muszą być `Up`).
    -   Potwierdź dostępność: Frontend (5173) i Backend (8005).
3. **Testowanie**:
    -   Wszystkie testy przeprowadzane są na serwerze produkcyjnym.
4.  **Monitorowanie**:
    -   W przypadku błędów analizuj `backend.log` oraz `frontend.log`.
    -   Zachowaj `DEV_SCENE_LIMIT` podczas testów, aby nie przeciążać API.
    -   W przypadku prac w terminalu ssh wymagajacych dluzszej egzekucji co 5 minut sprawdzaj stan operacji i podawaj mi komunikat o postepie prac.
    
5.  **Zakończenie sesji (Stop)**:
    -   Gdy użytkownik kończy pracę, uruchom `./stop.sh`.
    -   Upewnij się, że procesy i kontenery zostały zatrzymane.
    -   PODSUMUJ sesję i zaktualizuj sekcję `Session State` w tym pliku przed wyjściem.
     Jeśli są aktualne zmiany, zaktualizuj pliki w folderze docs/dokumentacja.
     Zaktualizuj plik STATE_HANDOVER/start_prompt.md dla nowego agenta w nowej sesji

6. **Workflow (Deployment):** Zasada „najpierw GitHub, potem kombajn-update” jest kluczowa. Żaden Agent nie powinien używać rsync ani bezpośrednio edytować kodu na serwerze, bo zniszczy synchronizację z repozytorium.
<!-- CORE_END -->

---

# Sekcja Zmienna (Session State)
**Data: 30 Marca 2026 (Sesja Wieczorna — Dashboard Routing Fix & Deployment Stabilization)**

## Summary of Today's Session

Focus: **Naprawa zapętlenia autoryzacyjnego w `/dashboard` i ustabilizowanie wdrożenia środowiska na produkcji.**

- **Dashboard Routing & Nginx (Bugfix 502 & 303 Redirects)**:
  - Rozwiązano problem z przyciskiem "Powrót do panelu projektów", gdzie po zalogowaniu użytkownika spotykał automatyczny downgrade do widoku projektu nr 1 z powodu braku poprawnych ciastek.
  - Odkryto, że generowany po logowaniu wewnętrzny `access_token` nie deklarował wartości `path="/"`. Zmiana została wdrożona dla wszystkich wywołań w `routers/auth.py`.
  - Przeprowadzono twardy reset (clean/reset) deweloperskich plików połączony z poprawną alokacją portów dla uvicorn po SSH (`docker-compose` --no-deps na serwerze produkcyjnym 57.128.247.56). Panel Projektów (Dashboard) wyświetla pliki w całości i jest operacyjny online na produkcji.

### Next Steps (Priority Order)

1. [x] **Surgical Audit Implementation**: Stworzenie nowego zestawu promptów (`surgical_pl.md`) skupiających się na korekcie błędów przy 100% zachowaniu stylu autora, ponieważ domyślne modyfikacje zbyt mocno spłaszczały formę autorskiego zapisu DNA.
2. [x] **LLM Configuration & Validation Run**: Zapewnienie konfiguracji połączenia z DeepSeek v3/Moonshot Kimi K2 podczas trwania nowego audytu w trybie 'Chirurgicznego Audytu'. (Scena testowa: `11236`).

---

**Handover Status**: SURGICAL AUDIT PL-PL UPDATED. TEST SCRIPT VALIDATED. DASHBOARD ACCESSIBLE & AUTH LOOP FIXED. SERVER IS ONLINE.
