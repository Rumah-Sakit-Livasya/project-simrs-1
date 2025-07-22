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
        Schema::create('survei_kebersihan_kamar', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_maintenance_id')->constrained('room_maintenance')->cascadeOnDelete();
            $table->text('lantai_kamar')->nullable();
            $table->text('sudut_kamar')->nullable();
            $table->text('plafon_kamar')->nullable();
            $table->text('dinding_kamar')->nullable();
            $table->text('bed_head')->nullable();
            $table->text('lantai_toilet')->nullable();
            $table->text('wastafel_toilet')->nullable();
            $table->text('closet_toilet')->nullable();
            $table->text('kaca_toilet')->nullable();
            $table->text('dinding_toilet')->nullable();
            $table->text('shower_toilet')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_kebersihan_kamar');
    }
};
