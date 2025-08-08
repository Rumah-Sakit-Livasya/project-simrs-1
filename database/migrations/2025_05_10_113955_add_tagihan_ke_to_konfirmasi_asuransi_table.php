<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->foreignId('tagihan_ke')->constrained('penjamins')->cascadeOnDelete();
            $table->string('status')->nullable()->after('tagihan_ke');
        });
    }

    public function down()
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['tagihan_ke']);

            // Kemudian baru hapus kolomnya
            $table->dropColumn('tagihan_ke');
            $table->dropColumn('status');
        });
    }
};
