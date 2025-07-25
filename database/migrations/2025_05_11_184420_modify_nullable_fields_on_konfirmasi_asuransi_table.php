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
            $table->string('invoice')->nullable()->change();
            $table->text('keterangan')->nullable()->change();
            $table->date('jatuh_tempo')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->string('invoice')->nullable(false)->change();
            $table->text('keterangan')->nullable(false)->change();
            $table->date('jatuh_tempo')->nullable(false)->change();
        });
    }
};
