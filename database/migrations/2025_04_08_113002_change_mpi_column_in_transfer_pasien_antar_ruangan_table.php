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
        Schema::table('transfer_pasien_antar_ruangan', function (Blueprint $table) {
            $table->string('mpi')->nullable()->change();
            $table->string('ap')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_pasien_antar_ruangan', function (Blueprint $table) {
            $table->string('mpi')->nullable(false)->change();
            $table->string('ap')->nullable(false)->change();
        });
    }
};
