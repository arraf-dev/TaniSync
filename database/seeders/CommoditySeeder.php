<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Commodity;
use Illuminate\Database\Seeder;

class CommoditySeeder extends Seeder
{
    public function run(): void
    {
        $commodities = [
            ['nama_komoditas' => 'Padi Ciherang', 'kategori' => 'Pangan', 'satuan' => 'kg', 'harga_acuan' => 12500, 'icon' => '🌾', 'is_active' => true],
            ['nama_komoditas' => 'Jagung Manis', 'kategori' => 'Pangan', 'satuan' => 'kg', 'harga_acuan' => 8100, 'icon' => '🌽', 'is_active' => true],
            ['nama_komoditas' => 'Cabai Merah', 'kategori' => 'Hortikultura', 'satuan' => 'kg', 'harga_acuan' => 43200, 'icon' => '🌶️', 'is_active' => true],
            ['nama_komoditas' => 'Kentang Lokal', 'kategori' => 'Umbi-umbian', 'satuan' => 'kg', 'harga_acuan' => 18000, 'icon' => '🥔', 'is_active' => true],
            ['nama_komoditas' => 'Kedelai', 'kategori' => 'Palawija', 'satuan' => 'kg', 'harga_acuan' => 14000, 'icon' => '🫘', 'is_active' => false],
        ];

        foreach ($commodities as $data) {
            $category = Category::query()->where('nama_kategori', $data['kategori'])->first();

            if ($category) {
                Commodity::query()->updateOrCreate(
                    ['nama_komoditas' => $data['nama_komoditas']],
                    [
                        'kategori_id' => $category->id,
                        'satuan' => $data['satuan'],
                        'harga_acuan' => $data['harga_acuan'],
                        'icon' => $data['icon'],
                        'is_active' => $data['is_active'],
                    ]
                );
            }
        }
    }
}
