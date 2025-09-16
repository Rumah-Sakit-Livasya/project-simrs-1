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
        Schema::table('parameter_radiologi', function (Blueprint $table) {
            $table->dropUnique('parameter_radiologi_kode_unique');
            $table->string('kode')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_radiologi', function (Blueprint $table) {
            $table->integer('kode')->unique()->change();
        });
    }
};
