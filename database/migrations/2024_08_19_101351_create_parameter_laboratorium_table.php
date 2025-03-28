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
        Schema::create('parameter_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_parameter_laboratorium_id')->references('id')->on('grup_parameter_laboratorium')->cascadeOnDelete();
            $table->foreignId('kategori_laboratorium_id')->references('id')->on('kategori_laboratorium')->cascadeOnDelete();
            $table->foreignId('tipe_laboratorium_id') ->references('id')->on('tipe_laboratorium')->cascadeOnDelete();
            $table->bigInteger('kode');
            $table->string('parameter', 100);
            $table->string('satuan', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->boolean('is_hasil')->nullable();
            $table->boolean('is_order')->nullable();
            $table->string('tipe_hasil', 50)->nullable();
            $table->string('metode')->nullable();
            $table->integer('no_urut')->nullable();
            $table->string('sub_parameter')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_laboratorium');
    }
};
