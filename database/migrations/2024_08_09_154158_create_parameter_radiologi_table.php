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
        Schema::create('parameter_radiologi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_parameter_radiologi_id')->references('id')->on('grup_parameter_radiologi')->cascadeOnDelete();
            $table->foreignId('kategori_radiologi_id')->references('id')->on('kategori_radiologi')->cascadeOnDelete();
            $table->string('parameter');
            $table->integer('kode')->unique()->after('kategori_radiologi_id');
            $table->boolean('is_reverse')->after('parameter')->nullable();
            $table->boolean('is_kontras')->after('is_reverse')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_radiologi');
    }
};
