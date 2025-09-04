<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEwsObstetriTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ews_obstetri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tgl');
            $table->time('jam');
            $table->string('laju_respirasi');
            $table->string('saturasi');
            $table->string('suplemen')->nullable();
            $table->string('temperatur');
            $table->string('tekanan_darah_sistolik');
            $table->string('tekanan_darah_diastole');
            $table->string('laju_jantung');
            $table->string('kesadaran');
            $table->string('discharge');
            $table->string('proteinuria');
            $table->integer('skor_total');
            $table->string('gds')->nullable();
            $table->string('skor_nyeri')->nullable();
            $table->string('urin_output')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ews_obstetri');
    }
}
