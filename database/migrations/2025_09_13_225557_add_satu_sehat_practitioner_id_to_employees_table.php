<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Kolom untuk menyimpan ID Practitioner (Nakes) dari Satu Sehat.
            // Menggunakan UUID adalah standar FHIR.
            $table->uuid('satu_sehat_practitioner_id')->nullable()->after('ttd');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('satu_sehat_practitioner_id');
        });
    }
};
