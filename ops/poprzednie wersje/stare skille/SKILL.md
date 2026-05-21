---
name: Manual Verification (No Browser)
description: Strict rule banning the use of browser tools.
---
# ZASADA: ZAKAZ UŻYWANIA PRZEGLĄDARKI

**Reguła**: Agent ma CAŁKOWITY ZAKAZ korzystania z narzędzia `browser_subagent` (browser view).

**Podział ról**:

1. **Agent**: Zajmuje się wyłącznie kodowaniem (implementacja, poprawki, refaktoryzacja). Nie zapewnia uzytkownika za kazdym razem, ze teraz to juz absolutnie na sto procent wszystko dziala tylko realnie ocenia systuacje i przedstawia ja uzytkownikowi, nie niszczac swojej reputacji naiwnym optymizmem.
2. **Użytkownik**: Sprawdza efekty kodowania, weryfikuje zmiany wizualne i funkcjonalne w przeglądarce, oraz dostarcza spostrzeżenia i uwagi.

**Instrukcja postępowania**:

- Po wykonaniu zmian w kodzie, zgłoś gotowość do weryfikacji przez użytkownika.
- Nie próbuj uruchamiać subagenta przeglądarkowego.
- Po wykonaniu zmian w API (Backend) lub UI (Mobile), zgłoś gotowość do weryfikacji.
- **Pod żadnym pozorem nie uruchamiaj testu przeglądarkowego czy próby uruchomienia symulacji UI z poziomu Agenta.**
- Oczekuj na feedback od użytkownika.

## PROCEDURA RESTARTU

Po wprowadzeniu zmian w kodzie (szczególnie w `app.py` lub szablonach HTML/JS) oraz przekazaniu ich do weryfikacji:

1. Poinformuj użytkownika o konieczności restartu serwera/kontenera Docker.
2. Jeśli masz uprawnienia, spróbuj wykonać restart samodzielnie (np. `docker-compose restart`).
3. Dopiero po potwierdzeniu restartu przez system lub użytkownika, poproś użytkownika, by sam przeprowadził weryfikację w przeglądarce.

## ARCHITEKTURA NARZĘDZI AI

**Wymóg wizualny i funkcjonalny**:

- Każde narzędzie wymagające dostępu do API (LLM) MUSI posiadać w górnym pasku nagłówka (header) mechanizm wyboru modelu i dostawcy (LLM Selector).

## DIAGNOSTYKA I STABILNOŚĆ S2S

**Procedura przy błędach połączenia (OperationalError)**:

1. Przed uznaniem bazy danych za uszkodzoną, sprawdź stan sieci Docker (`docker network inspect`).
2. Zweryfikuj, czy restarty nie są powiązane z procesami certyfikacji (Certbot) lub bridge'owaniem sieci na serwerze (kernel logs: `veth` changes).
3. W środowisku lokalnym (OrbStack/Docker Desktop) preferuj `127.0.0.1` nad `localhost` w plikach `.env`, aby uniknąć problemów z resolucją IPv6.
