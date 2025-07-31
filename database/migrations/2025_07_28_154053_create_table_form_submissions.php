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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();

            // Kunci untuk menghubungkan data ini dengan pasien/registrasi tertentu
            $table->unsignedBigInteger('registration_id');
            $table->foreign('registration_id')->references('id')->on('registrations')->cascadeOnDelete();

            // Kunci untuk mengetahui form mana yang diisi
            $table->unsignedBigInteger('form_template_id');
            $table->foreign('form_template_id')->references('id')->on('form_templates')->cascadeOnDelete();

            // Kolom utama untuk menyimpan semua nilai form sebagai JSON
            $table->json('form_values'); // Gunakan tipe 'json' jika database Anda mendukung (MySQL 5.7+, PostgreSQL)

            // Meta-data tambahan
            $table->boolean('is_final')->default(false); // Status, apakah sudah final atau masih draft
            $table->unsignedBigInteger('user_id'); // Siapa yang mengisi/memperbarui
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();

            // Opsional: Tambahkan unique constraint agar satu registrasi hanya bisa punya satu entri untuk satu template form
            $table->unique(['registration_id', 'form_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission');
    }
};
