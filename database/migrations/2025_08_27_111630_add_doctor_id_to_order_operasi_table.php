<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdToOrderOperasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_operasi', function (Blueprint $table) {
            // Tambahkan kolom doctor_id setelah registration_id
            $table->unsignedBigInteger('dokter_operator_id')->nullable()->after('registration_id');

            // Tambahkan foreign key constraint ke tabel 'doctors'
            $table->foreign('dokter_operator_id')
                ->references('id')
                ->on('doctors')
                ->onDelete('set null'); // Jika dokter dihapus, kolom ini akan menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_operasi', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['dokter_operator_id']);
            // Hapus kolomnya
            $table->dropColumn('dokter_operator_id');
        });
    }
}
