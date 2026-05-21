# Kontekst Serwera VPS (Produkcja & Staging - Kericho Gold)

Ten dokument zawiera niezbędne informacje dla Orkiestratora do monitorowania stanu produkcji i stagingu w nowym stacku Laravel.

## Model Uprawnień
- **Agenci Wykonawczy (Local)**: Mają dostęp do lokalnego repozytorium Git oraz zdalnego GitHub (`push`). Nie mają dostępu do VPS.
- **Orkiestrator (VPS Main)**: Ma pełny dostęp do VPS (SSH) oraz do GitHub (`pull`). Odpowiada za proces deploymentu.

## Dane Serwera VPS (Tylko dla Orkiestratora)
- **IP**: `85.215.169.120`
- **Użytkownik**: `root`
- **SSH Key**: `/Users/zbyszek/.ssh/id_ed25519`
- **Lokalizacja Sklepu (Produkcja)**: `/var/www/kerichogold`
- **Lokalizacja Sklepu (Staging)**: `/var/www/kerichogold_staging`
- **Hasło DB (Admin)**: [REDACTED_DATABASE_PASSWORD]

## Dane GitHub (Dla wszystkich Agentów)
- **Repo**: `https://github.com/zibbie/kerichogold.git`
- **Użytkownik**: `zibbie`
- **Token (PAT)**: [REDACTED_GITHUB_TOKEN]

## Kontenery Docker (Produkcja)
- **Web (Nginx)**: `kericho-web`
- **App (PHP 8.3 FPM)**: `kericho-app`
- **Queue (Worker)**: `kericho-queue`
- **Database (PostgreSQL 15)**: `kericho-db`
- **Cache (Redis)**: `kericho-redis`
- **Search (Meilisearch)**: `kericho-meilisearch`
- **Proxy (Nginx Proxy Manager)**: `kericho-proxy`

## Kontenery Docker (Staging)
- **Web (Nginx)**: `kericho-staging-web`
- **App (PHP 8.3 FPM)**: `kericho-staging-app`
- **Queue (Worker)**: `kericho-staging-queue`

## Komendy Diagnostyczne (SSH)

### Podgląd logów aplikacji (Laravel - Produkcja)
```bash
ssh root@85.215.169.120 "docker logs --tail 50 kericho-app"
```

### Podgląd logów aplikacji (Laravel - Staging)
```bash
ssh root@85.215.169.120 "docker logs --tail 50 kericho-staging-app"
```

### Wejście do konsoli Tinker (Produkcja)
```bash
ssh root@85.215.169.120 "docker exec -it kericho-app php artisan tinker"
```

### Uruchomienie migracji (Produkcja)
```bash
ssh root@85.215.169.120 "docker exec kericho-app php artisan migrate --force"
```

## Ważne Ścieżki na VPS
- **Root projektu (Produkcja)**: `/var/www/kerichogold`
- **Root projektu (Staging)**: `/var/www/kerichogold_staging`
- **Logi Laravela**: `storage/logs/laravel.log` (wewnątrz kontenera `app`)
- **Config Nginx**: `docker/nginx.conf` (w repozytorium)
