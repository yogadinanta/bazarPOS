<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create([
            'name' => 'Menu Promo',
            'icon' => 'promo.png'
        ]);

        \App\Models\Category::create([
            'name' => 'Menu Utama',
            'icon' => 'main-course.png'
        ]);

        \App\Models\Category::create([
            'name' => 'Appetizer',
            'icon' => 'salad.png'
        ]);

        \App\Models\Category::create([
            'name' => 'Drinks',
            'icon' => 'drink.png'
        ]);

        \App\Models\Category::create([
            'name' => 'Dessert',
            'icon' => 'cake.png'
        ]);
    }
}