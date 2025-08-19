<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            // Tambahkan kolom tipe setelah 'kode_ap'
            $table->enum('ap_type', ['PO', 'Non-PO'])->default('PO')->after('kode_ap');
        });
    }

    public function down(): void
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            $table->dropColumn('ap_type');
        });
    }
};
