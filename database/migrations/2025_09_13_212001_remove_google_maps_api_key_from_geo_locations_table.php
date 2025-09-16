<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('geo_locations', function (Blueprint $table) {
            // Hapus kolomnya
            $table->dropColumn('google_maps_api_key');
        });
    }

    public function down(): void
    {
        Schema::table('geo_locations', function (Blueprint $table) {
            // Jika perlu rollback, buat kembali kolomnya
            $table->string('google_maps_api_key')->nullable()->after('latitude');
        });
    }
};
