<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ap_non_grn_details', function (Blueprint $table) {
            // Hapus constraint lama (jika ada)
            $table->dropForeign(['cost_center_id']);

            // Tambah constraint baru ke tabel rnc_centers
            $table->foreign('cost_center_id')
                ->references('id')
                ->on('rnc_centers')
                ->nullOnDelete(); // kalau nullable, pakai nullOnDelete()
        });
    }

    public function down(): void
    {
        Schema::table('ap_non_grn_details', function (Blueprint $table) {
            $table->dropForeign(['cost_center_id']);

            $table->foreign('cost_center_id')
                ->references('id')
                ->on('chart_of_account')
                ->cascadeOnDelete();
        });
    }
};
