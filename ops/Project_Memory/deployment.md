# Server Configuration & Deployment Guide

Ten dokument opisuje architekturę wdrożeniową, podział na środowiska produkcyjne/stagingowe, strukturę kontenerów Docker oraz pełną procedurę deploymentu sklepu **Nevro-Shop v2**.

---

## 1. Informacje Ogólne (Server Overview)

*   **System Operacyjny VPS:** Debian 12 (Bookworm)
*   **Adres IP Serwera:** `212.227.75.28`
*   **Użytkownik SSH:** `root`
*   **Repozytorium Git:** `https://github.com/zibbie/nevro-shop-v2.git`
*   **Metoda Konteneryzacji:** Docker & Docker Compose

---

## 2. Podział na Środowiska (Environments)

Sklep działa w oparciu o dwa w pełni odizolowane środowiska uruchomione na tym samym serwerze VPS za pomocą wspólnej sieci Docker (`www_v2-network`).

### A. Środowisko Produkcyjne (Production)
*   **Domena główna:** [https://nevro-wm.pl/](https://nevro-wm.pl/)
*   **Gałąź Git:** `master`
*   **Katalog na serwerze:** `/var/www`
*   **Baza danych:** `nevr0_v2` (PostgreSQL)
*   **Dedykowane Kontenery Docker:**
    *   `v2-app` (Aplikacja PHP/Laravel)
    *   `v2-queue` (Kolejki Laravel Queue Worker)
    *   `v2-web` (Serwer Nginx serwujący statyczne pliki)
    *   `v2-db` (Główna baza PostgreSQL 15 - współdzielona)
    *   `v2-redis` (Cache & Queue Broker - współdzielony)
    *   `v2-meilisearch` (Wyszukiwarka Meilisearch - współdzielona)
    *   `v2-proxy` (Nginx Proxy Manager - obsługa SSL i routingu domen)

### B. Środowisko Stagingowe (Staging)
*   **Domena testowa:** [https://shop.nevro-wm.pl/](https://shop.nevro-wm.pl/)
*   **Gałąź Git:** `staging`
*   **Katalog na serwerze:** `/var/www/staging`
*   **Baza danych:** `nevro_staging` (PostgreSQL uruchomiona na kontenerze `v2-db`)
*   **Dedykowane Kontenery Docker:**
    *   `staging-app` (Aplikacja PHP/Laravel dla wersji testowej)
    *   `staging-queue` (Kolejki Laravel Queue Worker dla wersji testowej)
    *   `staging-web` (Serwer Nginx dla wersji testowej)
*   *Uwaga:* Staging nie duplikuje baz danych ani serwerów proxy. Kontenery stagingowe są wpięte do sieci produkcyjnej `www_v2-network` jako sieć zewnętrzna i korzystają ze wspólnych instancji PostgreSQL (`v2-db`), Redis (`v2-redis`) oraz Nginx Proxy Manager (`v2-proxy`).

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
    ssh root@212.227.75.28
    ```
3.  Przejdź do katalogu stagingu i pobierz zmiany:
    ```bash
    cd /var/www/staging
    git pull origin staging
    ```
4.  Zastosuj aktualizacje wewnątrz kontenerów stagingowych:
    ```bash
    # Aktualizacja zależności composer (jeśli zmienił się composer.lock)
    docker exec staging-app composer install --no-dev --optimize-autoloader

    # Uruchomienie migracji bazy danych
    docker exec staging-app php artisan migrate --force

    # Wyczyszczenie i optymalizacja pamięci podręcznej
    docker exec staging-app php artisan optimize:clear
    ```
5.  Uruchom testy automatyczne na stagingu:
    ```bash
    docker exec staging-app php artisan test
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
    ssh root@212.227.75.28
    ```
3.  Przejdź do katalogu produkcyjnego i pobierz zmiany:
    ```bash
    cd /var/www
    git pull origin master
    ```
4.  Zastosuj aktualizacje w kontenerach produkcyjnych:
    ```bash
    # Aktualizacja zależności (jeśli to konieczne)
    docker exec v2-app composer install --no-dev --optimize-autoloader

    # Uruchomienie migracji
    docker exec v2-app php artisan migrate --force

    # Optymalizacja konfiguracji i wyczyszczenie cache
    docker exec v2-app php artisan optimize:clear
    docker exec v2-app php artisan filament:optimize
    ```
5.  Uruchom testy jednostkowe i integracyjne:
    ```bash
    docker exec v2-app php artisan test
    ```

---

## 4. Narzędzia i Przydatne Komendy (Utilities)

### Tworzenie kopii zapasowej bazy danych (Backup)
Wykonaj zrzut bazy produkcyjnej bezpośrednio z kontenera PostgreSQL:
```bash
ssh root@212.227.75.28 "docker exec v2-db pg_dump -U nevro nevr0_v2 > /var/www/production_db_backup_$(date +%F).sql"
```

### Pobranie bazy i plików do środowiska lokalnego
Z komputera lokalnego (po wykonaniu backupu na VPS):
```bash
# Rsync bazy do lokalnego katalogu backups/
rsync -avz root@212.227.75.28:/var/www/production_db_backup_*.sql ./backups/

# Rsync plików z pamięci publicznej (zdjęcia produktów)
rsync -avz root@212.227.75.28:/var/www/storage/app/public/ ./storage/app/public/
```

### Podgląd logów aplikacji w czasie rzeczywistym
```bash
# Logi Laravela (Produkcja)
tail -f /var/www/storage/logs/laravel.log

# Logi Laravela (Staging)
tail -f /var/www/staging/storage/logs/laravel.log

# Logi kontenerów Dockera
docker logs --tail 50 -f v2-queue
docker logs --tail 50 -f staging-queue
```
