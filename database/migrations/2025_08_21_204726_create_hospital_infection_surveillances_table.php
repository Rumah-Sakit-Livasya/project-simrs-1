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
        Schema::create('hospital_infection_surveillances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('User yang mengisi/memperbarui');

            // Data Pasien Awal
            $table->timestamp('tgl_masuk')->nullable();
            $table->string('cara_dirawat')->nullable(); // Emergency, Elektif
            $table->text('diagnosa_masuk')->nullable();
            $table->string('pindah_ke_ruangan')->nullable();
            $table->date('tgl_pindah')->nullable();

            // Menggunakan JSON untuk data yang kompleks dan berulang
            $table->json('faktor_resiko')->nullable();
            $table->json('faktor_penyakit')->nullable();
            $table->json('tindakan_operasi')->nullable();
            $table->json('komplikasi_infeksi')->nullable();
            $table->json('pemakaian_antimikroba')->nullable();

            // Data Pasien Keluar
            $table->date('tgl_keluar')->nullable();
            $table->string('keterangan_keluar')->nullable(); // BLPL, MENINGGAL, APS
            $table->string('pindah_rs_lain')->nullable();
            $table->text('diagnosa_akhir')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_infection_surveillances');
    }
};
