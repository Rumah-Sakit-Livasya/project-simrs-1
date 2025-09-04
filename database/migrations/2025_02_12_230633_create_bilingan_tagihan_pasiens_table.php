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
        Schema::create('bilingan_tagihan_pasien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bilingan_id')->constrained('bilingan')->onDelete('cascade');
            $table->foreignId('tagihan_pasien_id')->constrained('tagihan_pasien')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilingan_tagihan_pasien');
    }
};
