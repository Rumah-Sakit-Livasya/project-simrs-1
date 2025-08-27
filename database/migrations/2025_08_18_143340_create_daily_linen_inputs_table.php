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
        Schema::create('daily_linen_inputs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('linen_type_id')->constrained('linen_types')->onDelete('cascade');
            $table->foreignId('linen_category_id')->constrained('linen_categories')->onDelete('cascade');
            $table->decimal('volume', 8, 2); // Volume dalam Kg
            $table->foreignId('pic_id')->constrained('employees')->onDelete('cascade'); // PIC dari tabel employees
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_linen_inputs');
    }
};
