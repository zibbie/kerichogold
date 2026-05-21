# Task: Apply UI Fixes and Homepage Restoration
**ID:** 2026_04_27_FIX_01
**Assignee:** Agent_2_Fast_Editor

## Your Mission
Apply the following UI fixes to the local codebase:

1. **Header Spacing (CSS):**
Update `szablony/standardowy.rwd.v2/css/custom_checkout.css` to include:
```css
.NaglowekCheckoutMode .KontenerNaglowek {
    display: flex;
    align-items: center;
    max-width: 1500px;
    margin: 0 auto;
    padding: 15px 30px;
    gap: 30px;
}
.NaglowekCheckoutMode .CheckoutCartStatus {
    margin-left: auto;
    display: flex;
    align-items: center;
}
```

2. **Homepage Forced Include (Template):**
Update `szablony/standardowy.rwd.v2/strona_glowna.tp` to force include modules:
```php
<?php 
if ($GLOBALS['stronaGlowna'] == true) {
    include('moduly/polecane_okna.php'); 
    include('moduly/nowosci_okna.php');
    include('moduly/nasz_hit_okna.php');
}
?>
```

3. **Cart Popup Fix:**
Update `ajax_nevro/do_koszyka_proxy.php` to ensure keys match the production object structure.

## Final Steps
1. Run `php -l` on modified files.
2. `git add .`
3. `git commit -m "UI Fixes and Homepage Restoration via Local Agent"`
4. `git push origin master`

## Instructions
- Read `ops/SKILL.md` before starting.
- Signal completion to the Orchestrator.
