<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTanggalColumnOnKonfirmasiAsuransiTable extends Migration
{
    public function up()
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            // Gunakan raw SQL karena Laravel tidak support direct alter tipe kolom
            DB::statement('ALTER TABLE konfirmasi_asuransi MODIFY tanggal DATETIME');
        });
    }

    public function down()
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            // Kembalikan ke DATE jika dibatalkan
            DB::statement('ALTER TABLE konfirmasi_asuransi MODIFY tanggal DATE');
        });
    }
}
