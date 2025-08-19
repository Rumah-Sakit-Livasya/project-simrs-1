<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeysOnPencairansTable extends Migration
{
    public function up()
    {
        Schema::table('pencairans', function (Blueprint $table) {
            // Drop existing foreign key first
            $table->dropForeign('pencairans_pengajuan_id_foreign');

            // Re-add foreign key with restrict instead of cascade
            $table->foreign('pengajuan_id')
                ->references('id')
                ->on('pengajuans')
                ->onDelete('restrict')
                ->name('fk_pencairans_pengajuan_id'); // Give unique constraint name
        });
    }

    public function down()
    {
        Schema::table('pencairans', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign('fk_pencairans_pengajuan_id');

            // Restore original foreign key with cascade
            $table->foreign('pengajuan_id')
                ->references('id')
                ->on('pengajuans')
                ->onDelete('cascade')
                ->name('fk_pencairans_pengajuan_id'); // Use same unique name
        });
    }
}
