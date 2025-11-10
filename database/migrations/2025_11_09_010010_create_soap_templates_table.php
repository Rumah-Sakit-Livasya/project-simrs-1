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
        Schema::create('soap_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->text('subjective')->nullable();
            $table->text('objective')->nullable();
            $table->text('assesment')->nullable();
            $table->text('planning')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soap_templates');
    }
};
