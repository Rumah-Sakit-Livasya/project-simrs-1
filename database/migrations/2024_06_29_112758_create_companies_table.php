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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('phone_number', 18)->nullable();
            $table->string('email', 30)->nullable();
            $table->longText('address')->nullable();
            $table->string('province', 20)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('logo', 50)->nullable();
            $table->string('category', 30)->nullable();
            $table->string('class', 10)->nullable();
            $table->string('operating_permit_number', 50)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('radius')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
