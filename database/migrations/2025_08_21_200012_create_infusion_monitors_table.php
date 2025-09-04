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
        Schema::create('infusion_monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->timestamp('waktu_infus')->comment('Menyimpan Hari/Tanggal dan Jam');
            $table->string('kolf_ke')->nullable()->comment('Nomor Kolf/Labu Infus');
            $table->text('jenis_cairan')->comment('Jenis Cairan dan Kecepatan Tetesan');
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('cairan_masuk')->comment('Dalam cc/ml');
            $table->unsignedInteger('cairan_sisa')->nullable()->comment('Dalam cc/ml');
            $table->string('nama_perawat');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('ID Perawat yang menginput');
            $table->timestamps();
            $table->softDeletes(); // Kolom untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infusion_monitors');
    }
};
