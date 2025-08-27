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
        Schema::create('daily_waste_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->foreignId('pic')->constrained('employees')->onDelete('cascade'); // PIC (CS) constrained ke employees
            $table->date('date');
            $table->decimal('volume', 8, 2); // Volume dalam KG atau unit lain
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_waste_inputs');
    }
};
