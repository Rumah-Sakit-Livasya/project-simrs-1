<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAplicareFieldsToKelasRawatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas_rawat', function (Blueprint $table) {
            // Kolom untuk menyimpan kode kelas dari Aplicare BPJS
            $table->string('aplicare_code')->nullable()->unique()->after('isICU');

            // Kolom untuk menyimpan nama kelas dari Aplicare BPJS
            $table->string('aplicare_name')->nullable()->after('aplicare_code');

            // Kolom untuk menyimpan urutan (jika diperlukan)
            $table->integer('aplicare_urutan')->nullable()->after('aplicare_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelas_rawat', function (Blueprint $table) {
            $table->dropColumn(['aplicare_code', 'aplicare_name', 'aplicare_urutan']);
        });
    }
}
