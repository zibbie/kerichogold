# Handoff: Nevro-Shop v2 State - 2026-04-30

## Current Status
- **New Production VPS**: `212.227.75.28` is LIVE at `https://nevro-wm.pl`.
- **Infrastructure**: Debian 12 with Dockerized stack + Nginx Proxy Manager (NPM).
- **SSL**: Managed automatically by NPM (Let's Encrypt).
- **Data Migration**: 100% database (PostgreSQL) and media (storage/app/public) transferred from the old server.
- **Vat Invoices**: Feature fully implemented and migrated.

## Critical Fixes Completed Today
1. **Migration to Debian 12**: Fresh server setup, Docker installation, and project cloning.
2. **Nginx Proxy Manager**: Installed as a Docker service to handle ports 80/443 and SSL management (GUI at port 81).
3. **VAT Invoice Request**: Added `wants_invoice` and `nip` fields to Checkout and Admin Panel.
4. **Przelewy24 Alpha Fix**: Removed integer casting for Merchant ID in `Przelewy24Service` to support alphanumeric IDs.
5. **Session Persistence**: Fixed "Cart is empty" issue by setting `SESSION_SECURE_COOKIE=true` and `SESSION_DOMAIN=` for HTTPS.

## Pending Tasks for Next Session
1. **P24 Auth Fix**: Resolve `401 Incorrect authentication` error.
    - *Investigation*: Confirm if `65724a12` is indeed the Merchant ID or if it's a panel login.
    - *Action*: Verify API Key and CRC in P24 panel; possibly generate new ones.
2. **Database Cleanliness**: Address `Duplicate column: 7 ERROR: column "slug" already exists` logged during migrations (legacy artifact from import).
3. **P24 Webhook Test**: Verify that payment status updates correctly after a successful transaction.

## Production Context
- **IP Address**: `212.227.75.28`
- **Root dir**: `/var/www` (on host)
- **Proxy Panel**: `http://212.227.75.28:81` (admin@example.com / changeme)
- **Tinker command**: `docker compose exec app php artisan tinker`
- **Laravel Logs**: `docker compose exec app tail -f storage/logs/laravel.log`
