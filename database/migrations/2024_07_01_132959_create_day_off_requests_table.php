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
        Schema::create('day_off_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_code_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('photo')->nullable();
            $table->text('description');
            $table->string('is_approved')->default('Pending');
            $table->unsignedBigInteger('approved_line_child')->nullable();
            $table->unsignedBigInteger('approved_line_parent')->nullable();
            $table->timestamps();

            $table->foreign('attendance_code_id')->references('id')->on('attendance_codes')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            // Tambahkan kunci asing setelah memastikan konsistensi data
            $table->foreign('approved_line_child')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('approved_line_parent')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_off_requests');
    }
};
