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
        Schema::create('pembayaran_asuransi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('penjamin_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->string('status')->default('completed');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('penjamin_id')->references('id')->on('penjamins');
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_asuransi');
    }
};
