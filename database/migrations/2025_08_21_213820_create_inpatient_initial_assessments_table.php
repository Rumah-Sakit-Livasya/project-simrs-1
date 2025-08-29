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
        Schema::create('inpatient_initial_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            $table->dateTime('waktu_masuk_ruangan')->nullable();
            $table->json('info_masuk_ruangan')->nullable(); // Cara Masuk, Tiba dengan cara
            $table->json('pemeriksaan_dibawa')->nullable();
            $table->json('obat_dibawa')->nullable();
            $table->json('riwayat_kesehatan')->nullable();
            $table->json('riwayat_kesehatan_lalu')->nullable();
            $table->json('riwayat_alergi')->nullable();
            $table->json('riwayat_kesehatan_keluarga')->nullable();
            $table->string('riwayat_pendidikan')->nullable();
            $table->json('riwayat_psikososial')->nullable();
            $table->json('riwayat_komunikasi')->nullable();
            $table->json('riwayat_kebudayaan')->nullable();
            $table->json('respon_emosi_kognitif')->nullable();
            $table->json('informasi_diinginkan')->nullable();
            $table->json('nutrisi')->nullable();
            $table->json('eliminasi')->nullable();
            $table->json('personal_hygiene')->nullable();
            $table->json('istirahat_tidur')->nullable();
            $table->json('aktivitas_latihan')->nullable();
            $table->json('neuro_cerebral')->nullable();
            $table->json('tingkat_kesadaran')->nullable();
            $table->json('pemeriksaan_fisik')->nullable();
            $table->json('asesmen_nyeri')->nullable();
            $table->json('resiko_jatuh_dewasa')->nullable();
            $table->json('masalah_keperawatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inpatient_initial_assessments');
    }
};
