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
        Schema::create('warehouse_distribusi_barang_farmasi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date("tanggal_db");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId('asal_gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId('tujuan_gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId("sr_id")->nullable()->constrained('warehouse_stock_request_pharmacy', 'id')->comment('Foreign Key to WarehouseStockRequestPharmacy Table');
            $table->string("kode_db");
            $table->string("keterangan")->nullable();
            $table->enum("status", ["draft", "final"])->default("draft");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_distribusi_barang_farmasi');
    }
};
