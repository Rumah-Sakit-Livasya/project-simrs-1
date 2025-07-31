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
        Schema::create('pembayaran_asuransi_detail', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pembayaran_asuransi_id');
            $table->unsignedBigInteger('konfirmasi_asuransi_id');

            $table->decimal('dibayar', 15, 2)->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('pembayaran_asuransi_id')
                ->references('id')
                ->on('pembayaran_asuransi')
                ->onDelete('cascade');

            $table->foreign('konfirmasi_asuransi_id')
                ->references('id')
                ->on('konfirmasi_asuransi')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_asuransi_detail');
    }
};
