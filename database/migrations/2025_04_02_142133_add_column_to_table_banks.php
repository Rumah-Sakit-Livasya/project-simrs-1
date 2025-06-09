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
        Schema::table('bank_perusahaan', function (Blueprint $table) {
            $table->foreignId('akun_kas_bank')->after('id')->nullable();
            $table->foreignId('akun_kliring')->after('akun_kas_bank')->nullable(); // coa_id
            $table->boolean('is_aktivasi')->after('akun_kliring')->nullable(); // coa_id
            $table->boolean('is_bank')->after('is_aktivasi')->nullable();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_perusahaan', function (Blueprint $table) {
            // Then drop the columns
            $table->dropColumn([
                'akun_kas_bank',
                'akun_kliring',
                'is_aktivasi',
                'is_bank',
                // 'deleted_at'
            ]);
        });
    }
};
