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
            $table->integer('is_verified_penilai')->after('status_penilaian')->default(0);
            $table->integer('is_verified_pejabat_penilai')->after('status_penilaian')->default(0);
            $table->integer('is_verified_pegawai')->after('status_penilaian')->default(0);
            $table->integer('is_verified_hrd')->after('status_penilaian')->default(0);
            $table->integer('is_verified_direktur')->after('status_penilaian')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->dropColumn('is_verified_penilai');
            $table->dropColumn('is_verified_pejabat_penilai');
            $table->dropColumn('is_verified_pegawai');
            $table->dropColumn('is_verified_hrd');
            $table->dropColumn('is_verified_direktur');
        });
    }
};
