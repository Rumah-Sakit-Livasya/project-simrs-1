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
            $table->text('goal')->nullable()->after('hasil');
            $table->text('key_result')->nullable()->after('goal');
            $table->text('initiative')->nullable()->after('key_result');
            $table->text('anggaran')->nullable()->after('initiative');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->text('goal')->nullable()->after('hasil');
            $table->text('key_result')->nullable()->after('goal');
            $table->text('initiative')->nullable()->after('key_result');
            $table->text('anggaran')->nullable()->after('initiative');
        });
    }
};
