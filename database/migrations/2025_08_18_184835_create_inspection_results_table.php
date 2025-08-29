<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->foreignId('internal_vehicle_id')->constrained('internal_vehicles')->onDelete('cascade');
            $table->foreignId('inspection_item_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['Baik', 'Rusak']);
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_results');
    }
};
