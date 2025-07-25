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
        Schema::table('transfer_pasien_antar_ruangan', function (Blueprint $table) {
            $table->string('kondisi_pasien')->nullable()->after('tindakan');
            $table->renameColumn('pasien_kelmbali', 'pasien_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_pasien_antar_ruangan', function (Blueprint $table) {
            $table->dropColumn('kondisi_pasien');
            // $table->renameColumn('pasien_kembali', 'pasien_kelmbali');
        });
    }
};
