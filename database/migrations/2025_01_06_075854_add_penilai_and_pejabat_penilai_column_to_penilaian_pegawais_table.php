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
        Schema::table('penilaian_pegawais', function (Blueprint $table) {
            $table->unsignedBigInteger('penilai')->after('group_penilaian_id');
            $table->unsignedBigInteger('pejabat_penilai')->after('group_penilaian_id');
            $table->dropColumn('periode');
            
            $table->foreign('penilai')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('pejabat_penilai')->references('id')->on('employees')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_pegawais', function (Blueprint $table) {
            $table->dropForeign(['penilai']); // Drop foreign key 'penilai'
            $table->dropForeign(['pejabat_penilai']); // Drop foreign key 'pejabat_penilai'
            $table->dropColumn(['penilai', 'pejabat_penilai']);
            $table->string('periode')->after('indikator_penilaian_id');
        });
    }
};
