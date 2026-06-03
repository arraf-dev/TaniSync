<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\HarvestLog;
use App\Models\Market;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $platformAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@tanisync.id'],
            [
                'name' => 'Super Admin TaniSync',
                'organization_id' => null,
                'village' => 'Platform TaniSync',
                'role' => 'super_admin',
                'account_status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        $organization = Organization::query()->updateOrCreate(
            ['slug' => 'desa-sukamaju'],
            [
                'name' => 'Desa Sukamaju',
                'type' => 'desa',
                'region' => 'Sukamaju',
                'address' => 'Jl. Raya Sukamaju No. 12',
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => $platformAdmin->id,
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@tanisync.id'],
            [
                'name' => 'Admin Gapoktan',
                'organization_id' => $organization->id,
                'village' => $organization->name,
                'role' => 'admin',
                'account_status' => 'active',
                'approved_at' => now(),
                'approved_by' => $platformAdmin->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        $farmer = User::query()->updateOrCreate(
            ['email' => 'rahmat@tanisync.id'],
            [
                'name' => 'Bapak Rahmat',
                'organization_id' => $organization->id,
                'village' => $organization->name,
                'role' => 'petani',
                'account_status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        $sari = User::query()->updateOrCreate(
            ['email' => 'sari@tanisync.id'],
            [
                'name' => 'Ibu Sari',
                'organization_id' => $organization->id,
                'village' => $organization->name,
                'role' => 'petani',
                'account_status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        $categories = collect([
            'Pangan',
            'Hortikultura',
            'Umbi-umbian',
            'Palawija',
        ])->mapWithKeys(fn (string $name): array => [
            $name => Category::query()->updateOrCreate(
                ['nama_kategori' => $name],
                ['is_active' => true]
            ),
        ]);

        $commodities = collect([
            ['nama_komoditas' => 'Padi Ciherang', 'kategori' => 'Pangan', 'satuan' => 'kg', 'harga_acuan' => 12500, 'icon' => 'rice'],
            ['nama_komoditas' => 'Jagung Manis', 'kategori' => 'Pangan', 'satuan' => 'kg', 'harga_acuan' => 8100, 'icon' => 'corn'],
            ['nama_komoditas' => 'Cabai Merah', 'kategori' => 'Hortikultura', 'satuan' => 'kg', 'harga_acuan' => 43200, 'icon' => 'chili'],
            ['nama_komoditas' => 'Kentang Lokal', 'kategori' => 'Umbi-umbian', 'satuan' => 'kg', 'harga_acuan' => 18000, 'icon' => 'spud'],
            ['nama_komoditas' => 'Kedelai', 'kategori' => 'Palawija', 'satuan' => 'kg', 'harga_acuan' => 9900, 'icon' => 'soy'],
        ])->mapWithKeys(function (array $item) use ($categories, $organization): array {
            $commodity = Commodity::query()->updateOrCreate(
                ['organization_id' => $organization->id, 'nama_komoditas' => $item['nama_komoditas']],
                [
                    'kategori_id' => $categories[$item['kategori']]->id,
                    'satuan' => $item['satuan'],
                    'harga_acuan' => $item['harga_acuan'],
                    'icon' => $item['icon'],
                    'is_active' => $item['nama_komoditas'] !== 'Kedelai',
                ]
            );

            return [$item['nama_komoditas'] => $commodity];
        });

        $market = Market::query()->updateOrCreate(
            ['organization_id' => $organization->id, 'nama_pasar' => 'Pasar Desa Sukamaju'],
            [
                'tipe' => 'tradisional',
                'alamat_lengkap' => 'Jl. Raya Sukamaju No. 12',
                'latitude' => -6.914744,
                'longitude' => 107.609810,
                'is_active' => true,
            ]
        );

        DailyPrice::query()->updateOrCreate(
            ['organization_id' => $organization->id, 'id_pasar' => $market->id, 'tanggal' => now()->subDay()->toDateString()],
            [
                'data_harga' => [
                    $commodities['Padi Ciherang']->id => 12200,
                    $commodities['Jagung Manis']->id => 8100,
                    $commodities['Cabai Merah']->id => 43900,
                    $commodities['Kentang Lokal']->id => 17800,
                ],
                'status' => 'verified',
                'created_by' => $admin->id,
            ]
        )->items()->delete();

        $yesterday = DailyPrice::query()
            ->where('organization_id', $organization->id)
            ->where('id_pasar', $market->id)
            ->whereDate('tanggal', now()->subDay()->toDateString())
            ->first();

        foreach ($yesterday->data_harga as $commodityId => $price) {
            $yesterday->items()->updateOrCreate(['commodity_id' => (int) $commodityId], ['price' => $price]);
        }

        DailyPrice::query()->updateOrCreate(
            ['organization_id' => $organization->id, 'id_pasar' => $market->id, 'tanggal' => now()->toDateString()],
            [
                'data_harga' => [
                    $commodities['Padi Ciherang']->id => 12500,
                    $commodities['Jagung Manis']->id => 8100,
                    $commodities['Cabai Merah']->id => 43200,
                    $commodities['Kentang Lokal']->id => 18000,
                ],
                'status' => 'verified',
                'created_by' => $admin->id,
            ]
        )->items()->delete();

        $today = DailyPrice::query()
            ->where('organization_id', $organization->id)
            ->where('id_pasar', $market->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        foreach ($today->data_harga as $commodityId => $price) {
            $today->items()->updateOrCreate(['commodity_id' => (int) $commodityId], ['price' => $price]);
        }

        $harvestSeeds = [
            ['organization_id' => $organization->id, 'user_id' => $farmer->id, 'commodity_id' => $commodities['Padi Ciherang']->id, 'harvest_date' => now()->subDays(2)->toDateString(), 'location' => 'Blok Utara 02', 'quantity' => 450, 'unit' => 'kg', 'quality' => 'Grade A', 'note' => 'Cuaca cerah, gabah kering siap jemur.', 'status' => 'terverifikasi'],
            ['organization_id' => $organization->id, 'user_id' => $farmer->id, 'commodity_id' => $commodities['Jagung Manis']->id, 'harvest_date' => now()->subDays(4)->toDateString(), 'location' => 'Lahan Timur 03', 'quantity' => 280, 'unit' => 'kg', 'quality' => 'Grade B', 'note' => 'Bagian timur lahan, kadar air stabil.', 'status' => 'terverifikasi'],
            ['organization_id' => $organization->id, 'user_id' => $sari->id, 'commodity_id' => $commodities['Cabai Merah']->id, 'harvest_date' => now()->subDays(5)->toDateString(), 'location' => 'Kebun Lereng', 'quantity' => 96, 'unit' => 'kg', 'quality' => 'Grade A', 'note' => 'Perlu sortir lanjutan.', 'status' => 'menunggu'],
            ['organization_id' => $organization->id, 'user_id' => $sari->id, 'commodity_id' => $commodities['Kentang Lokal']->id, 'harvest_date' => now()->subDays(7)->toDateString(), 'location' => 'Petak Barat 04', 'quantity' => 320, 'unit' => 'kg', 'quality' => 'Grade A', 'note' => 'Lahan basah, cek penyimpanan.', 'status' => 'butuh-review'],
        ];

        foreach ($harvestSeeds as $harvest) {
            HarvestLog::query()->updateOrCreate(
                [
                    'user_id' => $harvest['user_id'],
                    'organization_id' => $organization->id,
                    'commodity_id' => $harvest['commodity_id'],
                    'harvest_date' => $harvest['harvest_date'],
                    'location' => $harvest['location'],
                ],
                $harvest
            );
        }
    }
}
