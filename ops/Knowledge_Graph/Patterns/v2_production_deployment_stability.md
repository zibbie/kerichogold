# Wzorce Stabilności Wdrożeń Produkcyjnych (Vite & Permissions)

## Problem
Strona "rozsypuje się" (brak stylów CSS, błędy ładowania assetów) po synchronizacji plików (`rsync`) na serwer produkcyjny. Problemem jest zazwyczaj brak aktualnych plików w `public/build`, niespójność manifestu Vite lub brak uprawnień dla użytkownika `www-data`.

## Rozwiązanie (Pattern)

Zawsze po wykonaniu `rsync` lub innych zmianach w plikach na produkcji, należy wykonać następującą sekwencję naprawczą:

### 1. Rebuild Assetów (Vite)
Jeśli środowisko posiada zainstalowane `node_modules`, przebuduj assety bezpośrednio na serwerze:
```bash
docker compose exec app npm run build
```
Jeśli budowanie na serwerze zawodzi, zbuduj lokalnie i zsynchronizuj folder `public/build`:
```bash
npm run build
rsync -avz ./public/build/ root@212.227.75.28:/var/www/public/build/
```

### 2. Naprawa Uprawnień
Użytkownik `www-data` musi mieć uprawnienia do zapisu w katalogach cache i storage, oraz do odczytu w `public`:
```bash
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/public
```

### 3. Czyszczenie Cache Laravel
Stare pliki manifestu lub cache'owane ścieżki mogą powodować błędy 404:
```bash
rm -f /var/www/bootstrap/cache/*.php
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

## Dlaczego to jest ważne?
Syncowanie plików przez `rsync` jako `root` zmienia właściciela plików na `root`, co blokuje serwerowi WWW (działającemu jako `www-data`) możliwość generowania widoków Blade lub czytania skompilowanych assetów Vite.
