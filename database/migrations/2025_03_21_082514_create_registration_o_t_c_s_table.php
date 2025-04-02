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
            $table->text('nama_pasien');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('registration_date');
            $table->string('registration_number');
            $table->text('doctor')->nullable();
            $table->text('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->text('diagnosa_klinis')->nullable();
            $table->string('no_urut')->nullable();
            $table->string('tipe_order')->nullable();
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
