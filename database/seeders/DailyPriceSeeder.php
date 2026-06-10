<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\Market;
use App\Models\User;
use Illuminate\Database\Seeder;

class DailyPriceSeeder extends Seeder
{
    public function run(): void
    {
        $market = Market::query()->first();
        $admin = User::query()->where('role', 'admin')->first();

        if (! $market || ! $admin) {
            return;
        }

        $commodities = Commodity::query()->where('is_active', true)->get();

        // Seed 7 days of price data
        foreach (range(0, 6) as $daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();

            $priceData = [];
            foreach ($commodities as $commodity) {
                // Slight daily variation (±5%)
                $variation = 1 + (rand(-5, 5) / 100);
                $priceData[$commodity->id] = round((float) $commodity->harga_acuan * $variation, 0);
            }

            DailyPrice::query()->updateOrCreate(
                ['id_pasar' => $market->id, 'tanggal' => $date],
                [
                    'data_harga' => $priceData,
                    'status' => 'verified',
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
