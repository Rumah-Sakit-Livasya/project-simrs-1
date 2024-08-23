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
        Schema::create('tarif_parameter_radiologi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_radiologi_id')->references('id')->on('parameter_radiologi')->cascadeOnDelete();
            $table->foreignId('group_penjamin_id')->references('id')->on('group_penjamin')->cascadeOnDelete();
            $table->foreignId('kelas_rawat_id')->references('id')->on('kelas_rawat')->cascadeOnDelete();
            $table->bigInteger('share_dr')->default('0');
            $table->bigInteger('share_rs')->default('0');
            $table->bigInteger('total')->default('0');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_parameter_radiologi');
    }
};
