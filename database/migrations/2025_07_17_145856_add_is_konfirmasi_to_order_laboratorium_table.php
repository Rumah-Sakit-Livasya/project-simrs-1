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
        Schema::table('order_laboratorium', function (Blueprint $table) {
            // Menambahkan kolom is_konfirmasi setelah kolom status_billed
            $table->boolean('is_konfirmasi')->default(false)->after('status_billed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_laboratorium', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('is_konfirmasi');
        });
    }
};
