<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plasma_display_rawat_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kolom untuk 'nama_loket'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plasma_display_rawat_jalans');
    }
};
