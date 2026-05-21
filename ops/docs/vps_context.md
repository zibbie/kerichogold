# Kontekst Serwera VPS (Produkcja - Nevro-Shop v2)

Ten dokument zawiera niezbędne informacje dla Orkiestratora do monitorowania stanu produkcji w nowym stacku Laravel.

## Model Uprawnień
- **Agenci Wykonawczy (Local)**: Mają dostęp do lokalnego repozytorium Git oraz zdalnego GitHub (`push`). Nie mają dostępu do VPS.
- **Orkiestrator (VPS Main)**: Ma pełny dostęp do VPS (SSH) oraz do GitHub (`pull`). Odpowiada za proces deploymentu.

## Dane Serwera VPS (Tylko dla Orkiestratora)
- **IP**: `212.227.75.28`
- **Użytkownik**: `root`
- **SSH Key**: `/Users/zbyszek/.ssh/id_ed25519`
- **Lokalizacja Sklepu**: `/var/www/nevro-shop-v2`
- **Hasło DB (Admin)**: [REDACTED_DATABASE_PASSWORD]

## Dane GitHub (Dla wszystkich Agentów)
- **Repo**: `https://github.com/zibbie/nevro-shop-v2.git`
- **Użytkownik**: `zibbie`
- **Token (PAT)**: [REDACTED_GITHUB_TOKEN]

## Kontenery Docker (v2 Stack)
- **Web (Nginx)**: `nevro-shop-v2-web-1`
- **App (PHP 8.3 FPM)**: `nevro-shop-v2-app-1`
- **Database (PostgreSQL 15)**: `nevro-shop-v2-db-1`
- **Cache (Redis)**: `nevro-shop-v2-redis-1`

## Komendy Diagnostyczne (SSH)

### Podgląd logów aplikacji (Laravel)
```bash
ssh root@212.227.75.28 "docker logs --tail 50 nevro-shop-v2-app-1"
```

### Wejście do konsoli Tinker
```bash
ssh root@212.227.75.28 "docker exec -it nevro-shop-v2-app-1 php artisan tinker"
```

### Podgląd logów Nginx
```bash
ssh root@212.227.75.28 "docker logs --tail 50 nevro-shop-v2-web-1"
```

### Uruchomienie migracji
```bash
ssh root@212.227.75.28 "docker exec nevro-shop-v2-app-1 php artisan migrate --force"
```

## Ważne Ścieżki na VPS
- **Root projektu**: `/var/www/nevro-shop-v2`
- **Logi Laravela**: `storage/logs/laravel.log` (wewnątrz kontenera `app`)
- **Config Nginx**: `docker/nginx/default.conf` (w repozytorium)

## Bezpieczeństwo
- Baza danych PostgreSQL bindowana wewnętrznie w sieci Docker.
- Redis chroniony hasłem (zobacz `.env` na VPS).
- Dostęp SSH ograniczony kluczem.

