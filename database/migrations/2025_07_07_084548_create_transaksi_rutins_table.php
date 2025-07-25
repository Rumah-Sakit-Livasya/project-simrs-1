<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_rutins', function (Blueprint $table) {
            $table->id();
            $table->string('nama_transaksi');
            // Relasi ke tabel Chart of Account
            $table->foreignId('chart_of_account_id')->constrained('chart_of_account')->onDelete('restrict');
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_rutins');
    }
};
