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
        Schema::create('skrining_gizi_dewasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id'); // pregid dari form
            $table->string('diagnosa_medis')->nullable();
            $table->decimal('bb', 5, 2)->nullable();
            $table->decimal('tb', 5, 2)->nullable();
            $table->decimal('imt', 5, 2)->nullable();
            $table->decimal('tinggi_lutut', 5, 2)->nullable();
            $table->decimal('lla', 5, 2)->nullable();
            $table->tinyInteger('skor1')->nullable()->comment('Skor IMT');
            $table->tinyInteger('skor2')->nullable()->comment('Skor Kehilangan BB');
            $table->tinyInteger('skor3')->nullable()->comment('Skor Efek Penyakit Akut');
            $table->tinyInteger('hasil_skor')->nullable();
            $table->text('analisis_skor')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skrining_gizi_dewasa');
    }
};
