# Instrukcja Pracy z Orchestratorem i Agentami (Nevro-Shop v2)

Niniejszy przewodnik opisuje krok po kroku workflow pracy nad nowoczesnym sklepem opartym na Laravel w modelu hybrydowym.

## 1. Topologia Systemu
- **Orchestrator**: Twój główny rozmówca, który "widzi" serwer produkcyjny VPS i zarządza stackiem Docker (v2-app, v2-web).
- **Agenci Wykonawczy**: Pomocnicy pracujący na lokalnych plikach w `/Volumes/Third/Users/zbyszek/nevro-shop-v2`.

## 2. Cykl Pracy (Step-by-Step)

### Krok 1: Zgłoszenie Problemu
Opisz problem Orchestratorowi. On sprawdzi logi kontenerów na VPS i zdiagnozuje przyczynę błędu.

### Krok 2: Delegacja Zadania
Orchestrator przekaże instrukcje Agentowi Wykonawczemu. Agent ten poprawi odpowiednie pliki w Twoim lokalnym folderze roboczym `nevro-shop-v2`.

### Krok 3: Walidacja Lokalna
Agent Wykonawczy sprawdzi składnię plików (`php artisan coach:lint`) i zamelduje gotowość.

### Krok 4: Synchronizacja z GitHub (Automatyczna)
Po zakończeniu prac i lokalnej walidacji:
1. Agent Wykonawczy wykonuje `git commit` i `git push` do repozytorium `zibbie/nevro-shop-v2`.
2. Agent informuje Orkiestratora o wypchnięciu zmian.

### Krok 5: Deployment i Weryfikacja (Orkiestrator)
Orchestrator przejmuje stery:
1. Łączy się przez SSH z VPS.
2. Wykonuje `git pull` w katalogu `/var/www/nevro-shop-v2`.
3. Uruchamia migracje lub czyści cache (`php artisan migrate`, `php artisan view:clear`).
4. Sprawdza logi produkcyjne i potwierdza rozwiązanie.

## 3. Kluczowe Narzędzia
- `php artisan coach:lint`: Lokalne narzędzie do sprawdzania jakości kodu.
- `orchestrator/Knowledge_Graph/Patterns/`: Baza wiedzy o specyficznych rozwiązaniach dla Laravel/v2.

## 4. Dane Dostępu
Wszystkie wrażliwe dane (SSH, DB, GitHub) znajdują się w pliku `orchestrator/docs/vps_context.md`.
