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
        Schema::create('tarif_persalinan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat')->onDelete('cascade');
            $table->foreignId('group_penjamin_id')->constrained('group_penjamin')->onDelete('cascade');
            $table->foreignId('persalinan_id')->constrained('daftar_persalinan')->onDelete('cascade');
            // $table->foreignId('persalinan_id')->constrained('persalinan')->onDelete('cascade');

            // Operator
            $table->decimal('operator_dokter', 15, 2)->default(0);
            $table->decimal('operator_rs', 15, 2)->default(0);
            $table->decimal('operator_prasarana', 15, 2)->default(0);

            // Asisten Operator
            $table->decimal('ass_operator_dokter', 15, 2)->default(0);
            $table->decimal('ass_operator_rs', 15, 2)->default(0);

            // Anastesi
            $table->decimal('anastesi_dokter', 15, 2)->default(0);
            $table->decimal('anastesi_rs', 15, 2)->default(0);

            // Asisten Anastesi
            $table->decimal('ass_anastesi_dokter', 15, 2)->default(0);
            $table->decimal('ass_anastesi_rs', 15, 2)->default(0);

            // Resusitator
            $table->decimal('resusitator_dokter', 15, 2)->default(0);
            $table->decimal('resusitator_rs', 15, 2)->default(0);

            // Umum
            $table->decimal('umum_dokter', 15, 2)->default(0);
            $table->decimal('umum_rs', 15, 2)->default(0);

            // Ruang
            $table->decimal('ruang', 15, 2)->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_persalinan');
    }
};
