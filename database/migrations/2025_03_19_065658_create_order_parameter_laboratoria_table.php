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
        Schema::create('order_parameter_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_laboratorium_id')->constrained('order_laboratorium')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('parameter_laboratorium_id')->constrained('parameter_laboratorium')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('nominal_rupiah');
            $table->foreignId('doctor_id')->nullable()->constrained('employees')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('verifikator_id')->nullable()->constrained('employees')->onUpdate('cascade');
            $table->date('verifikasi_date')->nullable();
            $table->text('hasil')->nullable();
            $table->text('nreff')->nullable();
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_parameter_laboratorium');
    }
};
