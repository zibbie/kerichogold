# Handoff: Nevro-Shop v2 State - 2026-04-28

## Current Status
- **Production VPS**: `212.227.75.28` is LIVE at `https://nevro-wm.pl`.
- **Infrastructure**: Dockerized stack (Nginx-Proxy, PHP-FPM, PostgreSQL, Redis).
- **SSL**: certificates from host mapped to Nginx container.
- **Admin**: Filament at `/admin` is fully functional.

## Critical Fixes Completed Today
1. **Auth Driver**: Switched `config/auth.php` from `legacy` to `eloquent`.
2. **Double Hashing**: Removed manual hashing from `UserResource`, allowing `User` model's `hashed` cast to work correctly.
3. **Access Rules**: Updated `User.php` and `UserPolicy.php` to allow `zbyszeklupikasza@gmail.com` and `@nevro-wm.pl` domain.
4. **Checkout UI**: Fixed invisible "Finalize Order" button using inline styles (`!important`) to bypass CSS masking.
5. **Data Migration**: 232 products successfully migrated to production.

## Pending Tasks for Next Session
1. **API Keys**: Populate `.env` on VPS with `TPAY_*` and `APACZKA_*` credentials.
2. **User Cleanup**: Delete `testadmin@admin.com` via Admin Panel.
3. **Password Change**: Encourage user to change the temporary `nevro123` password.
4. **Log Review**: Monitor production logs for any remaining 500 errors.

## Production Context
- **Root dir**: `/var/www/nevro-shop-v2`
- **Tinker command**: `docker exec -u root nevro-shop-v2-app-1 php artisan tinker`
- **Login**: `zbyszeklupikasza@gmail.com` / `nevro123`
