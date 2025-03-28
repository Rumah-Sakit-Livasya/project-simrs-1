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
        Schema::create('order_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_order');
            $table->unsignedBigInteger('registration_id');
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->boolean('is_billed');
            $table->boolean('is_cito');
            $table->string('nama_pasien', 100);
            $table->text('diagnosa_klinis')->nullable();
            $table->enum('tipe_pasien', ['rawat-jalan', 'rawat-inap', 'oct']); // 1. rajal 2. ranap 3 otc
            $table->string('entry_by', 70);
            $table->string('modify_by', 70)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_laboratorium');
    }
};
