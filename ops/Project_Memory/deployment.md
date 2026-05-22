# Server Configuration & Deployment Guide

Ten dokument opisuje architekturę wdrożeniową, podział na środowiska produkcyjne/stagingowe, strukturę kontenerów Docker oraz pełną procedurę deploymentu sklepu **Kericho Gold**.

---

## 1. Informacje Ogólne (Server Overview)

*   **System Operacyjny VPS:** Debian 13 (Trixie)
*   **Adres IP Serwera:** `85.215.169.120`
*   **Użytkownik SSH:** `root`
*   **Repozytorium Git:** `https://github.com/zibbie/kerichogold.git`
*   **Metoda Konteneryzacji:** Docker & Docker Compose

---

## 2. Podział na Środowiska (Environments)

Sklep działa w oparciu o dwa w pełni odizolowane środowiska uruchomione na tym samym serwerze VPS za pomocą wspólnej sieci Docker (`kericho-network`).

### A. Środowisko Produkcyjne (Production)
*   **Domena główna:** [https://sklep.kerichogold.com.pl/](https://sklep.kerichogold.com.pl/)
*   **Gałąź Git:** `master`
*   **Katalog na serwerze:** `/var/www/kerichogold`
*   **Baza danych:** `kericho_prod` (PostgreSQL)
*   **Dedykowane Kontenery Docker:**
    *   `kericho-app` (Aplikacja PHP/Laravel)
    *   `kericho-queue` (Kolejki Laravel Queue Worker)
    *   `kericho-web` (Serwer Nginx serwujący statyczne pliki)
    *   `kericho-db` (Główna baza PostgreSQL 15 - współdzielona)
    *   `kericho-redis` (Cache & Queue Broker - współdzielony)
    *   `kericho-meilisearch` (Wyszukiwarka Meilisearch - współdzielona)
    *   `kericho-proxy` (Nginx Proxy Manager - obsługa SSL i routingu domen)

### B. Środowisko Stagingowe (Staging)
*   **Domena testowa:** [https://sklep2.kerichogold.com.pl/](https://sklep2.kerichogold.com.pl/)
*   **Gałąź Git:** `staging`
*   **Katalog na serwerze:** `/var/www/kerichogold_staging`
*   **Baza danych:** `kericho_staging` (PostgreSQL uruchomiona na kontenerze `kericho-db`)
*   **Dedykowane Kontenery Docker:**
    *   `kericho-staging-app` (Aplikacja PHP/Laravel dla wersji testowej)
    *   `kericho-staging-queue` (Kolejki Laravel Queue Worker dla wersji testowej)
    *   `kericho-staging-web` (Serwer Nginx dla wersji testowej)
*   *Uwaga:* Staging nie duplikuje baz danych ani serwerów proxy. Kontenery stagingowe są wpięte do sieci produkcyjnej `kericho-network` i korzystają ze wspólnych instancji PostgreSQL (`kericho-db`), Redis (`kericho-redis`) oraz Nginx Proxy Manager (`kericho-proxy`).

---

## 3. Procedura Deploymentu (Deployment Procedure)

Zawsze najpierw wdrażaj i testuj zmiany na **Stagingu**, a po akceptacji klienta przenoś je na **Produkcję**.

### Krok 1: Wdrożenie na Staging

1.  Zacommituj i wyślij zmiany na gałąź `staging` w swoim lokalnym repozytorium:
    ```bash
    git checkout staging
    git add .
    git commit -m "feat/fix: opis zmian"
    git push origin staging
    ```
2.  Zaloguj się na serwer przez SSH:
    ```bash
    ssh -i ~/.ssh/id_ed25519 root@85.215.169.120
    ```
3.  Przejdź do katalogu stagingu i pobierz zmiany:
    ```bash
    cd /var/www/kerichogold_staging
    git pull origin staging
    ```
4.  Zastosuj aktualizacje wewnątrz kontenerów stagingowych:
    ```bash
    # Aktualizacja zależności composer (jeśli zmienił się composer.lock)
    docker exec kericho-staging-app composer install --no-dev --optimize-autoloader

    # Uruchomienie migracji bazy danych
    docker exec kericho-staging-app php artisan migrate --force

    # Wyczyszczenie i optymalizacja pamięci podręcznej
    docker exec kericho-staging-app php artisan optimize:clear
    ```
5.  Uruchom testy automatyczne na stagingu:
    ```bash
    docker exec kericho-staging-app php artisan test:tpay-security
    ```

---

### Krok 2: Wdrożenie na Produkcję

Gdy zmiany na stagingu przejdą pomyślnie testy i zostaną zaakceptowane:

1.  Złącz zmiany do gałęzi `master` i wyślij na serwer Git:
    ```bash
    git checkout master
    git merge staging
    git push origin master
    ```
2.  Zaloguj się na serwer przez SSH:
    ```bash
    ssh -i ~/.ssh/id_ed25519 root@85.215.169.120
    ```
3.  Przejdź do katalogu produkcyjnego i pobierz zmiany:
    ```bash
    cd /var/www/kerichogold
    git pull origin master
    ```
4.  Zastosuj aktualizacje w kontenerach produkcyjnych:
    ```bash
    # Aktualizacja zależności (jeśli to konieczne)
    docker exec kericho-app composer install --no-dev --optimize-autoloader

    # Uruchomienie migracji
    docker exec kericho-app php artisan migrate --force

    # Optymalizacja konfiguracji i wyczyszczenie cache
    docker exec kericho-app php artisan optimize:clear
    // Wdrożenie cache panelu admina
    docker exec kericho-app php artisan filament:optimize
    ```
5.  Uruchom testy jednostkowe i integracyjne:
    ```bash
    docker exec kericho-app php artisan test:tpay-security
    ```

---

## 4. Narzędzia i Przydatne Komendy (Utilities)

### Tworzenie kopii zapasowej bazy danych (Backup)
Wykonaj zrzut bazy produkcyjnej bezpośrednio z kontenera PostgreSQL:
```bash
ssh -i ~/.ssh/id_ed25519 root@85.215.169.120 "docker exec kericho-db pg_dump -U kericho kericho_prod > /var/www/production_db_backup_\$(date +%F).sql"
```

### Pobranie bazy i plików do środowiska lokalnego
Z komputera lokalnego (po wykonaniu backupu na VPS):
```bash
# Rsync bazy do lokalnego katalogu backups/
rsync -avz -e "ssh -i ~/.ssh/id_ed25519" root@85.215.169.120:/var/www/production_db_backup_*.sql ./backups/

# Rsync plików z pamięci publicznej (zdjęcia produktów)
rsync -avz -e "ssh -i ~/.ssh/id_ed25519" root@85.215.169.120:/var/www/kerichogold/storage/app/public/ ./storage/app/public/
```

### Podgląd logów aplikacji w czasie rzeczywistym
```bash
# Logi Laravela (Produkcja)
tail -f /var/www/kerichogold/storage/logs/laravel.log

# Logi Laravela (Staging)
tail -f /var/www/kerichogold_staging/storage/logs/laravel.log

# Logi kontenerów Dockera
docker logs --tail 50 -f kericho-queue
docker logs --tail 50 -f kericho-staging-queue
```
