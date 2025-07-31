<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pertanggungjawabans', function (Blueprint $table) {
            // Drop foreign key yang sudah ada dulu
            $table->dropForeign(['pencairan_id']);

            // Tambah ulang dengan cascading
            $table->foreign('pencairan_id')
                ->references('id')
                ->on('pencairans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pertanggungjawabans', function (Blueprint $table) {
            // Rollback hanya drop foreign key
            $table->dropForeign(['pencairan_id']);

            // Tambah kembali tanpa cascade (opsional)
            $table->foreign('pencairan_id')
                ->references('id')
                ->on('pencairans');
        });
    }
};
