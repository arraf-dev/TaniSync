<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commodity_id')->constrained('komoditas')->onDelete('cascade');
            $table->date('harvest_date');
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 20)->default('kg');
            $table->string('location', 120);
            $table->string('quality', 50)->default('Grade A');
            $table->text('note')->nullable();
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvests');
    }
};
