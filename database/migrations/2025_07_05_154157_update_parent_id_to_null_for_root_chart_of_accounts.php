<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // Penting untuk di-import

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah parent_id menjadi NULL untuk akun level teratas.
     */
    public function up(): void
    {
        DB::table('chart_of_account')
            ->whereRaw('id = parent_id')
            ->update(['parent_id' => null]);
    }

    /**
     * Reverse the migrations.
     * Mengembalikan parent_id ke nilai aslinya (menunjuk ke diri sendiri).
     */
    public function down(): void
    {
        // Perintah ini akan mengembalikan kondisi seperti semula jika migrasi di-rollback.
        DB::table('chart_of_account')
            ->whereNull('parent_id')
            ->update(['parent_id' => DB::raw('id')]);
    }
};
