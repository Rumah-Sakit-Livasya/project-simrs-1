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
        Schema::create('warehouse_penerimaan_barang_farmasi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            // Adding requested columns
            $table->date('tanggal_terima');
            $table->date('tanggal_faktur')->nullable();
            $table->string('kode_penerimaan');
            $table->string('no_faktur');
            $table->string('pic_penerima')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer("ppn");
            $table->integer("ppn_nominal");
            $table->integer("materai")->default(0);
            $table->integer("diskon_faktur")->default(0);
            $table->integer("total")->comment("sebelum ppn & diskon");
            $table->integer("total_final")->comment("setelah ppn & diskon");
            $table->foreignId('user_id')->constrained("users")->onDelete('cascade'); // Assuming 'users' table exists and has an 'id' column
            $table->foreignId('gudang_id')->constrained('warehouse_master_gudang', 'id');
            $table->foreignId('supplier_id')->nullable()->constrained('warehouse_supplier', 'id')->comment('Foreign Key to WarehouseSupplier Table');
            $table->foreignId('po_id')->nullable()->constrained('procurement_purchase_order_pharmacy', 'id')->comment('Foreign Key to ProcurementPurchaseOrderPharmacy Table');
            $table->enum('tipe_bayar', ['non_cash', 'cash']);
            $table->enum("tipe_terima" , ["po", "npo"])->default("po")->comment("PO / Non PO");
            $table->enum("status" , ["draft", "final"])->default("draft");

            // temporary
            $table->string("kas")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_penerimaan_barang_farmasi');
    }
};
