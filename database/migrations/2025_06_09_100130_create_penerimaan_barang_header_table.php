<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ... file: ..._create_penerimaan_barang_header_table.php

    public function up()
    {
        Schema::create('penerimaan_barang_header', function (Blueprint $table) {
            $table->id();
            $table->string('no_grn', 25)->unique();
            $table->date('tanggal_penerimaan');

            // Relasi ke supplier dan purchase order
            $table->foreignId('supplier_id')->constrained('warehouse_supplier');

            // ==== BARIS YANG DIPERBAIKI ====
            $table->foreignId('purchase_id')->constrained('purchases');

            $table->string('no_surat_jalan_supplier', 100)->nullable();
            $table->decimal('total_nilai_barang', 15, 2)->default(0);

            // KOLOM KUNCI untuk alur AP Supplier
            $table->enum('status_ap', ['Belum AP', 'Sudah AP'])->default('Belum AP');

            $table->foreignId('user_penerima_id')->constrained('users');
            $table->text('catatan_penerimaan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penerimaan_barang_header');
    }
};
