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
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas', 100); // [cite: 4]
            $table->string('satuan', 20)->default('kg'); // [cite: 4]
            $table->decimal('harga_acuan', 12, 2)->nullable(); // [cite: 4]
            $table->foreignId('kategori_id')->constrained('kategori_komoditas')->onDelete('cascade'); // 
            $table->string('image_path')->nullable(); // Modernized from 'images' [cite: 4]
            $table->string('icon', 10)->nullable(); // For emojis [cite: 4]
            $table->boolean('is_active')->default(true); // [cite: 4]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};
