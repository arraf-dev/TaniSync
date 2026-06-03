<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_price_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_price_id')->constrained('harga_bapok_harian')->cascadeOnDelete();
            $table->foreignId('commodity_id')->constrained('komoditas')->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->timestamps();
            $table->unique(['daily_price_id', 'commodity_id']);
        });

        if (Schema::hasTable('harga_bapok_harian')) {
            $now = now();
            DB::table('harga_bapok_harian')
                ->orderBy('id')
                ->get()
                ->each(function (object $dailyPrice) use ($now): void {
                    $prices = json_decode((string) $dailyPrice->data_harga, true);

                    if (! is_array($prices)) {
                        return;
                    }

                    foreach ($prices as $commodityId => $price) {
                        if ($price === null || $price === '') {
                            continue;
                        }

                        DB::table('daily_price_items')->updateOrInsert(
                            [
                                'daily_price_id' => $dailyPrice->id,
                                'commodity_id' => (int) $commodityId,
                            ],
                            [
                                'price' => (float) $price,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]
                        );
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_price_items');
    }
};
