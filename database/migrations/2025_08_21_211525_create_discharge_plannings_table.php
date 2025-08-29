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
        Schema::create('discharge_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            // Menggunakan JSON untuk data terstruktur
            $table->json('skrining_faktor_resiko')->nullable();
            $table->json('rencana_perawatan_rumah')->nullable();
            $table->json('hal_diperhatikan')->nullable();

            // Kolom untuk data penjelasan
            $table->dateTime('waktu_penjelasan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_plannings');
    }
};
