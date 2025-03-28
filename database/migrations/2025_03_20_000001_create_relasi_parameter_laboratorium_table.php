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
        Schema::create('relasi_parameter_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_parameter_id')->references('id')->on('parameter_laboratorium')->onDelete('cascade');
            $table->foreignId('sub_parameter_id')->references('id')->on('parameter_laboratorium')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relasi_parameter_laboratorium');
    }
};
