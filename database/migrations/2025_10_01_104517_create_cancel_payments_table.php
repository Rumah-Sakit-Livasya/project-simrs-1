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
        Schema::create('cancel_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bilingan_id')->constrained('bilingan')->onDelete('cascade');
            $table->foreignId('user_id')->comment('User yang melakukan pembatalan (kasir)')->constrained('users');
            $table->foreignId('otorisasi_id')->comment('User yang memberikan otorisasi')->constrained('users');
            $table->dateTime('tgl_batal');
            $table->text('catatan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancel_payments');
    }
};
