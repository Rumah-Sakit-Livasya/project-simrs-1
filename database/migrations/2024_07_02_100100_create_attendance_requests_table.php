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
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('clockin')->nullable();
            $table->string('clockout')->nullable();
            $table->string('file')->nullable();
            $table->text('description')->nullable();
            $table->string('is_approved')->default('Pending');
            $table->unsignedBigInteger('approved_line_child')->nullable();
            $table->unsignedBigInteger('approved_line_parent')->nullable();

            // Tambahkan kunci asing setelah memastikan konsistensi data
            $table->foreign('approved_line_child')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('approved_line_parent')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
