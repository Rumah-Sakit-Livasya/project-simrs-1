<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ap_supplier_detail', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('penerimaan_barang_header_id');

            // Tambahkan kolom polimorfik yang baru
            $table->unsignedBigInteger('penerimaan_barang_id')->nullable()->after('ap_supplier_header_id');
            $table->string('penerimaan_barang_type')->nullable()->after('penerimaan_barang_id');
        });
    }

    public function down(): void
    {
        Schema::table('ap_supplier_details', function (Blueprint $table) {
            $table->dropColumn(['penerimaan_barang_id', 'penerimaan_barang_type']);
            $table->unsignedBigInteger('penerimaan_barang_header_id');
        });
    }
};
