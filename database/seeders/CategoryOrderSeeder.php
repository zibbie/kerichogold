<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Akcesoria IBC',
            'Krany i zawory',
            'Kanistry',
            'Pojemniki',
            'Skrzynki magazynowe',
            'Worki BIG BAG',
            'Wanny wychwytowe',
            'Donice',
            'Kosze',
            'Kliny po koła',
        ];

        foreach ($categories as $index => $name) {
            $slug = \Illuminate\Support\Str::slug($name);
            \Illuminate\Support\Facades\DB::table('categories')
                ->where('name', $name)
                ->orWhere('slug', $slug)
                ->update(['position' => $index + 1]);
        }
    }
}
