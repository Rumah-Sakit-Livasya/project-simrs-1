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
        Schema::create('bed_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bed_id')->constrained('beds')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['terisi', 'kosong'])->default('kosong');
            $table->datetime('tanggal_masuk');
            $table->datetime('tanggal_keluar')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_patient');
    }
};
