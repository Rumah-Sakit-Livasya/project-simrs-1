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
        Schema::table('cppt', function (Blueprint $table) {
            $table->longText('diagnosa_gizi')->nullable()->after('implementasi');
            $table->longText('intervensi_gizi')->nullable()->after('diagnosa_gizi');
            $table->longText('monitoring')->nullable()->after('intervensi_gizi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cppt', function (Blueprint $table) {
            $table->dropColumn(['diagnosa_gizi', 'intervensi_gizi', 'monitoring']);
        });
    }
};
