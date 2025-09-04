<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEWSAnakTable extends Migration
{
    public function up()
    {
        Schema::create('ews_anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tgl');
            $table->time('jam');
            $table->string('keadaan_umum');
            $table->string('kardio_vaskular');
            $table->string('respirasi');
            $table->integer('skor_total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ews_anak');
    }
}
