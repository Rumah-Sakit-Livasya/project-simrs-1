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
        Schema::table('farmasi_resep_items', function (Blueprint $table) {
            $table->unsignedBigInteger('rhi_id')->nullable()->after('racikan_id');
            $table->foreign('rhi_id')->references('id')->on('farmasi_resep_harian_items')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmasi_resep_items', function (Blueprint $table) {
            $table->dropForeign(['rhi_id']);
            $table->dropColumn('rhi_id');
        });
    }
};
