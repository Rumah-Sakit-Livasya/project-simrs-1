<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSisaHutangToApSupplierHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            // Tambahkan kolom sisa_hutang setelah kolom grand_total
            $table->decimal('sisa_hutang', 15, 2)->default(0)->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ap_supplier_header', function (Blueprint $table) {
            // Definisi untuk rollback (menghapus kolom jika migrasi dibatalkan)
            $table->dropColumn('sisa_hutang');
        });
    }
}
