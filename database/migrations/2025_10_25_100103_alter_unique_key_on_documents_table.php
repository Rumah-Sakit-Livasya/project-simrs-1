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
        Schema::table('documents', function (Blueprint $table) {
            // 1. Hapus index unik yang lama
            $table->dropUnique('documents_document_number_unique');

            // 2. Tambahkan index unik gabungan yang baru
            $table->unique(['document_number', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1. Hapus index unik gabungan
            $table->dropUnique(['document_number', 'version']);

            // 2. Kembalikan index unik yang lama
            $table->unique('document_number', 'documents_document_number_unique');
        });
    }
};
