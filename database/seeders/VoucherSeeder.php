<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 1000; $i++) {
            Voucher::create([
                'code' => str_pad($i, 4, '0', STR_PAD_LEFT), // Menjadi 4 digit (0001 - 1000)
                'is_used' => false,
            ]);
        }
    }
}