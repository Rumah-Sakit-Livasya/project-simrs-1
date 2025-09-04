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
        Schema::table('bilingan', function (Blueprint $table) {
            $table->string('jkp')->nullable()->after('wajib_bayar');
            $table->string('invoice')->nullable()->after('jkp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bilingan', function (Blueprint $table) {
            $table->dropColumn(['jkp', 'invoice']);
        });
    }
};
