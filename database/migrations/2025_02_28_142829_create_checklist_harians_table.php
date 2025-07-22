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
        Schema::create('checklist_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_harian_category_id')->constrained('checklist_harian_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kegiatan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_harian');
    }
};
