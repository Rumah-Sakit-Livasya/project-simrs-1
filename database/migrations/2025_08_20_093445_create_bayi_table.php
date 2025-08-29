<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayiTable extends Migration
{
    public function up()
    {
        Schema::create('bayi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_persalinan_id')->nullable()->index();
            $table->unsignedBigInteger('registration_id')->nullable()->index();
            $table->string('no_rm')->nullable(); // No RM
            $table->string('nama_bayi')->nullable(); // Nama Bayi
            $table->dateTime('tgl_lahir')->nullable(); // Tgl Lahir
            $table->dateTime('tgl_reg')->nullable(); // Tgl Reg
            $table->string('no_label')->nullable(); // No Label
            $table->string('nama_keluarga')->nullable(); // Nama Keluarga
            $table->string('tempat_lahir')->nullable(); // Tempat, Tgl Lahir (bagian tempat)
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(); // Jenis Kelamin
            $table->decimal('panjang', 5, 2)->nullable()->default(0); // Panjang (cm)
            $table->decimal('berat', 5, 2)->nullable()->default(0); // Berat (gr)
            $table->decimal('lingkar_kepala', 5, 2)->nullable()->default(0); // Lingkar Kepala (cm)
            $table->decimal('lingkar_dada', 5, 2)->nullable()->default(0); // Lingkar Dada (cm)
            $table->dateTime('tgl_jam_registrasi')->nullable(); // Tgl Jam Registrasi

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('kelas_kamar')->nullable(); // Kelas / Kamar
            $table->enum('status_lahir', ['Hidup', 'Meninggal'])->nullable(); // Status Lahir
            $table->enum('jenis_kelahiran', ['Tunggal', 'Kembar'])->nullable(); // Jenis Kelahiran
            $table->integer('kelahiran_ke')->nullable()->default(0); // Kelahiran Ke
            $table->string('kelainan_fisik')->nullable(); // Kelainan Fisik
            $table->string('kelahiran_normal')->nullable(); // Kelahiran Normal
            $table->string('kelahiran_dgn_tindakan')->nullable(); // Kelahiran Dgn Tindakan
            $table->integer('apgar_score_1_minute')->nullable(); // Apgar Score 1 Minute
            $table->integer('apgar_score_5_minutes')->nullable(); // Apgar Score 5 Minutes
            $table->integer('gestasi')->nullable()->default(0); // Gestasi
            $table->string('pregnant_g')->nullable(); // Pregnant G
            $table->string('pregnant_p')->nullable(); // Pregnant P
            $table->string('pregnant_a')->nullable(); // Pregnant A
            $table->string('placenta_weight')->nullable(); // Placenta (weight)
            $table->string('placenta_measure')->nullable(); // Placenta (measure)
            $table->string('placenta_anomaly')->nullable(); // Placenta (anomaly)
            $table->string('pregnant_complication')->nullable(); // Pregnant Complication
            $table->string('partus')->nullable(); // Partus
            $table->string('partus_complication')->nullable(); // Partus Complication
            $table->timestamps();

            // Foreign key constraints (opsional, sesuaikan dengan tabel terkait)
            $table->foreign('order_persalinan_id')->references('id')->on('order_persalinan')->onDelete('cascade');
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bayi');
    }
}
