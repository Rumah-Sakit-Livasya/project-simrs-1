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
        Schema::table('warehouse_master_gudang', function (Blueprint $table) {
            $table->dropColumn('apotek_default');
            $table->boolean('rajal_default')->default(false);
            $table->boolean('ranap_default')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_master_gudang', function (Blueprint $table) {
            $table->dropColumn('rajal_default');
            $table->dropColumn('ranap_default');
            $table->boolean('apotek_default')->default(false);
        });
    }
};
