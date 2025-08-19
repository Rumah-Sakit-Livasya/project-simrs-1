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
        Schema::table('banks', function (Blueprint $table) {
            $table->decimal('saldo_awal', 15, 2)->default(0)->after('name');
            $table->decimal('total_masuk', 15, 2)->default(0)->after('saldo_awal');
            $table->decimal('total_keluar', 15, 2)->default(0)->after('total_masuk');
            $table->decimal('saldo_akhir', 15, 2)->virtualAs('saldo_awal + total_masuk - total_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['saldo_awal', 'total_masuk', 'total_keluar']);
        });
    }
};
