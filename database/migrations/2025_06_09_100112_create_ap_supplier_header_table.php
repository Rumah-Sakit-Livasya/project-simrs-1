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
    public function up()
    {
        Schema::create('ap_supplier_header', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ap', 25)->unique();

            // Relasi ke tabel supplier yang sudah ada
            $table->foreignId('supplier_id')->constrained('warehouse_supplier');

            $table->string('no_invoice_supplier', 100);
            $table->date('tanggal_ap');
            $table->date('tanggal_invoice_supplier')->nullable();
            $table->date('due_date'); // Tanggal Jatuh Tempo
            $table->date('tanggal_faktur_pajak')->nullable();

            // Kolom untuk nominal
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon_final', 15, 2)->default(0);
            $table->decimal('ppn_persen', 5, 2)->default(0);
            $table->decimal('ppn_nominal', 15, 2)->default(0);
            $table->decimal('biaya_lainnya', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas Sebagian', 'Lunas'])->default('Belum Lunas');

            // Relasi ke tabel user (jika ada)
            $table->foreignId('user_entry_id')->constrained('users');

            // Kelengkapan Dokumen (berdasarkan checkbox di UI)
            $table->boolean('ada_kwitansi')->default(false);
            $table->boolean('ada_faktur_pajak')->default(false);
            $table->boolean('ada_surat_jalan')->default(false);
            $table->boolean('ada_salinan_po')->default(false);
            $table->boolean('ada_tanda_terima_barang')->default(false);
            $table->boolean('ada_berita_acara')->default(false);

            $table->timestamps(); // otomatis membuat created_at dan updated_at
            $table->softDeletes(); // otomatis membuat deleted_at untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ap_supplier_header');
    }
};
