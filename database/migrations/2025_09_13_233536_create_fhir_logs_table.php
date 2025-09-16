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
        Schema::create('fhir_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->nullable();
            $table->string('resource_type'); // e.g., 'Encounter', 'Condition'
            $table->uuid('resource_id');
            $table->boolean('is_success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fhir_logs');
    }
};
