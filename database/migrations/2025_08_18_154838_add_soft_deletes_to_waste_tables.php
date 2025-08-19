<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Method ini akan dijalankan saat `php artisan migrate`.
     */
    public function up(): void
    {
        // Menambahkan kolom softDeletes ke tabel waste_transports
        Schema::table('waste_transports', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan membuat kolom 'deleted_at'
        });

        // Menambahkan kolom softDeletes ke tabel daily_waste_inputs
        Schema::table('daily_waste_inputs', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Menambahkan kolom softDeletes ke tabel waste_categories
        Schema::table('waste_categories', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * Method ini akan dijalankan saat `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        // Menghapus kolom softDeletes dari tabel waste_transports
        Schema::table('waste_transports', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menghapus kolom 'deleted_at'
        });

        // Menghapus kolom softDeletes dari tabel daily_waste_inputs
        Schema::table('daily_waste_inputs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Menghapus kolom softDeletes dari tabel waste_categories
        Schema::table('waste_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
