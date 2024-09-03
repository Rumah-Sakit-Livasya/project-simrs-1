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
        Schema::create('tindakan_operasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_operasi_id')->references('id')->on('jenis_operasi')->cascadeOnDelete();
            $table->foreignId('kategori_operasi_id')->references('id')->on('kategori_operasi')->cascadeOnDelete();
            $table->string('kode_operasi', 50);
            $table->string('nama_operasi', 100);
            $table->string('nama_billing', 100);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_operasi');
    }
};
