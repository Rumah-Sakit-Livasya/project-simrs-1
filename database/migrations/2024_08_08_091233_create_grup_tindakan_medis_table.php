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
        Schema::create('grup_tindakan_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deparmenet_id')->references('id')->on('departements')->cascadeOnDelete();
            $table->string('nama_grup', 100);
            $table->boolean('status');
            $table->unsignedBigInteger('coa_pendapatan')->nullable();
            $table->unsignedBigInteger('coa_prasarana')->nullable();
            $table->unsignedBigInteger('coa_bhp')->nullable();
            $table->unsignedBigInteger('coa_biaya')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_tindakan');
    }
};
