<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_retur_barang', function (Blueprint $table) {
            // Kolom ini akan melacak apakah retur sudah dipakai di AP atau belum
            $table->string('status_ap')->default('Belum Digunakan')->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_retur_barang', function (Blueprint $table) {
            $table->dropColumn('status_ap');
        });
    }
};
