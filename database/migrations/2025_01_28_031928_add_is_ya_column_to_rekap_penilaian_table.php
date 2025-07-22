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
            $table->integer('is_ya')->after('is_verified_direktur')->default(0);
            $table->integer('is_tidak')->after('is_verified_direktur')->default(0);
            $table->string('keterangan_ya')->after('is_verified_direktur')->nullable();
            $table->string('keterangan_tidak')->after('is_verified_direktur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_penilaian', function (Blueprint $table) {
            $table->dropColumn('is_ya');
            $table->dropColumn('is_tidak');
            $table->dropColumn('keterangan_ya');
            $table->dropColumn('keterangan_tidak');
        });
    }
};
