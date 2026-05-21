# Task: Full Migration, Photo Sync & Legacy Auth
**ID:** 2026_04_27_FULL_MIGRATION
**Assignee:** Agent_3_Mass_Coder

## Objective
Finalize data migration and ensure photos and customer accounts are ready.

## Steps
1. **Photo Sync Script:** 
   - Improve the existing photo sync logic to handle directory structures. 
   - Photos should move from `v1/images/` to `v2/storage/app/public/products/`.
2. **Full Data Migration:**
   - Execute the migration command for ALL Categories, Products, and Customers.
3. **Legacy Password Service:**
   - Implement a custom User Provider or a login listener in Laravel that:
     - Checks if the user has a `legacy_password` (MD5:SALT).
     - If yes, validates the input using `md5($salt . $password)`.
     - On success, re-hashes the password using `bcrypt` and clears the legacy fields.
4. **Order History:**
   - Complete the mapping for `orders` and `order_items`.

## Instructions
- Ensure all logic is covered by `php -l`.
- Push changes to `zibbie/nevro-shop-v2`.
