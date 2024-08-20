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
        Schema::create('grup_parameter_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->string('nama_grup', 100);
            $table->string('kode_order', 50);
            $table->string('kode_mapping', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_parameter_laboratorium');
    }
};
