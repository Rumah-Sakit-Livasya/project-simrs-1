<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('procurement_purchase_request_pharmacy_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId("pr_id")->constrained('procurement_purchase_request_pharmacy')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->comment("barang farmasi")->constrained('warehouse_barang_farmasi')->onDelete('cascade');
            $table->foreignId("satuan_id")->constrained("warehouse_satuan_barang")->onDelete('cascade');
            $table->string("kode_barang");
            $table->string("nama_barang");
            $table->string("unit_barang");
            $table->integer("harga_barang");
            $table->integer("qty");
            $table->integer("subtotal");
            $table->enum("status" , ["unprocessed", "pending", "approved", "rejected"])->default("unprocessed");
            $table->integer("approved_qty")->default(0);
            $table->string("keterangan")->nullable();
            $table->string("keterangan_approval")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_purchase_request_pharmacy_items');
    }
};
