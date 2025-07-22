<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('triage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->date('tgl_masuk')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_dilayani')->nullable();
            $table->integer('pr')->nullable();
            $table->string('bp')->nullable();
            $table->float('body_height')->nullable();
            $table->float('bmi')->nullable();
            $table->float('lingkar_dada')->nullable();
            $table->float('sp02')->nullable();
            $table->integer('rr')->nullable();
            $table->float('temperatur')->nullable();
            $table->float('body_weight')->nullable();
            $table->string('kat_bmi')->nullable();
            $table->float('lingkar_perut')->nullable();
            $table->boolean('auto_anamnesa')->default(0);
            $table->boolean('allo_anamnesa')->default(0);

            // JSON fields for triage categories
            $table->json('airway_merah')->nullable();
            $table->json('airway_kuning')->nullable();
            $table->json('airway_hijau')->nullable();
            $table->json('breathing_merah')->nullable();
            $table->json('breathing_kuning')->nullable();
            $table->json('breathing_hijau')->nullable();
            $table->json('circulation_merah')->nullable();
            $table->json('circulation_kuning')->nullable();
            $table->json('circulation_hijau')->nullable();
            $table->json('disability')->nullable();
            $table->json('kesimpulan')->nullable();
            $table->boolean('daa_hitam')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage');
    }
};
