<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            // Menambahkan foreign key ke order_alat_medis
            $table->foreignId('order_alat_medis_id')->nullable()->after('doctor_visit_id')->constrained('order_alat_medis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->dropForeign(['order_alat_medis_id']);
            $table->dropColumn('order_alat_medis_id');
        });
    }
};
