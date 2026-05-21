# Instrukcja Konfiguracji Serwera VPS (Home.pl) - Nevro-Shop v2 (Debian 12)

Ta instrukcja przeprowadzi Cię przez proces konfiguracji nowego serwera na systemie **Debian 12 (Bookworm)**.

## 1. Pierwsze logowanie i aktualizacja
Zaloguj się na serwer jako root:
```bash
ssh root@IP_TWOJEGO_SERWERA
```

Zaktualizuj system:
```bash
apt update && apt upgrade -y
```

## 2. Bezpieczeństwo (SSH i Firewall)
### Dodanie klucza SSH
1. Na swoim komputerze: `cat ~/.ssh/id_ed25519.pub | pbcopy`
2. Na serwerze:
```bash
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Wklej klucz, zapisz (Ctrl+O, Enter) i wyjdź (Ctrl+X)
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### Konfiguracja Firewalla (UFW)
Na Debianie UFW może nie być zainstalowany domyślnie:
```bash
apt install ufw -y
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

## 3. Instalacja Dockera (Debian)
Wykonaj te komendy, aby zainstalować Dockera na Debianie:
```bash
# Dodanie oficjalnego klucza GPG Dockera
apt install ca-certificates curl gnupg -y
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg

# Dodanie repozytorium do źródeł APT (Debian)
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  tee /etc/apt/sources.list.d/docker.list > /dev/null

apt update
apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y
```

## 4. Przygotowanie projektu (Git Deploy Key)
Wygeneruj klucz na serwerze, aby mógł pobierać kod z Twojego repozytorium:
```bash
ssh-keygen -t ed25519 -C "vps-nevro-shop"
cat ~/.ssh/id_ed25519.pub
```
**Skopiuj ten klucz i dodaj go w ustawieniach Twojego repozytorium (Settings -> Deploy Keys -> Add Deploy Key).**

Pobierz kod projektu:
```bash
mkdir -p /var/www
cd /var/www
git clone git@github.com:TWOJA_NAZWA/nevro-shop-v2.git .
```

## 5. Konfiguracja aplikacji
Utwórz plik `.env` na bazie przykładu:
```bash
cp .env.example .env
nano .env
```
**Kluczowe zmiany w `.env`:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://nevro-wm.pl`
- `DB_PASSWORD` (ustaw silne hasło)
- `P24_MERCHANT_ID`, `P24_API_KEY`, `P24_CRC` (nowe dane z Przelewy24)

## 6. Uruchomienie kontenerów
```bash
docker compose up -d --build
```

Uruchom migracje i wygeneruj klucz aplikacji:
```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan filament:optimize
```

## 7. SSL (HTTPS) przez Certbot
Zainstaluj Certbota:
```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d nevro-wm.pl -d www.nevro-wm.pl
```
Certbot automatycznie wykryje Twoją konfigurację Nginx i doda certyfikaty SSL.

## 8. Automatyczne odnawianie SSL
Sprawdź, czy odnawianie działa:
```bash
certbot renew --dry-run
```

---
## 9. Backup i Synchronizacja Lokalna

Aby pobrać aktualną bazę i pliki z VPS do środowiska lokalnego:

### 1. Zrzut bazy na VPS
```bash
ssh root@IP_SERWERA "docker exec v2-db pg_dump -U nevro nevr0_v2 > /var/www/production_db_backup.sql"
```

### 2. Rsync plików i bazy do lokalnego folderu
```bash
rsync -avz root@IP_SERWERA:/var/www/production_db_backup.sql ./backups/
rsync -avz root@IP_SERWERA:/var/www/storage/app/public/ ./storage/app/public/
```

### 3. Import lokalny (Docker)
```bash
docker exec -i v2-db psql -U nevro -d nevro_v2 < ./backups/production_db_backup.sql
```

---
*Gotowe! Twój sklep powinien być dostępny pod adresem https://nevro-wm.pl.*
