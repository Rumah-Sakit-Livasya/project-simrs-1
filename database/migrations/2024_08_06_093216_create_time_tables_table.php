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
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departement_id')->constrained('departements')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->string('hari_praktek');
            $table->string('jam_praktek');
            $table->string('lama_periksa');
            $table->string('mulai_antrian_bpjs');
            $table->string('kuota_regis_online');
            $table->boolean('jadwal_unlimited')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};
