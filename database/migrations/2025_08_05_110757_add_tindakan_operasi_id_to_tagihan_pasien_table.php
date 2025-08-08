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
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->foreignId('tindakan_operasi_id')
                ->nullable()
                ->constrained('tindakan_operasi')
                ->after('tindakan_medis_id')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->dropForeign(['tindakan_operasi_id']);
            $table->dropColumn('tindakan_operasi_id');
        });
    }
};
