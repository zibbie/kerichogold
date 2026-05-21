# Session Handoff: 2026-04-27

**Status:** Transition to Nevro-Shop v2 (Laravel) in progress.

## Infrastructure
- **VPS IP:** 212.227.75.28
- **New Project Path:** `/var/www/nevro-shop-v2`
- **Docker Stack (v2):** `v2-app` (PHP 8.3), `v2-web` (Nginx), `v2-db` (PostgreSQL).
- **Repo:** `https://github.com/zibbie/nevro-shop-v2`

## Accomplishments
1. **Migration Engine:** Developed by Agent 3. Supports Categories, Products (JSON attributes), Customers (Legacy MD5:SALT hashes), and Orders. Tested with 10 items.
2. **UI Framework:** Agent 2 implemented TALL Stack (Tailwind, Alpine, Livewire) with Premium layout.
3. **Domain Routing:** `nevro-wm.pl` points to v2 (port 8081). `shop.nevro-wm.pl` points to v1 (port 8080).

## Current Issue (High Priority)
- **502 Bad Gateway on `nevro-wm.pl`**: The Nginx proxy works, but the Laravel app inside the container is not fully bootstrapped. 
- **Required Fix:** 
  1. `docker exec -it v2-app composer install`
  2. `docker exec -it v2-app php artisan key:generate`
  3. `docker exec -it v2-app php artisan migrate`

## Pending Tasks
- Complete full data and photo migration (7,173 images).
- Configure Tpay (BLIK) production credentials.
- Verify SSL for `shop.nevro-wm.pl`.

---
**Prepared by:** Antigravity (Orchestrator Agent 1)
