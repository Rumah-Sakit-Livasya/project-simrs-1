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
        Schema::create('pembayaran_jasa_dokter', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('dokter_id');
            $table->unsignedBigInteger('kas_bank_id')->nullable(); // opsional
            $table->string('metode_pembayaran')->default('Transfer'); // Transfer / Tunai / Giro dll

            // Pajak & nominal
            $table->decimal('pajak_persen', 5, 2)->default(11.00); // default 11%
            $table->decimal('nominal', 15, 2)->default(0);

            // Informasi pendukung
            $table->string('npwp')->nullable();
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->year('tahun_pajak')->nullable();
            $table->string('guarantee_fee')->nullable();

            // Tanggal
            $table->date('tanggal_pembayaran')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');

            $table->timestamps();

            // Foreign keys
            $table->foreign('dokter_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('kas_bank_id')->references('id')->on('banks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_jasa_dokter');
    }
};
