<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_xxxxxx_add_laporan_internal_id_to_maintenance_barang_table.php

    public function up(): void
    {
        Schema::table('maintenance_barang', function (Blueprint $table) {
            $table->foreignId('laporan_internal_id')->nullable()->after('id')
                ->constrained('laporan_internal')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_barang', function (Blueprint $table) {
            $table->dropForeign(['laporan_internal_id']);
            $table->dropColumn('laporan_internal_id');
        });
    }
};
