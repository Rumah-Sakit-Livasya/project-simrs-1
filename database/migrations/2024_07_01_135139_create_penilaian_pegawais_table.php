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
        Schema::create('penilaian_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('group_penilaian_id');
            $table->unsignedBigInteger('indikator_penilaian_id');
            $table->string('periode');
            $table->string('tahun');
            $table->string('nilai', 10)->nullable();
            $table->string('file')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('group_penilaian_id')->references('id')->on('group_penilaians')->onDelete('cascade');
            $table->foreign('indikator_penilaian_id')->references('id')->on('indikator_penilaians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_pegawais');
    }
};
