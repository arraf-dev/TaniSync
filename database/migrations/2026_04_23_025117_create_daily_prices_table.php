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
        Schema::create('harga_bapok_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasar')->constrained('pasar')->onDelete('cascade'); // [cite: 1, 27]
            $table->date('tanggal')->index(); // [cite: 1, 11]
            $table->json('data_harga'); // Format: {"commodity_id": price} 
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft'); // 
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // [cite: 1, 27]
            $table->timestamps();
            $table->unique(['id_pasar', 'tanggal']); // Prevents duplicate entries for the same day [cite: 11]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_bapok_harian');
    }
};
