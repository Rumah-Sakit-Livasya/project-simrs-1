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
        Schema::create('group_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_group', 100);
            $table->unsignedBigInteger('penilai');
            $table->unsignedBigInteger('pejabat_penilai');
            $table->string('rumus_penilaian')->default('rata-rata');
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('penilai')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('pejabat_penilai')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_penilaians');
    }
};
