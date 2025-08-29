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
        Schema::create('neonatus_initial_assessment_doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->json('data'); // Kolom utama untuk menyimpan semua data form
            $table->timestamps();
            $table->softDeletes(); // Sesuai permintaan, tambahkan soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neonatus_initial_assessment_doctors');
    }
};
