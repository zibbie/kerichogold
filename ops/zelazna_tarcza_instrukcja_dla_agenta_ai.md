# Żelazna Tarcza (Ironclad Defense System) – Instrukcja dla Agentów AI

## Kontekst i Nadrzędna Dyrektywa
Jako agent AI pracujący z tym Użytkownikiem, musisz bezwzględnie przestrzegać najwyższych standardów bezpieczeństwa danych. Użytkownik posiada **zerową tolerancję** na utratę kodu, tekstów lub historii repozytorium z powodu "usprawnień" czy błędów automatyzacji. Zanim zaczniesz implementować nowe funkcjonalności w jakimkolwiek projekcie Użytkownika, **MUSISZ** upewnić się, że poniższe cztery warstwy Żelaznej Tarczy są zaimplementowane. Jeśli nie są – Twoim pierwszym zadaniem jest ich wdrożenie.

---

## Warstwa 1: Ochrona Bazy Danych (Brak usuwania danych)
Celem jest całkowite wyeliminowanie ryzyka fizycznego skasowania danych z bazy.

1. **Wdrożenie "Soft Deletes":** 
   - Zabrania się używania fizycznego usuwania rekordów (np. `db.delete(obj)` w SQLAlchemy, czy `DELETE FROM...` w SQL).
   - Wszystkie kluczowe tabele (projekty, dokumenty, posty, użytkownicy) muszą posiadać kolumnę `deleted_at` typu TIMESTAMP (domyślnie NULL).
   - Każda operacja "usuwania" ma jedynie aktualizować pole `deleted_at = NOW()`.
   - Zapytania pobierające dane (SELECT) muszą domyślnie odfiltrowywać usunięte rekordy (`WHERE deleted_at IS NULL`).
2. **Kopie zapasowe (Dumps):**
   - Skrypty uruchomieniowe (np. `start.sh`) lub demony muszą cyklicznie wykonywać lokalne zrzuty całej bazy danych (np. `pg_dump` lub `mysqldump`) do dedykowanego folderu `backups/`.

---

## Warstwa 2: Zabezpieczenie Historii Git (Ochrona przed nadpisywaniem)
Operacje na repozytoriach Git inicjowane przez AI lub z poziomu kodu aplikacji backendowych bywają destrukcyjne w przypadku konfliktów.

1. **Zakaz komend destrukcyjnych:** 
   - Bezwzględny zakaz używania flagi `--force` (lub `-f`) we wszystkich wywołaniach `git push`.
   - Bezwzględny zakaz używania `git reset --hard`.
2. **Ratunkowa gałąź (Pre-Pull Backup):**
   - Zanim skrypt lub AI wykona komendę `git pull` lub pobierze nową historię z repozytorium, MUSI automatycznie stworzyć tymczasową gałąź lokalną ze starym stanem.
   - **Wymagany kod przed pull:** `git branch backup-before-pull-$(date +%Y%m%d_%H%M%S)`
   - Dzięki temu, jeśli `pull` usunie kod lub zniszczy formatowanie, Użytkownik zawsze posiada w pełni działającą, zapasową gałąź ułamek sekundy sprzed błędu.

---

## Warstwa 3: Izolacja VPS (Suicide Switch)
Kod lokalny nigdy nie może przypadkowo połączyć się z produkcyjnymi serwerami VPS i usunąć tamtejszych danych.

1. **Hardkodowana Blokada Sieciowa:**
   - W module łączącym aplikację z bazą danych (np. `auth_manager.py`, `database.php`) zaimplementuj sprawdzanie wczytanego adresu (np. `DATABASE_URL`).
   - Stwórz "czarną listę" produkcyjnych słów kluczowych i publicznych IP (np. `["kerichogold", "vps", "195.", "10."]`).
   - Stwórz "białą listę" (Whitelist) akceptowanych lokalnych hostów: `["localhost", "127.0.0.1", "db", "postgres"]`.
   - Jeśli adres bazy nie jest na białej liście lub zawiera słowa z czarnej listy, system MUSI rzucić wyjątek (np. `RuntimeError("Suicide Switch Activated")`) i natychmiast zatrzymać aplikację.
2. **Kwarantanna:** Plik `.env` w środowisku lokalnym musi zostać bezwzględnie wyczyszczony z produkcyjnych poświadczeń serwerów VPS.

---

## Warstwa 4: Chmura Google Drive (Anti-AI Airgap)
Rozwiązanie typu "Airgap" zapewniające, że nawet w przypadku katastrofalnego błędu w halucynacjach AI, fizyczne kopie zapasowe projektu pozostaną poza zasięgiem agenta i będą wysyłane poza lokalny dysk.

1. **Oddzielny Demon (cloud_guard.py):**
   - Napisz miniaturowy, działający w tle skrypt Python, który jest CAŁKOWICIE ODSEPAROWANY od głównej aplikacji.
   - Demon ma za zadanie co 15 minut skanować główny katalog projektów. Jeśli wykryje, że pliki zostały zmodyfikowane w ciągu ostatnich 15 minut, pakuje cały katalog aktywnego projektu do pliku `.zip`.
2. **Izolacja Kluczy:**
   - Do wysyłania paczek ZIP demon musi używać Google Cloud Service Account (poprzez `google-api-python-client`).
   - **KRYTYCZNE:** Klucz JSON konta usługowego MUSI znajdować się poza katalogiem projektu (np. w `~/.secrets/studio_backup_key.json`), w lokalizacji, do której Ty (Agent AI) **nie masz dostępu i o którą nie pytasz**. Dzięki temu, agent AI pracujący w trybie sandboxingu wewnątrz projektu nie posiada "klucza" do usunięcia plików z Google Drive.
3. **Upload (Uploader/Append-Only):**
   - Konto usługowe na Dysku Google powołane dla Demona powinno mieć dostęp wyłącznie do udostępnionego mu foldera backupowego.

---

## Warstwa 5: Procedura Wdrożeniowa (Staging First)
Bezwzględny zakaz wdrażania poprawek bezpośrednio na produkcję bez uprzedniej weryfikacji na środowisku stagingowym.

1. **Staging First:** Każda zmiana kodu, konfiguracji lub bazy danych musi zostać najpierw wdrożona i przetestowana na serwerze stagingowym (np. `sklep2.kerichogold.com.pl` lub kontener `kericho-staging-app`).
2. **Human Approval:** Wdrożenie na produkcję (`kericho-app`, `kericho-web`, `sklep.kerichogold.com.pl`) może nastąpić WYŁĄCZNIE po wyraźnym, tekstowym potwierdzeniu przez Użytkownika, że zmiana na stagingu działa poprawnie i jest gotowa do publikacji.
3. **Frontend & Assets Stability (Tailwind v4):**
   - **Relative Paths:** Bezwzględny zakaz używania ścieżek absolutnych (np. `/var/www/html/...`) w dyrektywie `@source` w pliku `app.css`. Wszystkie ścieżki muszą być relatywne (np. `../../resources/views/...`), aby działały na każdym środowisku.
   - **Mandatory Build Trigger:** Po każdej zmianie w widokach Blade, plikach JS lub CSS na stagingu, agent MUSI wymusić proces budowania assetów (`npm run build`). 
   - **Docker Permissions:** W przypadku błędów `EACCES` podczas budowania w kontenerze, agent ma obowiązek użyć flagi `-u root` (np. `docker exec -u root staging-app npm run build`), aby zapewnić ciągłość operacji.
   - **Visual Smoke Test:** Po wdrożeniu na staging, agent musi poprosić Użytkownika o weryfikację "rozsypki" (Horizontal Overflow) przed dalszymi pracami.

---

> Jako Agent AI, potwierdzam że przyjmuję te wytyczne do wiadomości. W przypadku jakichkolwiek wątpliwości, bezpieczeństwo DANYCH UŻYTKOWNIKA traktuję priorytetowo przed realizacją jakichkolwiek nowych funkcjonalności.
