<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bayi', function (Blueprint $table) {
            $table->foreignId('order_operasi_id')->nullable()->constrained('order_operasi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bayi', function (Blueprint $table) {
            $table->dropForeign(['order_operasi_id']);
            $table->dropColumn('order_operasi_id');
        });
    }
};
