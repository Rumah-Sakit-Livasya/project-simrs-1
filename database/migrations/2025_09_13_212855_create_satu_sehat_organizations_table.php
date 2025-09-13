<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Kolom untuk menyimpan ID dari Satu Sehat. Menggunakan UUID adalah standar FHIR.
            $table->uuid('satu_sehat_organization_id')->nullable()->after('master_layanan_rl')->comment('ID Organisasi dari Satu Sehat FHIR');

            // Kolom untuk filtering tabulasi di halaman kita.
            $table->enum('category', ['poliklinik', 'penunjang_medis', 'lainnya'])->nullable()->after('satu_sehat_organization_id');
        });
    }

    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Jika migrasi di-rollback, hapus kolom-kolom ini.
            $table->dropColumn('satu_sehat_organization_id');
            $table->dropColumn('category');
        });
    }
};
