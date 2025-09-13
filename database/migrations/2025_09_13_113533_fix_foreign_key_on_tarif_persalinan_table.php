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
        Schema::table('tarif_persalinan', function (Blueprint $table) {
            // 1. Hapus foreign key yang lama/salah
            // Nama constraint biasanya: {nama_tabel}_{nama_kolom}_foreign
            $table->dropForeign('tarif_persalinan_persalinan_id_foreign');

            // 2. Tambahkan foreign key yang baru dan benar
            $table->foreign('persalinan_id')
                ->references('id')
                ->on('persalinan') // <-- Nama tabel yang benar
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarif_persalinan', function (Blueprint $table) {
            // Balikkan prosesnya jika migrasi di-rollback

            // 1. Hapus foreign key yang baru
            // Laravel akan otomatis menamai constraint ini, tapi kita bisa definisikan
            // Untuk amannya kita drop berdasarkan kolom saja
            $table->dropForeign(['persalinan_id']);

            // 2. Tambahkan lagi foreign key yang lama
            $table->foreign('persalinan_id', 'tarif_persalinan_persalinan_id_foreign')
                ->references('id')
                ->on('daftar_persalinan') // <-- Nama tabel yang salah
                ->onDelete('cascade');
        });
    }
};
