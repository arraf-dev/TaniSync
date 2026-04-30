<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catatan_panen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commodity_id')->constrained('komoditas')->onDelete('cascade');
            $table->date('harvest_date')->index();
            $table->string('location', 120);
            $table->decimal('quantity', 12, 2);
            $table->string('unit', 20)->default('kg');
            $table->string('quality', 80)->default('Grade B');
            $table->text('note')->nullable();
            $table->enum('status', ['menunggu', 'terverifikasi', 'butuh-review'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_panen');
    }
};
