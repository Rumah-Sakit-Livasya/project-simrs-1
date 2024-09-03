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
        Schema::create('tipe_operasi', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 50);
            $table->integer('operator');
            $table->integer('anestesi');
            $table->integer('resusitator');
            $table->integer('dokter_tambahan');
            $table->integer('alat');
            $table->integer('ruangan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_operasi');
    }
};
