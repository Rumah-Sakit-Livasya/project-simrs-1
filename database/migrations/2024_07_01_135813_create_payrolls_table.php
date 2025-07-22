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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->string('employee_name');
            $table->integer('basic_salary');
            $table->integer('tunjangan_jabatan');
            $table->integer('tunjangan_profesi');
            $table->integer('tunjangan_makan_dan_transport');
            $table->integer('tunjangan_masa_kerja');
            $table->integer('guarantee_fee');
            $table->integer('uang_duduk');
            $table->integer('tax_allowance');
            $table->integer('total_allowance');
            $table->integer('potongan_keterlambatan');
            $table->integer('potongan_izin');
            $table->integer('potongan_sakit')->default(0);
            $table->integer('simpanan_pokok');
            $table->integer('potongan_koperasi');
            $table->integer('potongan_absensi');
            $table->integer('potongan_bpjs_kesehatan');
            $table->integer('potongan_bpjs_ketenagakerjaan');
            $table->integer('potongan_pajak');
            $table->integer('total_deduction');
            $table->integer('take_home_pay');
            $table->string('periode');
            $table->integer('hari_kerja');
            $table->boolean('is_review')->default(0);
            $table->boolean('is_edit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
