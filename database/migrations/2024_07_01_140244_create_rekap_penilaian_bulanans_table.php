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
        Schema::create('rekap_penilaian_bulanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('group_penilaian_id');
            $table->string('periode', 100);
            $table->string('tahun', 6);
            $table->string('total_nilai');
            $table->string('keterangan');
            $table->text('catatan')->nullable();
            $table->text('komentar_pegawai')->nullable();
            $table->text('komentar_penilai')->nullable();
            $table->text('komentar_pejabat_penilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_penilaian_bulanans');
    }
};
