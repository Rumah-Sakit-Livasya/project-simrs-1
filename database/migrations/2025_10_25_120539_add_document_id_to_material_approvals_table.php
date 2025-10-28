<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            // Kolom foreign key ke tabel documents.
            // Dibuat nullable karena mungkin ada material yang dicatat tanpa dokumen awal.
            // 'after' ditempatkan setelah kolom id untuk kerapian.
            $table->foreignId('document_id')->nullable()->after('id')->constrained('documents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('material_approvals', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');
        });
    }
};
