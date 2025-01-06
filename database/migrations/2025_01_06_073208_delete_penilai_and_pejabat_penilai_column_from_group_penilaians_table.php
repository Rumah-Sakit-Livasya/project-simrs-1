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
        Schema::table('group_penilaians', function (Blueprint $table) {
            $table->dropForeign(['penilai']); // Drop foreign key 'penilai'
            $table->dropForeign(['pejabat_penilai']); // Drop foreign key 'pejabat_penilai'
            $table->dropColumn(['penilai', 'pejabat_penilai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_penilaians', function (Blueprint $table) {
            $table->unsignedBigInteger('penilai');
            $table->unsignedBigInteger('pejabat_penilai');

            $table->foreign('penilai')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('pejabat_penilai')->references('id')->on('employees')->onDelete('cascade');
        });
    }
};
