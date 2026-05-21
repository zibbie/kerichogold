<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample products with images
        Product::create([
            'name' => 'Sample Product 1',
            'sku' => 'SP001',
            'price' => 100.00,
            'quantity' => 10,
            'status' => true,
            'image' => 'sample1.jpg',
            'description' => 'Sample product description',
        ]);

        Product::create([
            'name' => 'Sample Product 2',
            'sku' => 'SP002',
            'price' => 200.00,
            'quantity' => 5,
            'status' => true,
            'image' => 'sample2.jpg',
            'description' => 'Another sample product',
        ]);

        // Add more products for testing (232 real products simulation)
        for ($i = 3; $i <= 234; $i++) {
            Product::create([
                'name' => "Real Product {$i}",
                'sku' => "RP" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'price' => rand(50, 500),
                'quantity' => rand(1, 20),
                'status' => true,
                'image' => "real{$i}.jpg",
                'description' => "Description for real product {$i}",
            ]);
        }
    }
}