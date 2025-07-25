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
        Schema::create('pembayaran_transfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_tagihan_id')->constrained('pembayaran_tagihan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bank_perusahaan_id')->constrained('bank_perusahaan')->onUpdate('cascade')->onDelete('cascade');
            $table->string('bank_pengirim');
            $table->string('norek_pengirim');
            $table->string('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_transfer');
    }
};
