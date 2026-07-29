<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'category_id' => 1,
            'name' => 'Promo Set',
            'price' => 50000,
            'discount_price' => 40000,
            'image' => 'promo-set.png',
            'description' => 'Set promo dengan makanan dan minuman'
        ]);

        \App\Models\Product::create([
            'category_id' => 2,
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'discount_price' => null,
            'image' => 'nasi-goreng.png',
            'description' => 'Nasi goreng dengan telur dan sayuran'
        ]);
        \App\Models\Product::create([
            'category_id' => 3,
            'name' => 'Caesar Salad',
            'price' => 15000,
            'discount_price' => null,
            'image' => 'caesar-salad.png',
            'description' => 'Salad Caesar dengan ayam panggang'
        ]);
    }
}
