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
        Schema::create('patient_monitoring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('departement_id')->nullable();

            // Monitoring stages and status
            $table->json('monitoring_stages')->nullable(); // Array of stage names
            $table->json('stage_status')->nullable(); // Array of stage statuses (pending, completed, etc.)
            $table->json('stage_timestamps')->nullable(); // Array of completion timestamps
            $table->json('stage_completed_by')->nullable(); // Array of user IDs who completed stages
            $table->json('stage_notes')->nullable(); // Array of notes for each stage

            // Overall monitoring status
            $table->enum('overall_status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->decimal('completion_percentage', 5, 2)->default(0);

            // Metadata
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->foreign('departement_id')->references('id')->on('departements')->onDelete('set null');

            // Indexes for performance
            $table->index(['registration_id', 'overall_status']);
            $table->index(['patient_id', 'created_at']);
            $table->index(['overall_status', 'completion_percentage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_monitoring');
    }
};
