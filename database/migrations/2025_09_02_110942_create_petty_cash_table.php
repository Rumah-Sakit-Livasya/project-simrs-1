<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // periode awal
            $table->unsignedBigInteger('kas_id'); // relasi ke akun_kas_bank
            $table->text('keterangan'); // keterangan umum
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->timestamps();

            // Foreign Key ke akun kas/bank
            $table->foreign('kas_id')->references('id')->on('bank_perusahaan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash');
    }
};
