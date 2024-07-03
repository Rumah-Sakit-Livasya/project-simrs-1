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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('basic_salary')->default(0);
            $table->integer('tunjangan_jabatan')->default(0);
            $table->integer('tunjangan_profesi')->default(0);
            $table->integer('tunjangan_makan_dan_transport')->default(0);
            $table->integer('tunjangan_masa_kerja')->default(0);
            $table->integer('guarantee_fee')->default(0);
            $table->integer('uang_duduk')->default(0);
            $table->integer('tax_allowance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
