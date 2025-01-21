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
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->integer('status_penilaian')->after('komentar_pejabat_penilai')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->dropColumn('status_penilaian');
        });
    }
};
