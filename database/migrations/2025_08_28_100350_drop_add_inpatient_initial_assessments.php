<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('inpatient_initial_assessments');

        Schema::create('inpatient_initial_assessments', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('registration_id')
                ->constrained('registrations')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // --- Vital Sign ---
            $table->unsignedSmallInteger('vital_sign_pr')->nullable(); // Pulse Rate
            $table->unsignedSmallInteger('vital_sign_rr')->nullable(); // Respiratory Rate
            $table->string('vital_sign_bp', 20)->nullable(); // Tekanan darah
            $table->decimal('vital_sign_temperature', 4, 1)->nullable(); // Suhu

            // --- Antropometri ---
            $table->decimal('anthropometry_height', 5, 2)->nullable(); // cm
            $table->decimal('anthropometry_weight', 5, 2)->nullable(); // kg
            $table->decimal('anthropometry_bmi', 5, 2)->nullable();
            $table->string('anthropometry_bmi_category', 100)->nullable();
            $table->decimal('anthropometry_chest_circumference', 5, 2)->nullable();
            $table->decimal('anthropometry_abdominal_circumference', 5, 2)->nullable();

            // --- Alergi & Catatan ---
            $table->json('allergy_medicine')->nullable(); // array/JSON
            $table->json('allergy_food')->nullable();    // array/JSON
            $table->text('diagnosis')->nullable();
            $table->text('registration_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inpatient_initial_assessments');

        // --- Bikin ulang versi lama sebelum diperbarui ---
        Schema::create('inpatient_initial_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained('registrations')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Versi lama pakai banyak JSON array untuk asesmen lengkap
            $table->dateTime('waktu_masuk_ruangan')->nullable();
            $table->json('info_masuk_ruangan')->nullable();
            $table->json('pemeriksaan_dibawa')->nullable();
            $table->json('obat_dibawa')->nullable();
            $table->json('riwayat_kesehatan')->nullable();
            $table->json('riwayat_kesehatan_lalu')->nullable();
            $table->json('riwayat_alergi')->nullable();
            $table->json('riwayat_kesehatan_keluarga')->nullable();
            $table->json('riwayat_psikososial')->nullable();
            $table->json('riwayat_komunikasi')->nullable();
            $table->json('riwayat_kebudayaan')->nullable();
            $table->json('respon_emosi_kognitif')->nullable();
            $table->json('informasi_diinginkan')->nullable();
            $table->json('nutrisi')->nullable();
            $table->json('eliminasi')->nullable();
            $table->json('personal_hygiene')->nullable();
            $table->json('istirahat_tidur')->nullable();
            $table->json('aktivitas_latihan')->nullable();
            $table->json('neuro_cerebral')->nullable();
            $table->json('tingkat_kesadaran')->nullable();
            $table->json('pemeriksaan_fisik')->nullable();
            $table->json('asesmen_nyeri')->nullable();
            $table->json('resiko_jatuh_dewasa')->nullable();
            $table->json('masalah_keperawatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
