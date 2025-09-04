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
        Schema::create('inpatient_initial_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            // Tanda Vital
            $table->string('vital_sign_pr')->nullable(); // Nadi
            $table->string('vital_sign_rr')->nullable(); // Respirasi
            $table->string('vital_sign_bp')->nullable(); // Tensi
            $table->string('vital_sign_temperature')->nullable(); // Suhu

            // Antropometri
            $table->decimal('anthropometry_height', 5, 2)->nullable(); // Tinggi Badan (cm)
            $table->decimal('anthropometry_weight', 5, 2)->nullable(); // Berat Badan (kg)
            $table->decimal('anthropometry_bmi', 4, 2)->nullable(); // BMI
            $table->string('anthropometry_bmi_category')->nullable(); // Kategori BMI
            $table->string('anthropometry_chest_circumference')->nullable(); // Lingkar Dada
            $table->string('anthropometry_abdominal_circumference')->nullable(); // Lingkar Perut

            // Alergi (disimpan sebagai JSON)
            $table->json('allergy_medicine')->nullable(); // Alergi Obat
            $table->json('allergy_food')->nullable(); // Alergi Makanan

            // Lain-lain
            $table->text('diagnosis')->nullable();
            $table->text('registration_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inpatient_initial_examinations');
    }
};
