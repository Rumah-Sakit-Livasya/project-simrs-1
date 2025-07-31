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
        Schema::create('procurement_purchase_order_non_pharmacy_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('po_id')->constrained("procurement_purchase_order_non_pharmacy")->onDelete('cascade');
            $table->foreignId("pri_id")->nullable()->constrained("procurement_purchase_request_non_pharmacy_items")->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->comment("barang non farmasi")->constrained("warehouse_barang_non_farmasi")->onDelete('cascade');
            $table->string("kode_barang");
            $table->string("nama_barang");
            $table->string("unit_barang");
            $table->integer("harga_barang");
            $table->integer("qty");
            $table->integer("qty_bonus");
            $table->integer("qty_received")->default(0);
            $table->integer("discount_nominal");
            $table->integer("subtotal");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_purchase_order_non_pharmacy_items');
    }
};
