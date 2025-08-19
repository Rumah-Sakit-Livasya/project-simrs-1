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
        Schema::create('ap_supplier_detail', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel master AP. Jika header dihapus, detail ikut terhapus.
            $table->foreignId('ap_supplier_header_id')
                ->constrained('ap_supplier_header')
                ->onDelete('cascade');

            // Relasi ke tabel GRN.
            $table->integer('penerimaan_barang_id');
            $table->enum('penerimaan_barang_type', ['farmasi', 'non_farmasi']);

            // Menyimpan nominal GRN sebagai arsip saat AP dibuat
            $table->decimal('nominal_grn', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ap_supplier_detail');
    }
};
