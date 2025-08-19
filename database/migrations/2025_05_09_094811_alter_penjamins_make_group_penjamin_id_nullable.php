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
        Schema::table('penjamins', function (Blueprint $table) {
            $table->unsignedBigInteger('group_penjamin_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjamins', function (Blueprint $table) {
            // Optional: define default behavior or fail if data integrity is at risk
            $table->unsignedBigInteger('group_penjamin_id')->nullable(false)->change();
        });
    }
};
