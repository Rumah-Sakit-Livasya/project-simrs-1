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
            $table->string('periode')->after('tahun');
            $table->string('status_penilaian')->default('karyawan')->change();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->dropColumn('periode');
            $table->integer('status_penilaian')->default(0)->change();
        });
    }
};
