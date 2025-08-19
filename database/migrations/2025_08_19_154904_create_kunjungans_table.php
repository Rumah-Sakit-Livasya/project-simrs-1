<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kunjungan');
            $table->foreignId('jenis_kegiatan_id')->constrained('jenis_kegiatans');
            $table->string('ruangan');
            $table->foreignId('user_id')->constrained('users'); // PIC dari tabel users
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
