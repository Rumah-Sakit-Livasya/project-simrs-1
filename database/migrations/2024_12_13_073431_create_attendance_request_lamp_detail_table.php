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
            $table->unsignedBigInteger('attendance_request_lamp_id');
            $table->foreign('attendance_request_lamp_id')
                ->references('id')
                ->on('attendance_request_lamp')
                ->cascadeOnDelete()
                ->name('fk_att_req_lamp'); // Berikan nama constraint yang lebih pendek
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->name('fk_employee'); // Berikan nama constraint yang lebih pendek
            $table->date('tanggal');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('attendance_request_lamp_detail', function (Blueprint $table) {
        //     // Hapus foreign key dengan nama default
        //     $table->dropForeign(['attendance_request_lamp_id']); // Foreign key default Laravel
        //     $table->dropForeign(['employee_id']); // Foreign key default Laravel
        // });

        // Hapus tabel setelah constraints dihapus
        Schema::dropIfExists('attendance_request_lamp_detail');
    }
};
