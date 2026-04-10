<?php

namespace Database\Seeders;

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
    }
}
