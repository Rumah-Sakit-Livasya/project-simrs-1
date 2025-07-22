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
        Schema::create('order_alat_medis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_order');
            $table->foreignId('user_id')->constrained('users'); // Assuming you have a doctors table
            $table->foreignId('registration_id')->constrained('registrations'); // Assuming you have a doctors table
            $table->foreignId('doctor_id')->constrained('doctors'); // Assuming you have a doctors table
            $table->foreignId('departement_id')->constrained('departements'); // Assuming you have a departements table
            $table->foreignId('peralatan_id')->constrained('peralatan'); // Assuming you have a tindakan_medis table
            $table->foreignId('kelas_rawat_id')->constrained('kelas_rawat'); // Assuming you have a tindakan_medis table
            // $table->string('kelas');
            $table->integer('qty');
            $table->string('lokasi', 20);
            $table->boolean('diskon_dokter')->default(false);
            $table->string('entry_by');
            $table->boolean('is_edit')->nullable();
            $table->string('edit_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_alat_medis');
    }
};
