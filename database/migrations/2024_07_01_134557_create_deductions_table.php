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
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->integer("potongan_keterlambatan")->default(0);
            $table->integer("potongan_izin")->default(0);
            $table->integer("simpanan_pokok")->default(0);
            $table->integer("potongan_koperasi")->default(0);
            $table->integer("potongan_absensi")->default(0);
            $table->integer("potongan_bpjs_kesehatan")->default(0);
            $table->integer("potongan_bpjs_ketenagakerjaan")->default(0);
            $table->integer("potongan_pajak")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
