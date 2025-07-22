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
        Schema::create('tindakan_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_tindakan_medis_id')->references('id')->on('grup_tindakan_medis')->cascadeOnDelete();
            $table->string('kode', 30);
            $table->string('nama_tindakan', 100);
            $table->string('nama_billing', 100);
            $table->boolean('is_konsul')->default(0);
            $table->boolean('auto_charge')->default(0);
            $table->boolean('is_vaksin')->default(0);
            $table->string('mapping_rl_13')->nullable();
            $table->string('mapping_rl_34')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_medis');
    }
};
