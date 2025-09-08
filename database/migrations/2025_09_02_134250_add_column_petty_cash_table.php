<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update tabel petty_cash
        Schema::table('petty_cash', function (Blueprint $table) {
            if (!Schema::hasColumn('petty_cash', 'kode_transaksi')) {
                $table->string('kode_transaksi')->unique()->after('id');
            }
            if (!Schema::hasColumn('petty_cash', 'total_nominal')) {
                $table->decimal('total_nominal', 15, 2)->default(0)->after('keterangan');
            }
            if (!Schema::hasColumn('petty_cash', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('kas_id');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });

        // Update tabel petty_cash_detail
        Schema::table('petty_cash_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('petty_cash_detail', 'cost_center_id')) {
                $table->unsignedBigInteger('cost_center_id')->nullable()->after('coa_id');

                // Foreign key ke cost_center (atau tabel sesuai nama kamu)
                $table->foreign('cost_center_id')
                    ->references('id')
                    ->on('rnc_centers') // ganti sesuai nama tabel cost center kamu
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Rollback tabel petty_cash
        Schema::table('petty_cash', function (Blueprint $table) {
            if (Schema::hasColumn('petty_cash', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('petty_cash', 'kode_transaksi')) {
                $table->dropColumn('kode_transaksi');
            }
            if (Schema::hasColumn('petty_cash', 'total_nominal')) {
                $table->dropColumn('total_nominal');
            }
        });

        // Rollback tabel petty_cash_detail
        Schema::table('petty_cash_detail', function (Blueprint $table) {
            if (Schema::hasColumn('petty_cash_detail', 'cost_center_id')) {
                $table->dropForeign(['cost_center_id']);
                $table->dropColumn('cost_center_id');
            }
        });
    }
};
