<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bpjs_rencana_kontrols', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke tabel registrations, unik karena satu registrasi hanya punya satu rencana kontrol
            $table->foreignId('registration_id')->unique()->constrained()->onDelete('cascade');

            $table->string('no_surat_kontrol')->unique();
            $table->date('tgl_rencana_kontrol');
            $table->string('poli_kontrol_kode');
            $table->string('poli_kontrol_nama');
            $table->string('dokter_kode');
            $table->string('dokter_nama');
            $table->string('jenis_kontrol'); // Cth: 1 (SPRI), 2 (Surat Kontrol)

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bpjs_rencana_kontrols');
    }
};
