<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Experiment;
use App\Models\ExperimentVariant;

class ExperimentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Free Shipping Progress Bar
        $exp1 = Experiment::updateOrCreate(
            ['slug' => 'cart-free-shipping-bar'],
            [
                'name' => 'Pasek postępu darmowej dostawy',
                'description' => 'Testuje, czy wizualny pasek postępu w koszyku zwiększa wartość zamówienia (AOV).',
                'is_active' => true,
            ]
        );
        
        $exp1->variants()->updateOrCreate(['key' => 'control'], ['name' => 'Brak paska (Kontrola)', 'weight' => 50]);
        $exp1->variants()->updateOrCreate(['key' => 'bar'], ['name' => 'Pasek postępu', 'weight' => 50]);

        // 2. Checkout Button Text
        $exp2 = Experiment::updateOrCreate(
            ['slug' => 'checkout-button-text'],
            [
                'name' => 'Tekst przycisku zamówienia',
                'description' => 'Testuje, czy zmiana tekstu na bardziej wynikowy zwiększa konwersję.',
                'is_active' => true,
            ]
        );

        $exp2->variants()->updateOrCreate(['key' => 'control'], ['name' => 'Zapłać i zamów', 'weight' => 50]);
        $exp2->variants()->updateOrCreate(['key' => 'action'], ['name' => 'Odbierz zamówienie', 'weight' => 50]);

        // 3. Add to Cart Color
        $exp3 = Experiment::updateOrCreate(
            ['slug' => 'product-add-to-cart-color'],
            [
                'name' => 'Kolor przycisku koszyka',
                'description' => 'Testuje wpływ kontrastowego koloru przycisku na ilość dodań do koszyka.',
                'is_active' => true,
            ]
        );

        $exp3->variants()->updateOrCreate(['key' => 'sage'], ['name' => 'Zieleń Szałwiowa (Brand)', 'weight' => 50]);
        $exp3->variants()->updateOrCreate(['key' => 'terracotta'], ['name' => 'Terakota (Kontrast)', 'weight' => 50]);
    }
}
