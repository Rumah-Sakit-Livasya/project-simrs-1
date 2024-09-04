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
            $table->foreignId('default_dokter')
                ->nullable() // Menyatakan kolom ini bisa bernilai NULL
                ->after('kode_poli')
                ->constrained('employees') // Mengatur foreign key ke tabel 'employees'
                ->nullOnDelete(); // Menentukan aksi ON DELETE SET NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign('departements_default_dokter_foreign');

            // Hapus kolom setelah constraint dihapus
            $table->dropColumn('default_dokter');
        });
    }
};
