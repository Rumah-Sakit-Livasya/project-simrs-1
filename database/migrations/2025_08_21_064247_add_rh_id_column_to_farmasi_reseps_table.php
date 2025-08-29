<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('farmasi_reseps', function (Blueprint $table) {
            $table->unsignedBigInteger('rh_id')->nullable()->after('re_id');
            $table->foreign('rh_id')->references('id')->on('farmasi_resep_harians')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmasi_reseps', function (Blueprint $table) {
            $table->dropForeign(['rh_id']);
            $table->dropColumn('rh_id');
        });
    }
};
