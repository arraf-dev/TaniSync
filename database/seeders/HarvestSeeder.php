<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\Harvest;
use App\Models\User;
use Illuminate\Database\Seeder;

class HarvestSeeder extends Seeder
{
    public function run(): void
    {
        $petani = User::query()->where('role', 'petani')->first();
        $admin = User::query()->where('role', 'admin')->first();

        if (! $petani) {
            return;
        }

        $commodities = Commodity::query()->where('is_active', true)->get();

        $harvests = [
            [
                'commodity_name' => 'Padi Ciherang',
                'harvest_date' => now()->subDays(2)->toDateString(),
                'quantity' => 450,
                'unit' => 'kg',
                'location' => 'Blok Utara 02',
                'quality' => 'Grade A',
                'note' => 'Cuaca cerah, gabah kering siap jemur.',
                'status' => 'terverifikasi',
                'verified' => true,
            ],
            [
                'commodity_name' => 'Jagung Manis',
                'harvest_date' => now()->subDays(4)->toDateString(),
                'quantity' => 280,
                'unit' => 'kg',
                'location' => 'Lahan Timur 03',
                'quality' => 'Grade B',
                'note' => 'Bagian timur lahan, kadar air stabil.',
                'status' => 'terverifikasi',
                'verified' => true,
            ],
            [
                'commodity_name' => 'Cabai Merah',
                'harvest_date' => now()->subDays(5)->toDateString(),
                'quantity' => 96,
                'unit' => 'kg',
                'location' => 'Kebun Lereng',
                'quality' => 'Grade A',
                'note' => 'Perlu sortir lanjutan.',
                'status' => 'menunggu',
                'verified' => false,
            ],
            [
                'commodity_name' => 'Kentang Lokal',
                'harvest_date' => now()->subDays(7)->toDateString(),
                'quantity' => 320,
                'unit' => 'kg',
                'location' => 'Petak Barat 04',
                'quality' => 'Grade A',
                'note' => 'Lahan basah, cek penyimpanan.',
                'status' => 'menunggu',
                'verified' => false,
            ],
        ];

        foreach ($harvests as $data) {
            $commodity = $commodities->firstWhere('nama_komoditas', $data['commodity_name']);

            if ($commodity) {
                Harvest::query()->updateOrCreate(
                    [
                        'user_id' => $petani->id,
                        'commodity_id' => $commodity->id,
                        'harvest_date' => $data['harvest_date'],
                    ],
                    [
                        'quantity' => $data['quantity'],
                        'unit' => $data['unit'],
                        'location' => $data['location'],
                        'quality' => $data['quality'],
                        'note' => $data['note'],
                        'status' => $data['status'],
                        'verified_by' => $data['verified'] ? $admin?->id : null,
                    ]
                );
            }
        }
    }
}
