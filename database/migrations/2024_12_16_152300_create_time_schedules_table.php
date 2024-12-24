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
        Schema::create('time_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->string('title');
            $table->text('perihal');
            $table->enum('type', ['rapat', 'kegiatan']);
            $table->datetime('datetime');
            $table->string('undangan');
            $table->string('materi')->nullable();
            $table->string('absensi')->nullable();
            $table->string('notulen')->nullable();
            $table->boolean('is_online')->default(0);
            $table->string('room_name')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_schedules');
    }
};
