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
        Schema::table('rooms', function (Blueprint $table) {
            // Menggunakan tipe boolean lebih efisien untuk true/false
            // Default false berarti semua ruangan awalnya tidak ter-mapping
            $table->boolean('aplicare_mapping')->default(false)->after('keterangan');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('aplicare_mapping');
        });
    }
};
