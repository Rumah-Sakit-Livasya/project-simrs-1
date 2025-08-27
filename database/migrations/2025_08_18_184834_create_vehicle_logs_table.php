<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_vehicle_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('driver_id');
            // $table->foreignId('driver_id')->constrained()->onDelete('cascade');

            // Data Saat Peminjaman (Keluar)
            $table->dateTime('start_datetime');
            $table->unsignedInteger('start_odometer');
            $table->string('destination');

            // Data Saat Pengembalian (Kembali)
            $table->dateTime('end_datetime')->nullable();
            $table->unsignedInteger('end_odometer')->nullable();
            $table->string('fuel_receipt_path')->nullable(); // Path bukti BBM
            $table->text('return_notes')->nullable(); // Catatan kondisi saat kembali

            $table->enum('status', ['Digunakan', 'Selesai'])->default('Digunakan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_logs');
    }
};
