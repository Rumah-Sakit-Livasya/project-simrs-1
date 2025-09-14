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
        Schema::table('departements', function (Blueprint $table) {
            $table->enum('category', ['poliklinik', 'rawat_inap', 'penunjang_medis', 'lainnya'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Ubah kembali kolom category ke tipe sebelumnya (misal: string, atau enum dengan value berbeda)
            // Contoh: jika sebelumnya string dan tidak nullable
            $table->string('category')->nullable(false)->change();
        });
    }
};
