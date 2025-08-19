<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kendaraan (KR4 ERTIGA)
            $table->string('type'); // Jenis (KR4)
            $table->string('license_plate')->unique(); // No. Plat
            $table->string('brand_model'); // Merek & Tipe (Suzuki Ertiga)
            $table->year('model_year'); // Tahun Pembuatan
            $table->date('tax_due_date'); // Pajak Tahunan
            $table->date('stnk_due_date'); // Pajak 5 Ta hunan
            $table->unsignedInteger('service_schedule_km')->nullable(); // Jadwal servis KM
            $table->unsignedInteger('service_schedule_months')->nullable(); // Jadwal servis Bulan
            $table->unsignedInteger('current_km')->default(0); // Kilometer saat ini
            $table->unsignedInteger('last_oil_change_km')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Disable foreign key checks to avoid constraint errors when dropping the table
        Schema::dropIfExists('internal_vehicles');
    }
};
