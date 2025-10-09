<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Kolom untuk menandai status pasien: 'aktif', 'nonaktif', 'digabung', dll.
            // Default 'aktif' untuk semua data yang sudah ada.
            $table->string('status', 20)->default('aktif')->after('notes'); // Sesuaikan posisi 'after' jika perlu

            // Kolom untuk menyimpan nomor RM tujuan jika statusnya 'digabung'.
            $table->string('merged_to_rm')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['status', 'merged_to_rm']);
        });
    }
};
