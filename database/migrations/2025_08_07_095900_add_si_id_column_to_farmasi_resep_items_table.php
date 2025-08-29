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
            $table->foreignId('si_id')->nullable()->constrained('stored_barang_farmasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmasi_resep_items', function (Blueprint $table) {
            $table->dropForeign(['si_id']);
            $table->dropColumn('si_id');
        });
    }
};
