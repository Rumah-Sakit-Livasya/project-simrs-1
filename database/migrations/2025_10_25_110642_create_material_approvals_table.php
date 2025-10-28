<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('material_name');
            $table->string('brand')->nullable();
            $table->string('type_or_model')->nullable();
            $table->text('technical_specifications');
            $table->string('image_path')->nullable(); // Path to the material photo
            $table->enum('status', ['Submitted', 'Approved', 'Rejected', 'Revision Required']);

            // To link who submitted/recorded this approval
            $table->foreignId('submitted_by')->constrained('users');

            // To link who reviewed/approved this
            $table->foreignId('reviewed_by')->nullable()->constrained('users');

            $table->text('remarks')->nullable(); // Notes or reasons for rejection/revision
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_approvals');
    }
};
