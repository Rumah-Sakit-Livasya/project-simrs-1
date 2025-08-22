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
        Schema::create('doctor_initial_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            $table->dateTime('waktu_masuk')->nullable();
            $table->dateTime('waktu_dilayani')->nullable();
            $table->json('tanda_vital')->nullable();
            $table->json('anamnesis')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            $table->text('diagnosa_kerja')->nullable();
            $table->text('diagnosa_banding')->nullable();
            $table->text('terapi_tindakan')->nullable();
            $table->json('gambar_anatomi')->nullable(); // Untuk menyimpan base64 image dari Painterro
            $table->json('edukasi')->nullable();
            $table->json('evaluasi_penyakit')->nullable();
            $table->json('rencana_tindak_lanjut_pasien')->nullable(); // penamaan dibedakan dari field lain
            $table->string('status', 20)->default('draft'); // Untuk 'draft' atau 'final'

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_initial_assessments');
    }
};
