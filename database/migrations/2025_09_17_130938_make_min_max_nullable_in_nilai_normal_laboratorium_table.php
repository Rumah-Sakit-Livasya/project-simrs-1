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
        Schema::table('nilai_normal_laboratorium', function (Blueprint $table) {
            // Mengubah kolom min dan max agar bisa menerima nilai NULL
            $table->float('min', 8, 2)->nullable()->change();
            $table->float('max', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_normal_laboratorium', function (Blueprint $table) {
            // Mengembalikan kolom ke kondisi semula (jika perlu rollback)
            $table->float('min', 8, 2)->nullable(false)->change();
            $table->float('max', 8, 2)->nullable(false)->change();
        });
    }
};
