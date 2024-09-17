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
        Schema::create('harga_tarif_registrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarif_registrasi_id')->references('id')->on('tarif_registrasi')->cascadeOnDelete();
            $table->foreignId('group_penjamin_id')->references('id')->on('group_penjamin')->cascadeOnDelete();
            $table->bigInteger('harga');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_tarif_registrasi');
    }
};
