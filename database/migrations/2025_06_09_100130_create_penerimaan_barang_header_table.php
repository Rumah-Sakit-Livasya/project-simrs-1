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

            // Relasi ke supplier (ini tetap sama)
            $table->foreignId('supplier_id')->constrained('warehouse_supplier');

            // ==== PERUBAHAN UTAMA: RELASI POLIMORFIK KE PO ====
            // Ini akan membuat kolom 'purchasable_id' (BIGINT) dan 'purchasable_type' (VARCHAR)
            $table->morphs('purchasable');

            $table->string('no_surat_jalan_supplier', 100)->nullable();
            $table->decimal('total_nilai_barang', 15, 2)->default(0);
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
