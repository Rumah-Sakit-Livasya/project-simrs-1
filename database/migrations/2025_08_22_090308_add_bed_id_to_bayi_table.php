<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBedIdToBayiTable extends Migration
{
    public function up()
    {
        Schema::table('bayi', function (Blueprint $table) {
            // Menambahkan foreign key ke tabel beds
            // Pastikan Anda sudah punya tabel 'beds'
            $table->unsignedBigInteger('bed_id')->nullable()->after('doctor_id');

            $table->foreign('bed_id')->references('id')->on('beds')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('bayi', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
            $table->dropColumn('bed_id');
        });
    }
}
