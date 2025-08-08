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
        Schema::create('order_operasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')->constrained('registrations');

            // Tambahkan ruangan_id sebagai nullable tanpa foreign key constraint
            $table->unsignedBigInteger('ruangan_id')->nullable();

            $table->dateTime('tgl_operasi');
            $table->foreignId('tipe_operasi_id')->constrained('tipe_operasi');
            $table->foreignId('kategori_operasi_id')->constrained('kategori_operasi');
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat');

            $table->text('diagnosa_awal');
            $table->enum('status', ['draft', 'final', 'batal'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_operasi');
    }
};
