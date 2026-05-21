<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Accessory Categories
    |--------------------------------------------------------------------------
    |
    | Slugs of categories that are considered accessories. Accessories are
    | usually shipped with the main product or have a flat shipping rate
    | regardless of quantity if bought alone.
    |
    */
    'accessory_categories' => [
        'akcesoria-ibc',
        'akcesoria',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Rates
    |--------------------------------------------------------------------------
    |
    | Define the shipping rates for different shipping classes and types.
    |
    */
    'rates' => [
        'paczkomat_a' => ['name' => 'InPost Paczkomat (Gabaryt A)', 'price' => 15.99, 'type' => 'paczkomat'],
        'paczkomat_b' => ['name' => 'InPost Paczkomat (Gabaryt B)', 'price' => 16.99, 'type' => 'paczkomat'],
        'paczkomat_c' => ['name' => 'InPost Paczkomat (Gabaryt C)', 'price' => 19.99, 'type' => 'paczkomat'],
        'courier_standard' => ['name' => 'Kurier Standard', 'price' => 18.99, 'type' => 'courier'],
        'courier_heavy' => ['name' => 'Kurier Ciężki', 'price' => 24.99, 'type' => 'courier'],
        'courier_oversize' => ['name' => 'Kurier Gabaryt', 'price' => 80.00, 'type' => 'courier'],
        'pallet' => ['name' => 'Paleta', 'price' => 260.00, 'type' => 'courier'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Items Per Package
    |--------------------------------------------------------------------------
    |
    | Default value if product doesn't specify items_per_package.
    |
    */
    'default_items_per_package' => 1,
];
