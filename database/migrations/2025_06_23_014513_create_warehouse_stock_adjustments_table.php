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
        Schema::create('warehouse_stock_adjustment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date("tanggal_sa");
            $table->foreignId("user_id")->constrained("users")->comment("Current logged in user")->cascadeOnDelete();
            $table->foreignId("authorized_user_id")->constrained("warehouse_stock_adjustment_user", "user_id")->comment("Authorized user.")->cascadeOnDelete();
            $table->string("kode_sa");
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId('barang_f_id')->nullable()->constrained('warehouse_barang_farmasi', 'id')->comment('Foreign Key to WarehouseBarangFarmasi Table');
            $table->foreignId('barang_nf_id')->nullable()->constrained('warehouse_barang_non_farmasi', 'id')->comment('Foreign Key to WarehouseBarangNonFarmasi Table');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->string("keterangan")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_adjustment');
    }
};
