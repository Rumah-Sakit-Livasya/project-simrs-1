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
        Schema::create('pembayaran_credit_card', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_tagihan_id')->constrained('pembayaran_tagihan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bank_perusahaan_id')->constrained('bank_perusahaan')->onUpdate('cascade')->onDelete('cascade');
            $table->string('tipe');
            $table->string('cc_number');
            $table->string('auth_number')->nullable();
            $table->string('batch')->nullable();
            $table->string('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_credit_card');
    }
};
