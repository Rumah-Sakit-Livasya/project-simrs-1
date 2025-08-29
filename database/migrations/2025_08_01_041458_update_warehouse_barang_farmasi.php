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
        Schema::table('warehouse_barang_farmasi', function (Blueprint $table) {
            $table->dropColumn("principal");
            $table->foreignId('principal')->nullable()->constrained('warehouse_pabrik')->onDelete('cascade');
            $table->string("restriksi")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_barang_farmasi', function (Blueprint $table) {
            $table->dropForeign(['principal']);
            $table->dropColumn("principal");
            $table->dropColumn("restriksi");
            $table->string("principal")->nullable();
        });
    }
};
