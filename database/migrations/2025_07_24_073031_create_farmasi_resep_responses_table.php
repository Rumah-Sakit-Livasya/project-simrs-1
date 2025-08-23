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
        Schema::create('farmasi_resep_responses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('re_id')->nullable()->constrained('farmasi_resep_elektroniks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('resep_id')->nullable()->constrained('farmasi_reseps')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('input_resep_user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('input_resep_time')->nullable();
            $table->foreignId('penyiapan_user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('penyiapan_time')->nullable();
            $table->foreignId('racik_user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('racik_time')->nullable();
            $table->foreignId('verifikasi_user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('verifikasi_time')->nullable();
            $table->foreignId('penyerahan_user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('penyerahan_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_resep_responses');
    }
};
