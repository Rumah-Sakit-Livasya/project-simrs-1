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
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            // Kolom ini untuk menyimpan identifier sistem, contoh: 'prosedur_operasi_123'
            // Ini tidak ditampilkan ke user, hanya untuk logika backend.
            // Diletakkan setelah kolom 'tagihan' agar mudah dilihat di database.
            $table->string('deskripsi_sistem')->nullable()->index()->after('tagihan');
        });
    }

    public function down(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->dropColumn('deskripsi_sistem');
        });
    }
};
