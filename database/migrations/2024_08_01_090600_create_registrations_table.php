<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('user_id');
            $table->foreignId('employee_id');
            $table->foreignId('penjamin_id');
            $table->string('registration_type');
            $table->string('registration_date');
            $table->string('registration_number');
            $table->string('diagnosa_awal');
            $table->boolean('kartu_pasien');
            $table->string('rujukan');
            $table->string('doctor_perujuk')->nullable();
            $table->string('diagnosa')->nullable();
            $table->string('tipe_order')->nullable();
            $table->string('order_lab')->nullable();
            $table->string('order_rad')->nullable();
            $table->string('kelas_rawat_id')->nullable();
            $table->string('poliklinik_id')->nullable();
            $table->string('paket')->nullable();
            $table->string('tipe_jadwal')->nullable();
            $table->string('igd_type')->nullable();
            $table->string('pelayanan')->nullable();
            $table->string('kamar_tujuan')->nullable();
            $table->string('prosedur_masuk')->nullable();
            $table->string('titip_kelas_rawat')->nullable();
            $table->string('tipe_perawatan')->nullable();
            $table->string('tindakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
