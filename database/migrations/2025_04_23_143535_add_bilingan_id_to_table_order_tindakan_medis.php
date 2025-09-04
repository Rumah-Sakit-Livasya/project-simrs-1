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
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->string('type')->nullable()->after('date');
            $table->foreignId('tindakan_medis_id')->nullable()->after('type')->constrained('tindakan_medis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihan_pasien', function (Blueprint $table) {
            $table->dropForeign(['tindakan_medis_id']);
            $table->dropColumn('tindakan_medis_id');
            $table->dropColumn('type');
        });
    }
};
