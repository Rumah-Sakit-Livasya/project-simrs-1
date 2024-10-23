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
        Schema::create('resume_medis_rajal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien', 100);
            $table->string('medical_record_number');
            $table->foreign('medical_record_number')
                ->references('medical_record_number')->on('patients')
                ->onDelete('cascade');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin', 100);
            $table->date('tgl_masuk');
            $table->string('cara_keluar');
            $table->string('berat_lahir', 30)->nullable();
            $table->text('anamnesa');
            $table->text('diagnosa_utama');
            $table->text('diagnosa_tambahan')->nullable();
            $table->text('tindakan_utama')->nullable();
            $table->text('tindakan_tambahan')->nullable();
            $table->unsignedBigInteger('pic_dokter')->default(0);
            $table->foreign('pic_dokter')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_ttd')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_medis_rajal');
    }
};
