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
        Schema::create('warehouse_stock_adjustment_item', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('sa_id')->constrained('warehouse_stock_adjustment', 'id')->comment('Foreign Key to WarehouseStockAdjustment Table');
            $table->foreignId('si_f_id')->nullable()->constrained('stored_barang_farmasi', 'id')->comment('Foreign Key to StoredBarangFarmasi Table');
            $table->foreignId('si_nf_id')->nullable()->constrained('stored_barang_non_farmasi', 'id')->comment('Foreign Key to StoredBarangNonFarmasi Table');
            $table->integer("qty")->comment("Quantity of the adjustment. Not to be confused with the final quantity. Signed.");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_adjustment_item');
    }
};
