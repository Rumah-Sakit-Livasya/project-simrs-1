<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranApSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_ap_supplier_details', function (Blueprint $table) {
            $table->id();

            // Foreign Key ke tabel header pembayaran
            $table->foreignId('pembayaran_ap_header_id')
                ->constrained('pembayaran_ap_supplier_headers')
                ->onDelete('cascade'); // Jika header dihapus, detail ikut terhapus

            // Foreign Key ke invoice yang dibayar
            $table->foreignId('ap_supplier_header_id')
                ->constrained('ap_supplier_header')
                ->onDelete('restrict'); // Jangan hapus invoice jika sudah ada pembayaran

            // Detail Nominal
            $table->decimal('nominal_pembayaran', 15, 2);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);

            // Detail biasanya tidak butuh timestamps, tapi bisa ditambahkan jika perlu
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_ap_supplier_details');
    }
}
