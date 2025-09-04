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
        Schema::create('tarif_operasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tindakan_operasi_id')->constrained('tindakan_operasi')->onDelete('cascade');
            $table->foreignId('group_penjamin_id')->constrained('group_penjamin')->onDelete('cascade');
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat')->onDelete('cascade');
            // $table->string('tipe_dokter')->nullable(); // Standar, dll.

            // === Operator Utama ===
            $table->decimal('operator_dokter', 15, 2)->default(0);
            $table->decimal('operator_rs', 15, 2)->default(0);
            $table->decimal('operator_anastesi_dokter', 15, 2)->default(0);
            $table->decimal('operator_anastesi_rs', 15, 2)->default(0);
            $table->decimal('operator_resusitator_dokter', 15, 2)->default(0);
            $table->decimal('operator_resusitator_rs', 15, 2)->default(0);

            // === Asisten Operator 1~3 ===
            for ($i = 1; $i <= 3; $i++) {
                $table->decimal("asisten_operator_{$i}_dokter", 15, 2)->default(0);
                $table->decimal("asisten_operator_{$i}_rs", 15, 2)->default(0);
            }

            // === Asisten Anastesi 1~2 ===
            for ($i = 1; $i <= 2; $i++) {
                $table->decimal("asisten_anastesi_{$i}_dokter", 15, 2)->default(0);
                $table->decimal("asisten_anastesi_{$i}_rs", 15, 2)->default(0);
            }

            // === Dokter Tambahan 1~5 ===
            for ($i = 1; $i <= 5; $i++) {
                $table->decimal("dokter_tambahan_{$i}_dokter", 15, 2)->default(0);
                $table->decimal("dokter_tambahan_{$i}_rs", 15, 2)->default(0);
            }

            // === Ruang Operasi & Alat ===
            $table->decimal('ruang_operasi', 15, 2)->default(0);
            $table->decimal('bmhp', 15, 2)->default(0);
            $table->decimal('alat_dokter', 15, 2)->default(0);
            $table->decimal('alat_rs', 15, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_operasi');
    }
};
