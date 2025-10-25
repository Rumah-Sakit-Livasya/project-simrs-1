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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique()->comment('Nomor Agenda atau Nomor Dokumen Resmi');
            $table->string('title');
            $table->text('description')->nullable();

            // Foreign key baru untuk document_type_id
            $table->foreignId('document_type_id')->constrained('document_types');

            // Enum untuk melacak status
            $table->enum('status', ['Diajukan', 'Diterima', 'Direview', 'Revisi', 'Disetujui', 'Dibalas']);

            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');

            // Relasi ke user yang mengupload
            $table->foreignId('uploader_id')->constrained('users');

            // Relasi ke user penanggung jawab (khusus untuk surat)
            $table->foreignId('person_in_charge_id')->nullable()->constrained('users');

            // Untuk Versioning Control Gambar
            $table->foreignId('parent_id')->nullable()->constrained('documents')->onDelete('cascade');
            $table->integer('version')->default(1);
            $table->boolean('is_latest')->default(true)->comment('Menandai apakah ini versi terbaru');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
