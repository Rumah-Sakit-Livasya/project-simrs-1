<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_distribusi_barang_farmasi_item', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('db_id')->constrained('warehouse_distribusi_barang_farmasi', 'id')->comment('Foreign Key to WarehouseDistribusiBarangFarmasi Table');
            $table->foreignId('sri_id')->nullable()->constrained('warehouse_stock_request_pharmacy_item', 'id')->comment('Foreign Key to WarehouseStockRequestPharmacyItems Table');
            $table->foreignId('barang_id')->constrained('warehouse_barang_non_farmasi')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->integer("qty")->unsigned();
            $table->string("keterangan")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_distribusi_barang_farmasi_item');
    }
};
