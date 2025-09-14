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
        Schema::table('kelas_rawat', function (Blueprint $table) {
            $table->string('kode_bpjs')->nullable()->after('kelas');
            $table->string('nama_bpjs')->nullable()->after('kode_bpjs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_rawat', function (Blueprint $table) {
            $table->dropColumn('kode_bpjs');
            $table->dropColumn('nama_bpjs');
        });
    }
};
