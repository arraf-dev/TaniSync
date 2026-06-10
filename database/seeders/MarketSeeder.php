<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        Market::query()->updateOrCreate(
            ['nama_pasar' => 'Pasar Desa Sukamaju'],
            [
                'tipe' => 'tradisional',
                'alamat_lengkap' => 'Jl. Raya Desa Sukamaju No. 1, Kec. Sukamaju',
                'is_active' => true,
            ]
        );
    }
}
