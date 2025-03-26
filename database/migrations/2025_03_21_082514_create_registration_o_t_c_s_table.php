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
            $table->text('nama_pasien');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('doctor');
            $table->text('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->text('agama')->nullable();
            $table->text('diagnosa_klinis')->nullable();
            $table->foreignId('kelurahan')->on('kelurahan')->onDelete('cascade');
            $table->foreignId('kecamatan')->on('kecamatan')->onDelete('cascade');
            $table->foreignId('kabupaten')->on('kabupaten')->onDelete('cascade');
            $table->foreignId('user_id');
            $table->foreignId('employee_id');
            $table->foreignId('penjamin_id');
            $table->foreignId('departement_id');
            $table->string('registration_date');
            $table->string('registration_number');
            $table->string('no_urut')->nullable();
            $table->string('tipe_order')->nullable();
            $table->string('order_lab')->nullable();
            $table->string('order_rad')->nullable();
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
