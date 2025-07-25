<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranApSupplierHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_ap_supplier_headers', function (Blueprint $table) {
            // Kolom Utama
            $table->id();
            $table->string('kode_pembayaran', 25)->unique();
            $table->date('tanggal_pembayaran');

            // Foreign Keys
            $table->foreignId('supplier_id')->constrained('warehouse_supplier')->onDelete('restrict');
            $table->foreignId('kas_bank_id')->constrained('banks')->onDelete('restrict'); // Pastikan nama tabel 'kas_bank' sudah benar
            $table->foreignId('user_entry_id')->constrained('users')->onDelete('restrict');

            // Detail Pembayaran
            $table->enum('metode_pembayaran', ['Transfer', 'Giro', 'Tunai']);
            $table->string('no_referensi', 100)->nullable();
            $table->decimal('total_pembayaran', 15, 2);
            $table->decimal('pembulatan', 10, 2)->default(0);

            // Lain-lain
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur "batal bayar" di masa depan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_ap_supplier_headers');
    }
}
