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
        Schema::create('warehouse_penerimaan_barang_farmasi_item', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('pb_id')->constrained('warehouse_penerimaan_barang_farmasi')->onDelete('cascade');
            $table->foreignId('poi_id')->nullable()->constrained('procurement_purchase_order_pharmacy_items')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('warehouse_barang_farmasi')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('warehouse_satuan_barang')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('kode_barang');
            $table->string('unit_barang');
            $table->string('batch_no');
            $table->date('tanggal_exp');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('diskon_nominal');
            $table->integer('subtotal');
            $table->boolean("is_bonus")->default(false);
            $table->boolean("distributed")->default(false)->comment("jika sudah di distribusi, tidak boleh di edit lagi dan harus pengajuan untuk perbaikan.");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_penerimaan_barang_farmasi_item');
    }
};
