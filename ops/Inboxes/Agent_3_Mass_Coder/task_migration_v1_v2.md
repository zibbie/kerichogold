# Task: Data Migration Script (ShopGold to Laravel)
**ID:** 2026_04_27_MIGRATION_V1_V2
**Assignee:** Agent_3_Mass_Coder

## Objective
Develop a robust migration script to transfer all data from the legacy ShopGold (MySQL) database to the new Nevro-Shop (PostgreSQL) environment.

## Requirements
1. **Database Connections:**
   - Configure a secondary database connection in `config/database.php` named `legacy` pointing to the ShopGold MySQL DB.
2. **Migration Command:**
   - Create a Laravel command: `php artisan nevro:migrate-legacy`.
3. **Data Mapping:**
   - **Categories:** Preserve hierarchy.
   - **Products:** Transfer name, description, price, and stock. Handle attributes (stored in legacy tables) by converting them to a JSON structure in the new `products` table.
   - **Customers:** Map `customers` to the `users` table. **CRITICAL:** Store the legacy `MD5:SALT` password in a `legacy_password` column and flag the user for a "Legacy Hash Check" on login.
   - **Orders:** Transfer order history and items.
4. **Photos:** Create a task to sync files from `images/` to the new Laravel `storage/app/public/products` directory.

## Testing
- Ensure the script handles Polish characters (UTF-8) correctly.
- Implement a `--limit` flag to test with only 10 products first.

## Finalization
- Push the code to `zibbie/nevro-shop-v2`.
- Update your status in `ops/Outboxes/Agent_3_Mass_Coder/report.md`.
