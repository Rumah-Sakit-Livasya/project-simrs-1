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
        Schema::create('aspek_penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_penilaian_id');
            $table->string('nama');
            $table->string('bobot', 10);
            $table->timestamps();

            $table->foreign('group_penilaian_id')->references('id')->on('group_penilaians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspek_penilaians');
    }
};
