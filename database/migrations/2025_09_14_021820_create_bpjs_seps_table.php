<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjs_seps', function (Blueprint $table) {
            $table->id(); // Kolom primary key auto-increment

            // Foreign Key ke tabel registrations
            // Sesuaikan 'registrations' dan 'id' jika nama tabel/kolom Anda berbeda
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');

            // Kolom data SEP
            $table->string('sep_number')->unique(); // Nomor SEP harus unik
            $table->date('sep_date'); // Tanggal SEP

            // Kolom lain yang mungkin relevan dari data SEP (contoh)
            $table->string('patient_name')->nullable(); // Nama pasien (redundant tapi sering disimpan)
            $table->string('medical_record_number')->nullable(); // No Rekam Medis
            $table->string('nokartu')->nullable(); // Nomor Kartu BPJS
            $table->string('diagnosa')->nullable(); // Diagnosa
            $table->string('kelasrawat')->nullable(); // Kelas Rawat
            $table->string('jnspelayanan')->nullable(); // Jenis Pelayanan (RJ/RI)
            $table->string('poli_eksekutif')->nullable(); // Poli Eksekutif/BPJS
            $table->string('norujukan')->nullable(); // Nomor Rujukan
            $table->string('ppkrujukan')->nullable(); // PPK Perujuk
            $table->string('ppkpelayanan')->nullable(); // PPK Pelayanan
            $table->string('catatan')->nullable(); // Catatan SEP

            // Kolom timestamp default Laravel
            $table->timestamps();

            // Jika Anda perlu menambahkan soft delete untuk tabel ini juga
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bpjs_seps');
    }
};
