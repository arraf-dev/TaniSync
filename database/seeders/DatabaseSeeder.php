<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Users
        User::query()->updateOrCreate(
            ['email' => 'superadmin@tanisync.id'],
            [
                'name' => 'Super Admin',
                'village' => 'Desa Sukamaju',
                'role' => 'superadmin',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@tanisync.id'],
            [
                'name' => 'Admin Gapoktan',
                'village' => 'Desa Sukamaju',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'rahmat@tanisync.id'],
            [
                'name' => 'Bapak Rahmat',
                'village' => 'Desa Sukamaju',
                'role' => 'petani',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        // Domain data (order matters due to FK dependencies)
        $this->call([
            CategorySeeder::class,
            CommoditySeeder::class,
            MarketSeeder::class,
            DailyPriceSeeder::class,
            HarvestSeeder::class,
        ]);
    }
}
