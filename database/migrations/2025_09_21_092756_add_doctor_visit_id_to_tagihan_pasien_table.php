<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->foreignId('doctor_visit_id')->nullable()->after('tindakan_medis_id')->constrained('doctor_visits')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->dropForeign(['doctor_visit_id']);
            $table->dropColumn('doctor_visit_id');
        });
    }
};
