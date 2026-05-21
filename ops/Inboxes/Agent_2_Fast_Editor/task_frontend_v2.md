# Task: Premium Homepage UI Development
**ID:** 2026_04_27_FRONTEND_UI
**Assignee:** Agent_2_Fast_Editor

## Objective
Create a high-end, responsive homepage for Nevro-Shop v2 using Tailwind CSS and Livewire.

## Requirements
1. **Main Layout:** Create `resources/views/layouts/app.blade.php` with a clean header (Logo, Search, Cart icon) and footer.
2. **Product Card Component:** Create a Livewire component `ProductCard` that displays:
   - Product Image (with hover effect).
   - Name (clean typography).
   - Price (bold, distinct).
   - "Add to Cart" button (using the #28a745 green color scheme from previous sessions).
3. **Homepage Grid:** Display z z-migrated products in a 4-column grid (desktop) and 1 or 2 columns (mobile).

## Design Guidelines
- Use "DM Sans" or "Inter" fonts.
- Background: Very light gray (#f8f9fa) or clean white.
- Cards: Subtle shadows, rounded corners (`rounded-xl`).

## Instructions
- Use `php artisan make:livewire Home` for the main page.
- Push changes to `zibbie/nevro-shop-v2`.
