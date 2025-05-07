<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jam_makan_gizi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->time('jam');
            $table->string('waktu_makan');
            $table->boolean('aktif')->default(true);
            $table->boolean('auto_order')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_makan_gizi');
    }
};
