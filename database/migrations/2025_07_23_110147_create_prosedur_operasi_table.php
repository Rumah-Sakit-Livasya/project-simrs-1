<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prosedur_operasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_operasi_id')
                ->constrained('order_operasi')
                ->onDelete('cascade');

            $table->foreignId('tindakan_id')
                ->constrained('tindakan_operasi');

            // Tim Operasi
            $table->foreignId('dokter_operator_id')
                ->constrained('doctors');

            $table->foreignId('ass_dokter_operator_id')
                ->nullable()
                ->constrained('doctors');

            $table->foreignId('dokter_anastesi_id')
                ->nullable()
                ->constrained('doctors');

            $table->foreignId('ass_dokter_anastesi_id')
                ->constrained('doctors');

            // Field tambahan untuk tim operasi sesuai gambar
            $table->foreignId('dokter_resusitator_id')
                ->nullable()
                ->constrained('doctors');

            $table->foreignId('dokter_tambahan_id')
                ->nullable()
                ->constrained('doctors');

            // Data operasi
            $table->text('laporan_operasi');
            $table->text('komplikasi')->nullable();
            $table->enum('status', ['rencana', 'berlangsung', 'selesai', 'batal'])->default('rencana');

            // Waktu operasi
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();

            // Audit trail
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->softDeletes();
            $table->timestamps();

            // Indexes untuk performance
            $table->index(['order_operasi_id', 'status']);
            $table->index(['dokter_operator_id', 'waktu_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prosedur_operasi');
    }
};
