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
        Schema::create('rujuk_antar_rs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('tgl_masuk');
            $table->string('nama_ts');
            $table->string('nama_pasien');
            $table->string('alamat_pasien');
            $table->string('nama_rs');
            $table->string('rs_tujuan');
            $table->string('dokter_penerima');
            $table->string('umur_pasien');
            $table->string('alasan_keluar');
            $table->text('pemeriksaan_laboratorium')->nullable();
            $table->text('pemeriksaan_radiologi')->nullable();
            $table->text('pemeriksaan_lainnya')->nullable();
            $table->text('diagnosa_masuk')->nullable();
            $table->text('tindakan_dan_terapi')->nullable();
            $table->text('alasan_dirujuk')->nullable();
            $table->string('edukasi_pasien')->nullable();
            $table->string('dpjp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rujuk_antar_rs');
    }
};
