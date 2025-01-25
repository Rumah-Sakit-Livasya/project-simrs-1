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
        Schema::create('tindakan_medis_rajal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('tindakan_medis_id')->constrained('tindakan_medis')->onDelete('cascade');
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat')->onDelete('cascade');
            $table->integer('qty');
            $table->bigInteger('total_harga')->nullable();
            $table->string('user_entry');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_medis_rajal');
    }
};
