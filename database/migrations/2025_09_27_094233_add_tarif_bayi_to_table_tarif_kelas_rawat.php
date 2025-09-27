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
        Schema::table('tarif_kelas_rawat', function (Blueprint $table) {
            $table->decimal('tarif', 15, 2)->change();
            $table->decimal('tarif_bayi', 15, 2)->nullable()->after('tarif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarif_kelas_rawat', function (Blueprint $table) {
            $table->string('tarif')->change();
            $table->dropColumn('tarif_bayi');
        });
    }
};
