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
        Schema::create('attendance_request_lamp_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_request_lamp_id');
            $table->foreign('attendance_request_lamp_id', 'arl_id_foreign')
                ->references('id')
                ->on('attendance_request_lamp');
            $table->foreignId('employee_id')->constrained('employees');
            $table->time('clock_in');
            $table->time('clock_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_request_lamp_detail');
    }
};
