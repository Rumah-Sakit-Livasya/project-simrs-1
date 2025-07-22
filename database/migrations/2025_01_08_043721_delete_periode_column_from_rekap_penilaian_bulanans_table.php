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
        Schema::rename('rekap_penilaian_bulanans', 'rekap_penilaian');
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ganti nama tabel kembali ke rekap_penilaian_bulanan
        Schema::rename('rekap_penilaian', 'rekap_penilaian_bulanans');

        // Tambahkan kembali kolom 'periode'
        Schema::table('rekap_penilaian_bulanans', function (Blueprint $table) {
            $table->string('periode')->nullable(); // Sesuaikan tipe data dan sifat nullable jika diperlukan
        });
    }
};
