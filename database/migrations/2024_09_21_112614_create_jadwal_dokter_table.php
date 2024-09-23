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
        Schema::create('jadwal_dokter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');  // Relasi ke tabel dokter
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');  // Relasi ke tabel dokter
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);  // Hari Senin sampai Minggu
            $table->time('jam_mulai');  // Jam mulai praktik
            $table->time('jam_selesai');  // Jam selesai praktik
            $table->timestamps();  // created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokter');
    }
};
