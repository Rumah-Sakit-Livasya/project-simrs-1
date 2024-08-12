<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasRawatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_rawat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_penjamin_id')->constrained('group_penjamins')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kelas');
            $table->string('urutan');
            $table->string('keterangan');
            $table->boolean('isICU')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelas_rawats');
    }
}
