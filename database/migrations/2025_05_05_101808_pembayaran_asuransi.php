<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel utama pembayaran
        Schema::create('pembayaran_asuransi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('penjamin_id');
            $table->unsignedBigInteger('bank_id')->nullable(); // bisa null kalau tunai
            $table->decimal('jumlah', 15, 2);
            $table->string('status')->default('completed'); // optional: 'completed', 'draft', 'canceled'
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('penjamin_id')->references('id')->on('penjamin')->onDelete('restrict');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Tabel detail: untuk menghubungkan ke banyak invoice
        Schema::create('pembayaran_asuransi_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_asuransi_id');
            $table->unsignedBigInteger('konfirmasi_asuransi_id');
            $table->decimal('dibayar', 15, 2);
            $table->timestamps();

            $table->foreign('pembayaran_asuransi_id')->references('id')->on('pembayaran_asuransi')->onDelete('cascade');
            $table->foreign('konfirmasi_asuransi_id')->references('id')->on('konfirmasi_asuransi')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_asuransi_detail');
        Schema::dropIfExists('pembayaran_asuransi');
    }
};
