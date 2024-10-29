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
        Schema::create('maintenance_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id');
            $table->foreignId('user_id');
            $table->string('kondisi');
            $table->string('hasil');
            $table->string('tanggal');
            $table->string('estimasi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('rtl');
            $table->string('foto');
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_barang');
    }
};
