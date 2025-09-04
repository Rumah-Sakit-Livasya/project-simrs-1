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
        Schema::create('echocardiographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');

            // Kelompokkan data ke dalam JSON
            $table->json('aorta')->nullable();
            $table->json('left_atrium')->nullable();
            $table->json('right_ventricle')->nullable();
            $table->json('left_ventricle')->nullable();
            $table->json('mitral_valve')->nullable();
            $table->json('other_valves')->nullable(); // Untuk Tricuspid & Pulmonary
            $table->json('pericardial_effusion')->nullable();
            $table->json('comments')->nullable();

            $table->text('conclussion')->nullable();
            $table->text('advice')->nullable();
            $table->string('status', 20)->default('draft'); // draft, final

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('echocardiographies');
    }
};
