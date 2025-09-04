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
        Schema::create('registration_otc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('employee_id');
            $table->foreignId('penjamin_id');
            $table->foreignId('departement_id');
            $table->enum("tipe_pasien", ["rawat-jalan", "rawat-inap", "otc"])->default("otc");
            $table->text('nama_pasien');
            $table->date('date_of_birth')->nullable();
            $table->foreignId("patient_id")->nullable();
            $table->text('poly_ruang')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('order_date');
            $table->string('registration_number');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->text('alamat')->nullable();
            $table->text('no_telp')->nullable();
            $table->text('diagnosa_klinis')->nullable();
            $table->string('no_urut')->nullable();
            $table->string('order_type')->nullable();
            $table->string('order_lab')->nullable();
            $table->string('order_rad')->nullable();
            $table->enum('embalase', ["Racikan", "Item", "Tidak"])->nullable();
            $table->boolean('bmhp')->nullable();
            $table->boolean('kronis')->nullable();
            $table->boolean('dispensing')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_otc');
    }
};
