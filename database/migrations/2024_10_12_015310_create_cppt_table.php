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
        Schema::create('cppt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('tipe_cppt');
            $table->string('tipe_rawat');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('konsulkan_ke')->nullable();
            $table->longText('subjective');
            $table->longText('objective');
            $table->longText('assesment');
            $table->longText('planning');
            $table->longText('instruksi')->nullable();
            $table->longText('evaluasi')->nullable();
            $table->longText('implementasi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cppt');
    }
};
