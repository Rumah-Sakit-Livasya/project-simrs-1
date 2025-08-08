<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTanggalColumnKonfirmasiAsuransi extends Migration
{
    public function up()
    {
        // Buat kolom sementara
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->dateTime('tanggal_baru')->nullable();
        });

        // Salin isi kolom lama ke kolom baru
        DB::statement('UPDATE konfirmasi_asuransi SET tanggal_baru = tanggal');

        // Hapus kolom lama
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        // Ubah nama kolom baru ke nama semula
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->renameColumn('tanggal_baru', 'tanggal');
        });
    }

    public function down()
    {
        // Rollback: dari datetime ke date
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->date('tanggal_lama')->nullable();
        });

        DB::statement('UPDATE konfirmasi_asuransi SET tanggal_lama = tanggal');

        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->renameColumn('tanggal_lama', 'tanggal');
        });
    }
}
