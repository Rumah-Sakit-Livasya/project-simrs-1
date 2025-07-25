<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jasa_dokter', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('dokter_id')->nullable(); // harus nullable untuk SET NULL
            $table->unsignedBigInteger('order_tindakan_medis_id')->nullable();

            // Informasi tindakan
            $table->string('nama_tindakan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);

            // Pajak & hasil
            $table->decimal('ppn_persen', 5, 2)->default(11.00); // default 11%
            $table->decimal('jkp', 15, 2)->nullable();           // nilai setelah PPN
            $table->decimal('jasa_dokter', 15, 2)->nullable();   // sebelum potongan lainnya
            $table->decimal('share_dokter', 15, 2);              // nilai final

            // Status
            $table->enum('status', ['draft', 'final'])->default('draft');

            $table->timestamps();

            // Foreign key
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('dokter_id')->references('id')->on('doctors')->onDelete('set null');
            $table->foreign('order_tindakan_medis_id')->references('id')->on('order_tindakan_medis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jasa_dokter');
    }
};
