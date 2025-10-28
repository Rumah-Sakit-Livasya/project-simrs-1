<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('inspection_type', ['Incoming Material', 'Work In Progress']);
            $table->date('inspection_date');

            // Link to the material being inspected (nullable if it's work inspection)
            $table->foreignId('material_approval_id')->nullable()->constrained('material_approvals');

            // Reference to an external document, like Surat Jalan number
            $table->string('reference_document')->nullable();

            $table->text('description'); // What is being inspected? (e.g., "Pemasangan Keramik Area Lobi")
            $table->enum('result', ['Pass', 'Fail', 'Correction Required']);
            $table->text('notes')->nullable();

            $table->foreignId('inspected_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_logs');
    }
};
