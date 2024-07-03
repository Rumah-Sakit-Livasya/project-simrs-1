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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->date('date')->nullable();
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->integer('late_clock_in')->nullable();
            $table->integer('early_clock_out')->nullable();
            $table->string('location', 90)->nullable();
            $table->integer('is_day_off')->nullable();
            $table->unsignedBigInteger('day_off_request_id')->nullable();
            $table->unsignedBigInteger('attendance_code_id')->nullable();

            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('attendance_code_id')->references('id')->on('attendance_codes')->onDelete('cascade');
            $table->foreign('day_off_request_id')->references('id')->on('day_off_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
