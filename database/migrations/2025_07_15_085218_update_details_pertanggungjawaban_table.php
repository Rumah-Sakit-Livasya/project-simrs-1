<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDetailsPertanggungjawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pertanggungjawaban_details', function (Blueprint $table) {
            // Tambahkan kolom foreign key baru
            $table->unsignedBigInteger('transaksi_rutin_id')->nullable()->after('pertanggungjawaban_id');
            $table->unsignedBigInteger('rnc_center_id')->nullable()->after('transaksi_rutin_id');

            // Tambahkan foreign key constraint
            $table->foreign('transaksi_rutin_id')->references('id')->on('transaksi_rutins');
            $table->foreign('rnc_center_id')->references('id')->on('rnc_centers');

            // Hapus kolom lama jika sudah tidak digunakan
            $table->dropColumn(['tipe_transaksi', 'cost_center']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pertanggungjawaban_details', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->string('tipe_transaksi', 255)->nullable();
            $table->string('cost_center', 255)->nullable();

            // Hapus foreign key dan kolom baru
            $table->dropForeign(['transaksi_rutin_id']);
            $table->dropForeign(['rnc_center_id']);
            $table->dropColumn(['transaksi_rutin_id', 'rnc_center_id']);
        });
    }
}
