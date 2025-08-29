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
        Schema::create('farmasi_antrians', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('re_id')->constrained('farmasi_resep_elektroniks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('resep_id')->constrained('farmasi_reseps')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('tipe', ['bpjs', 'umum']);
            $table->string('antrian');
            $table->boolean('racikan')->default(false);
            $table->boolean('dipanggil')->default(false);
            $table->boolean('penyerahan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmasi_antrians');
    }
};
