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
        Schema::table('tarif_visite_dokters', function (Blueprint $table) {
            // Tambahkan kolom group_penjamin_id sebagai foreign key
            $table->foreignId('group_penjamin_id')
                ->nullable() // Buat nullable untuk sementara agar tidak error pada data lama
                ->after('kelas_rawat_id') // Posisikan setelah kelas_rawat_id
                ->constrained('group_penjamin'); // Merujuk ke tabel 'group_penjamin'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarif_visite_dokters', function (Blueprint $table) {
            // Hapus foreign key dan kolomnya jika migrasi di-rollback
            $table->dropForeign(['group_penjamin_id']);
            $table->dropColumn('group_penjamin_id');
        });
    }
};
