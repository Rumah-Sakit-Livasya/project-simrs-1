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
        Schema::table('warehouse_stock_adjustment', function (Blueprint $table) {
            $table->dropForeign(['authorized_user_id']);
            $table->foreign('authorized_user_id')->references('id')->on('users')->cascadeOnDelete()->comment('Authorized user.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_stock_adjustment', function (Blueprint $table) {
            $table->dropForeign(['authorized_user_id']);
            // Restore foreign key without cascadeOnDelete and comment (assuming the previous state)
            $table->foreign('authorized_user_id')->references('user_id')->on('warehouse_stock_adjustment_user')->cascadeOnDelete()->comment('Authorized user.');
        });
    }
};
