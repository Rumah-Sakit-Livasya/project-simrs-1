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
        Schema::table('farmasi_resep_items', function (Blueprint $table) {
            // Drop foreign key constraints first
            if (Schema::hasColumn('farmasi_resep_items', 'barang_id')) {
                $table->dropForeign(['barang_id']);
            }
            if (Schema::hasColumn('farmasi_resep_items', 'satuan_id')) {
                $table->dropForeign(['satuan_id']);
            }
            
            // Then drop the columns
            $table->dropColumn('barang_id');
            $table->dropColumn('satuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmasi_resep_items', function (Blueprint $table) {
            // Recreate the columns
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable();
            
            // Recreate foreign key constraints
            if (Schema::hasTable('warehouse_master_barangs')) {
                $table->foreign('barang_id')->references('id')->on('warehouse_master_barangs');
            }
            if (Schema::hasTable('warehouse_master_satuan')) {
                $table->foreign('satuan_id')->references('id')->on('warehouse_master_satuan');
            }
        });
    }
};
