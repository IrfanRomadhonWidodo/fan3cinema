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
        Schema::create('jadwal_tayang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained('film')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studio')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam');
            $table->timestamps();
            // Validasi agar tidak ada jadwal ganda untuk studio, tanggal, dan jam yang sama
            $table->unique(['studio_id', 'tanggal', 'jam'], 'unique_studio_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tayang');
    }
};
