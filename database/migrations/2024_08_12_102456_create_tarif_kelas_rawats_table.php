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
        Schema::create('tarif_kelas_rawat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_penjamin_id')->constrained('group_penjamin');
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat');
            $table->string('tarif');
            $table->softDeletes();
            $table->timestamps();

            // $table->primary(['kelas_rawat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_kelas_rawats');
    }
};
