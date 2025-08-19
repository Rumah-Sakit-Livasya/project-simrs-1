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
        Schema::table('jasa_dokter', function (Blueprint $table) {
            // Tambah kolom untuk AP (Accounts Payable)
            $table->string('ap_number')->nullable()->after('status');
            $table->date('ap_date')->nullable()->after('ap_number');
            $table->date('bill_date')->nullable()->after('ap_date');

            // Index untuk performa
            $table->index(['status', 'ap_date']);
            $table->index(['dokter_id', 'status']);
            $table->index(['registration_id', 'status']);
            $table->index('bill_date');
            $table->index('ap_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jasa_dokter', function (Blueprint $table) {
            $table->dropIndex(['status', 'ap_date']);
            $table->dropIndex(['dokter_id', 'status']);
            $table->dropIndex(['registration_id', 'status']);
            $table->dropIndex(['bill_date']);
            $table->dropIndex(['ap_number']);

            $table->dropColumn(['ap_number', 'ap_date', 'bill_date']);
        });
    }
};
