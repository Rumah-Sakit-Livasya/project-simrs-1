<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeysOnPencairansTable extends Migration
{
    public function up()
    {
        Schema::table('pencairans', function (Blueprint $table) {
            // CUKUP langsung tambahkan foreign key
            $table->foreign('pengajuan_id')
                ->references('id')
                ->on('pengajuans') // ✅ sesuai nama tabel asli
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pencairans', function (Blueprint $table) {
            // Drop foreign key jika ingin rollback
            $table->dropForeign(['pengajuan_id']);
        });
    }
}
