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
            $table->text('lantai_kamar');
            $table->text('sudut_kamar');
            $table->text('plafon_kamar');
            $table->text('dinding_kamar');
            $table->text('bed_head');
            $table->text('lantai_toilet');
            $table->text('wastafel_toilet');
            $table->text('closet_toilet');
            $table->text('kaca_toilet');
            $table->text('dinding_toilet');
            $table->text('shower_toilet');
            $table->string('dokumentasi');
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
