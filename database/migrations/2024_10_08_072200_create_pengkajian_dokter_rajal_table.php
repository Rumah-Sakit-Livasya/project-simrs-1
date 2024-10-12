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
        Schema::create('pengkajian_dokter_rajal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();

            //======== TTV =========

            $table->string('pr')->nullable(); // nadi
            $table->string('rr')->nullable(); // respirasi
            $table->string('bp')->nullable(); // tensi
            $table->string('temperatur')->nullable(); // suhu
            $table->string('body_height')->nullable(); // tinggi badan
            $table->string('body_weight')->nullable(); // berat badan
            $table->string('bmi')->nullable(); // index masa tubuh
            $table->string('kat_bmi')->nullable(); // kategori IMT
            $table->string('sp02')->nullable(); // sp02
            $table->string('lingkar_kepala')->nullable(); // lingkar kepala
            $table->string('diagnosa_keperawatan')->nullable();
            $table->string('rencana_tindak_lanjut')->nullable();

            //Pengkajian
            $table->json('asesmen_dilakukan_melalui')->nullable();
            $table->date('awal_tgl_rajal');
            $table->time('awal_jam_rajal');
            $table->text('awal_keluhan');
            $table->string('awal_riwayat_penyakit_sekarang')->nullable();
            $table->string('awal_riwayat_penyakit_dahulu')->nullable();
            $table->string('awal_riwayat_penyakit_keluarga')->nullable();
            $table->boolean('awal_riwayat_alergi_obat')->default(0);
            $table->string('awal_riwayat_alergi_obat_lain')->nullable();
            $table->string('awal_pemeriksaan_fisik');
            $table->string('awal_pemeriksaan_penunjang');
            $table->string('awal_diagnosa_kerja');
            $table->string('awal_diagnosa_banding')->nullable();
            $table->string('awal_terapi_tindakan')->nullable();
            $table->json('awal_edukasi')->nullable();
            $table->json('awal_evaluasi_penyakit')->nullable();
            $table->json('awal_rencana_tindak_lanjut')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_final')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengkajian_dokter_rajal');
    }
};
