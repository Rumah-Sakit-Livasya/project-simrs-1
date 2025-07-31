<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rnc_centers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rnc')->unique(); // Kode RNC Center, harus unik
            $table->string('nama_rnc');          // Nama RNC Center
            $table->boolean('is_active')->default(true); // Status: true = aktif, false = tidak aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rnc_centers');
    }
};
