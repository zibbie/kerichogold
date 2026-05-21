# Dokumentacja Konfiguracji Serwera VPS — Klonowanie Środowiska (Debian 13)

Ten dokument stanowi kompletny przewodnik konfiguracyjny serwera VPS dla dwóch nowych instancji sklepu **Kericho Gold**:
1.  **sklep.kerichogold.com.pl** (Środowisko Produkcyjne)
2.  **sklep2.kerichogold.com.pl** (Środowisko Stagingowe)

Konfiguracja bazuje na sprawdzonych rozwiązaniach z serwera Nevro-Shop (`212.227.75.28`), dostosowanych do systemu **Debian 13 (Trixie)**.

---

## 1. Wymagane Pakiety Systemowe i Zabezpieczenia (OS & Security)

### Aktualizacja systemu i narzędzi:
```bash
apt update && apt upgrade -y
apt install -y curl git unzip zip ufw ca-certificates gnupg
```

### Konfiguracja Firewalla (UFW):
```bash
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

### Instalacja Dockera (dla Debian 13):
```bash
# Dodanie oficjalnego klucza GPG Dockera
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg

# Dodanie repozytorium do źródeł APT (Debian 13 / trixie)
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
  trixie stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null

apt update
apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

---

## 2. Struktura Katalogów i Uprawnienia (Directory Layout)

Wszystkie aplikacje umieszczamy w katalogu `/var/www/` na serwerze:
*   `/var/www/kerichogold` — Katalog wersji produkcyjnej (`master` branch).
*   `/var/www/kerichogold_staging` — Katalog wersji stagingowej (`staging` branch).

### Przygotowanie folderów i pobranie kodu z repozytorium Git:
Wygeneruj klucz SSH dla Deploy Key na GitHubie:
```bash
ssh-keygen -t ed25519 -C "vps-kericho-gold"
cat ~/.ssh/id_ed25519.pub # Skopiuj i dodaj na GitHubie jako Deploy Key z prawami odczytu
```

Sklonuj repozytoria do odpowiednich folderów:
```bash
mkdir -p /var/www
cd /var/www
git clone git@github.com:zibbie/kerichogold.git kerichogold
git clone -b staging git@github.com:zibbie/kerichogold.git kerichogold_staging
```

Ustaw uprawnienia dla użytkownika serwera PHP (`www-data`):
```bash
chown -R www-data:www-data /var/www/kerichogold
chown -R www-data:www-data /var/www/kerichogold_staging
```

---

## 3. Konfiguracja Dockerfile dla Środowiska PHP (`docker/app.Dockerfile`)

Ten sam Dockerfile (`docker/app.Dockerfile`) jest używany zarówno na produkcji, jak i na stagingu. Definiuje on środowisko PHP 8.3-fpm wraz z rozszerzeniami oraz Node.js (Vite):

```dockerfile
FROM php:8.3-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libmariadb-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Czyszczenie cache APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP
RUN pecl install redis \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd intl zip \
    && docker-php-ext-enable redis

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Konfiguracja PHP-FPM do nasłuchu na porcie 9000
RUN echo "[global]\n\
daemonize = no\n\
\n\
[www]\n\
listen = 0.0.0.0:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html

USER www-data
```

---

## 4. Konfiguracja Serwera WWW Nginx (`docker/nginx.conf`)

Serwery Nginx wewnątrz kontenerów webowych nasłuchują na porcie 80 i przekazują żądania PHP do kontenera `app`. NPM (Nginx Proxy Manager) przejmuje ruch zewnętrzny HTTPS i przekazuje go do tych kontenerów na port 80.

```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/html/public;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript image/svg+xml;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000; # W stagingu: fastcgi_pass staging-app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # Static Assets Caching
    location ~* \.(?:css|js|woff2?|eot|ttf|otf|png|jpg|jpeg|gif|webp|svg|ico)$ {
        root /var/www/html/public;
        try_files $uri =404;
        expires 1y;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

---

## 5. Konfiguracja Docker Compose

Używamy jednej instancji bazy danych, Redisa i Nginx Proxy Manager (współdzielonej w ramach sieci Docker `kericho-network`), aby zoptymalizować zasoby serwera.

### A. Docker Compose Produkcyjny (`/var/www/kerichogold/docker-compose.yml`)
Ten plik definiuje infrastrukturę współdzieloną oraz kontenery produkcyjne:

```yaml
services:
  app:
    container_name: kericho-app
    build:
      context: .
      dockerfile: docker/app.Dockerfile
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    networks:
      - kericho-network
    depends_on:
      - db
      - redis

  queue:
    container_name: kericho-queue
    build:
      context: .
      dockerfile: docker/app.Dockerfile
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=production
    command: php artisan queue:work --verbose --tries=3 --timeout=90
    networks:
      - kericho-network
    depends_on:
      - db
      - redis
      - app

  redis:
    image: redis:alpine
    container_name: kericho-redis
    networks:
      - kericho-network
    ports:
      - "6380:6379"

  web:
    container_name: kericho-web
    image: nginx:alpine
    expose:
      - "80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
      - ./storage/logs/nginx:/var/log/nginx
    networks:
      - kericho-network
    depends_on:
      - app

  nginx-proxy:
    image: 'jc21/nginx-proxy-manager:latest'
    container_name: kericho-proxy
    restart: always
    ports:
      - '80:80'
      - '81:81'
      - '443:443'
    volumes:
      - ./docker/proxy/data:/data
      - ./docker/proxy/letsencrypt:/etc/letsencrypt
    networks:
      - kericho-network

  db:
    container_name: kericho-db
    image: postgres:15-alpine
    environment:
      - POSTGRES_DB=kericho_prod
      - POSTGRES_USER=kericho
      - POSTGRES_PASSWORD=TWOJE_SILNE_HASLO
    volumes:
      - kericho-db-data:/var/lib/postgresql/data
    networks:
      - kericho-network

  meilisearch:
    image: getmeili/meilisearch:v1.7
    container_name: kericho-meilisearch
    ports:
      - "7700:7700"
    environment:
      - MEILI_MASTER_KEY=TWÓJ_MASTER_KEY
      - MEILI_NO_ANALYTICS=true
      - MEILI_ENV=production
    volumes:
      - kericho-meilisearch-data:/meili_data
    networks:
      - kericho-network

networks:
  kericho-network:
    driver: bridge
    name: kericho-network

volumes:
  kericho-db-data:
  kericho-meilisearch-data:
```

### B. Docker Compose Stagingowy (`/var/www/kerichogold_staging/docker-compose.staging.yml`)
Staging korzysta ze wspólnej sieci zewnętrznej `kericho-network` w celu komunikacji z bazą danych, redisem i Nginx Proxy Managerem.

```yaml
version: '3.8'

services:
  app:
    container_name: kericho-staging-app
    build:
      context: .
      dockerfile: docker/app.Dockerfile
    volumes:
      - .:/var/www/html
    networks:
      - kericho-network

  queue:
    container_name: kericho-staging-queue
    build:
      context: .
      dockerfile: docker/app.Dockerfile
    volumes:
      - .:/var/www/html
    command: php artisan queue:work --verbose --tries=3 --timeout=90
    networks:
      - kericho-network

  web:
    container_name: kericho-staging-web
    image: nginx:alpine
    volumes:
      - .:/var/www/html
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - kericho-network
    depends_on:
      - app

networks:
  kericho-network:
    external: true
```

*Uwaga:* Na środowisku stagingowym zmodyfikuj `./docker/nginx.conf` w sekcji `fastcgi_pass` na wartość `kericho-staging-app:9000` (lub stwórz dedykowany `nginx.staging.conf` i zmapuj go w wolumenach).

---

## 6. Przygotowanie Baz Danych (Database Setup)

Zanim uruchomisz aplikację stagingową, musisz utworzyć dedykowaną bazę danych `kericho_staging` w kontenerze postgres (`kericho-db`):
```bash
docker exec -it kericho-db psql -U kericho -c "CREATE DATABASE kericho_staging;"
```

---

## 7. Konfiguracja Plików Środowiskowych (`.env`)

### Plik produkcyjny (`/var/www/kerichogold/.env`):
```env
APP_NAME="Kericho Gold Shop"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sklep.kerichogold.com.pl

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=kericho_prod
DB_USERNAME=kericho
DB_PASSWORD=TWOJE_SILNE_HASLO

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=database

REDIS_HOST=redis
REDIS_PORT=6379

MEILISEARCH_HOST=http://meilisearch:7700
MEILISEARCH_KEY=TWÓJ_MASTER_KEY
```

### Plik stagingowy (`/var/www/kerichogold_staging/.env`):
```env
APP_NAME="Kericho Gold Shop [Staging]"
APP_ENV=local
APP_DEBUG=true
APP_URL=https://sklep2.kerichogold.com.pl

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=kericho_staging
DB_USERNAME=kericho
DB_PASSWORD=TWOJE_SILNE_HASLO

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=database

REDIS_HOST=redis
REDIS_PORT=6379

MEILISEARCH_HOST=http://meilisearch:7700
MEILISEARCH_KEY=TWÓJ_MASTER_KEY

# Zabezpieczenie przed robotami SEO
ROBOTS_NOINDEX=true
```

---

## 8. Konfiguracja Nginx Proxy Manager (Ruting & SSL)

Po uruchomieniu kontenerów wejdź do panelu NPM pod adresem: `http://IP_SERWERA:81`
Zmień domyślne dane logowania (admin@example.com / changeme).

### Konfiguracja domeny produkcyjnej (`sklep.kerichogold.com.pl`):
1.  Kliknij **Add Proxy Host**.
2.  **Domain Names:** `sklep.kerichogold.com.pl`
3.  **Scheme:** `http`
4.  **Forward Name/IP:** `kericho-web` (nazwa usługi/kontenera w sieci Docker)
5.  **Forward Port:** `80`
6.  Zaznacz opcje: *Block Common Exploits*, *Websockets Support*.
7.  Przejdź do zakładki **SSL**:
    *   Wybierz **Request a new SSL Certificate** z Let's Encrypt.
    *   Zaznacz: *Force SSL*, *HTTP/2 Support*.
    *   Zaakceptuj warunki i kliknij **Save**.

### Konfiguracja domeny stagingowej (`sklep2.kerichogold.com.pl`):
1.  Kliknij **Add Proxy Host**.
2.  **Domain Names:** `sklep2.kerichogold.com.pl`
3.  **Scheme:** `http`
4.  **Forward Name/IP:** `kericho-staging-web` (nazwa usługi/kontenera w sieci Docker)
5.  **Forward Port:** `80`
6.  Zaznacz opcje: *Block Common Exploits*, *Websockets Support*.
7.  Przejdź do zakładki **SSL**:
    *   Wybierz **Request a new SSL Certificate** z Let's Encrypt.
    *   Zaznacz: *Force SSL*, *HTTP/2 Support*.
    *   Kliknij **Save**.

---

## 9. Pierwsze Uruchomienie (Initial Deploy Sequence)

### A. Uruchomienie infrastruktury i produkcji:
```bash
cd /var/www/kerichogold
docker compose up -d --build

# Inicjalizacja aplikacji produkcyjnej
docker exec kericho-app php artisan key:generate
docker exec kericho-app php artisan migrate --force
docker exec kericho-app php artisan optimize:clear
docker exec kericho-app php artisan filament:optimize
```

### B. Uruchomienie stagingu:
```bash
# Utworzenie bazy stagingowej w istniejącym kontenerze db
docker exec -it kericho-db psql -U kericho -c "CREATE DATABASE kericho_staging;"

# Uruchomienie kontenerów stagingowych
cd /var/www/kerichogold_staging
docker compose -f docker-compose.staging.yml up -d --build

# Inicjalizacja aplikacji stagingowej
docker exec kericho-staging-app php artisan key:generate
docker exec kericho-staging-app php artisan migrate --force
docker exec kericho-staging-app php artisan optimize:clear
```

### C. Weryfikacja testów:
Jeśli composer został uruchomiony bez flagi `--no-dev` (środowisko developerskie), możesz uruchomić pełne testy:
```bash
docker exec kericho-app php artisan test
```
Na produkcji/stagingu (z `--no-dev`) uruchamiamy testy bezpieczeństwa integracji:
```bash
docker exec kericho-app php artisan test:tpay-security
docker exec kericho-staging-app php artisan test:tpay-security
```

---
*Dokumentacja przygotowana w oparciu o konfigurację Nevro-Shop v2 / Maj 2026.*
