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
        Schema::create('pencairans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pencairan')->unique();

            // Relasi ke pengajuan yang dicairkan
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('restrict');

            $table->date('tanggal_pencairan');
            $table->decimal('nominal_pencairan', 15, 2);

            // === PERUBAHAN DI SINI ===
            // Relasi ke sumber dana (tabel banks yang sudah ada)
            $table->foreignId('bank_id')->constrained('banks')->onDelete('restrict');

            $table->text('keterangan')->nullable();

            // Relasi ke user yang memproses pencairan
            $table->foreignId('user_entry_id')->constrained('users')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencairans');
    }
};
