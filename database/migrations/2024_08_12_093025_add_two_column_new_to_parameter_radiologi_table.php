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
            $table->dropColumn('status');
            $table->integer('kode')->unique()->after('kategori_radiologi_id');
            $table->boolean('is_reverse')->after('parameter')->nullable();
            $table->boolean('is_kontras')->after('is_reverse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_radiologi', function (Blueprint $table) {
            $table->dropColumn('is_reverse');
            $table->dropColumn('kode');
            $table->dropColumn('is_kontras');
            $table->string('status', 50);
        });
    }
};
