<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoTransaksiToPembayaranTagihanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_tagihan', function (Blueprint $table) {
            $table->string('no_transaksi', 50)->unique()->after('id')->comment('Unique transaction number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_tagihan', function (Blueprint $table) {
            $table->dropColumn('no_transaksi');
        });
    }
}
