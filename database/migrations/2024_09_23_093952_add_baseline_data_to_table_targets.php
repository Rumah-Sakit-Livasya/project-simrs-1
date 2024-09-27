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
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn(['max_target', 'min_target', 'difference']);
            $table->string('baseline_data')->after('status');
            $table->string('movement')->after('target');
            $table->string('persentase')->after('movement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->integer('min_target')->nullable();
            $table->integer('max_target')->nullable();
            $table->integer('different')->nullable();
            $table->string('baseline_data')->after('status');
            $table->string('movement')->after('target');
            $table->string('persentase')->after('movement');
        });
    }
};
