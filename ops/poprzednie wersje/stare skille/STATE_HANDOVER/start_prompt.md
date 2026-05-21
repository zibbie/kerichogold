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
<!-- CORE_END -->

---

# Sekcja Zmienna (Session State)
**Data: 28 Marca 2026 (Sesja Poranna — Core Pro Integration & S2S Solidification)**

## Summary of Today's Session

Focus: **Solidyfikacja integracji "Core Pro", wsparcie dla diffów (LCS) oraz optymalizacja S2S API.**

- **S2S & Core Pro**:
  - Zaimplementowano brakujące endpointy S2S: `/linguistic-audit` oraz `/expert-panel` (wielomodelowa analiza ekspercka).
  - Dodano `tools/diff_utils.py` obsługujący mapowanie zmian (LCS) dla Humanizera.
  - Zintegrowano `diff_mapping` w odpowiedziach callback `/humanize`.
- **Raportowanie PDF**:
  - Zdiagnozowano przyczynę błędów formatowania w `core/backend/tools/pdf_generator.py` (brak twardych marginesów i problemy z `multi_cell`).
  - Przygotowano instrukcję poprawki dla Core (patrz `walkthrough.md` lub historia sesji).
- **Environment & Git**:
  - Ujednolicono porty (8005) w `.env` i `docker-compose.yml`.
  - Wszystkie zmiany (kod + dokumentacja) zostały wypchnięte do repozytorium (branch: `master`).

### Next Steps (Priority Order)

1. [ ] **Core PDF Fix**: Agent Core musi zaaplikować poprawkę marginesów i `auto_page_break` w swoim backendzie.
2. [ ] **End-to-End Test**: Test integracyjny nowych endpointów S2S pod kątem pełnej oferty "Core Pro".
3. [ ] **UX Check**: Weryfikacja wizualna diffów przesyłanych przez Studio na froncie Core.

---

**Handover Status**: COMPLETE ECOSYSTEM DOCUMENTED (25+ technical docs total). S2S API ALIGNED. SYSTEM RUNNING.
