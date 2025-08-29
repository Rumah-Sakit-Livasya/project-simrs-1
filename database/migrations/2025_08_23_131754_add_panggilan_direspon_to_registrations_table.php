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
        Schema::table('registrations', function (Blueprint $table) {
            // Flag untuk menandai apakah panggilan sudah direspon oleh plasma
            // Diletakkan setelah kolom 'waktu_panggil'
            $table->boolean('panggilan_direspon')->default(false)->after('waktu_panggil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('panggilan_direspon');
        });
    }
};
