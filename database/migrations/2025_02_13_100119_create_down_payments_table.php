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
        Schema::create('down_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bilingan_id')->constrained('bilingan')->onUpdate('cascade')->onDelete('cascade'); // Kolom tagihan_pasien_id
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade'); // Kolom tagihan_pasien_id
            $table->string('metode_pembayaran');
            $table->string('nominal');
            $table->string('tipe');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('down_payment');
    }
};
