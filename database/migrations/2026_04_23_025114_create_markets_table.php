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
        Schema::create('pasar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasar', 100); // [cite: 5]
            $table->enum('tipe', ['tradisional', 'modern', 'campuran'])->default('tradisional'); // [cite: 5]
            $table->text('alamat_lengkap')->nullable(); // [cite: 5]
            $table->decimal('latitude', 10, 8)->nullable(); // [cite: 6]
            $table->decimal('longitude', 11, 8)->nullable(); // [cite: 6]
            $table->string('image_pasar')->nullable(); // [cite: 6]
            $table->boolean('is_active')->default(true); // [cite: 6]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasar');
    }
};
