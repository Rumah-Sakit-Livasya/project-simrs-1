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
        Schema::create('tagihan_pasien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bilingan_id')->constrained('bilingan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('date');
            $table->string('tagihan');
            $table->string('quantity');
            $table->string('nominal');
            $table->string('tipe_diskon')->nullable();
            $table->string('disc')->nullable();
            $table->string('diskon')->nullable();
            $table->string('jamin')->nullable();
            $table->string('jaminan')->nullable();
            $table->string('wajib_bayar')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_pasien');
    }
};
