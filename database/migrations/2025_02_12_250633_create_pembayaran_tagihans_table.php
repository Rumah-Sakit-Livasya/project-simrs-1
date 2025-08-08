<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pembayaran_tagihan', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade'); // Kolom user_id
            $table->foreignId('bilingan_id')->constrained('bilingan')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('total_tagihan'); // Kolom total_tagihan
            $table->integer('jaminan'); // Kolom jaminan
            $table->integer('tagihan_pasien'); // Kolom tagihan_pasien
            $table->integer('jumlah_terbayar'); // Kolom jumlah_terbayar
            $table->integer('sisa_tagihan'); // Kolom sisa_tagihan
            $table->integer('kembalian'); // Kolom kembalian
            $table->text('bill_notes')->nullable(); // Kolom bill_notes, tipe text, bisa null
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft delete
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_tagihan');
    }
};
