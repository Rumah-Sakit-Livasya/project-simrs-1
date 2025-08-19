<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('jasa_dokter', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan membuat kolom deleted_at bertipe timestamp nullable
        });
    }

    public function down()
    {
        Schema::table('jasa_dokter', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
