<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bpjs_rujukans', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke tabel registrations
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');

            // Kolom data Rujukan
            $table->string('no_rujukan')->unique();
            $table->date('tgl_rujukan');
            $table->string('ppk_dirujuk_kode');
            $table->string('ppk_dirujuk_nama');
            $table->string('diagnosa_kode');
            $table->string('diagnosa_nama');
            $table->string('tipe_rujukan'); // Cth: Penuh, Parsial
            $table->string('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bpjs_rujukans');
    }
};
