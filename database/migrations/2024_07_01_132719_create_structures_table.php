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
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_organization');
            $table->unsignedBigInteger('parent_organization')->nullable();
            $table->timestamps();

            $table->foreign('child_organization')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('parent_organization')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};
