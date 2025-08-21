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
        Schema::create('neonatus_initial_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            // Kolom JSON untuk menampung data terstruktur
            $table->dateTime('waktu_masuk_ruangan')->nullable();
            $table->json('info_masuk_ruangan')->nullable();
            $table->json('riwayat_kesehatan')->nullable();
            $table->json('riwayat_kelahiran')->nullable();
            $table->json('pengkajian_khusus_neonatus')->nullable();
            $table->json('keadaan_umum')->nullable();
            $table->json('penilaian_fisik')->nullable();
            $table->json('asesmen_nyeri_neonatus')->nullable();
            $table->json('masalah_keperawatan')->nullable();
            $table->json('pendidikan_kesehatan_pulang')->nullable();
            $table->json('info_bayi_pulang')->nullable();
            $table->dateTime('waktu_pemeriksaan_akhir')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neonatus_initial_assessments');
    }
};
