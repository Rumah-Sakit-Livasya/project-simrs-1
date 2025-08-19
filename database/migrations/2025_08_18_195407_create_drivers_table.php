<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel employees. Unik karena 1 employee hanya bisa jadi 1 driver.
            $table->foreignId('employee_id')->unique()->constrained()->onDelete('cascade');
            $table->string('no_sim')->unique();
            $table->date('masa_berlaku_sim');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
