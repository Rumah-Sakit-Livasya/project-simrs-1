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
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->decimal('sisa_tagihan', 15, 2)->default(0)->after('jumlah');
            $table->decimal('total_dibayar', 15, 2)->default(0)->after('sisa_tagihan');
            $table->boolean('is_lunas')->default(false)->after('total_dibayar');
            $table->unsignedBigInteger('last_pembayaran_id')->nullable();

            $table->foreign('last_pembayaran_id')->references('id')->on('pembayaran_asuransi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('konfirmasi_asuransi', function (Blueprint $table) {
            $table->dropColumn(['sisa_tagihan', 'total_dibayar', 'is_lunas', 'last_pembayaran_id']);
        });
    }
};
