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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('no_mou')->nullable()->after('is_active');
            $table->string('mou_period')->nullable()->after('no_mou');
            $table->date('mou_start_date')->nullable()->after('mou_period');
            $table->date('mou_end_date')->nullable()->after('mou_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['no_mou', 'mou_start_date', 'mou_period', 'mou_end_date']);
        });
    }
};
