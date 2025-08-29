<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKelasKamarToForeignKeyInBayiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bayi', function (Blueprint $table) {
            // 1. Tambahkan kolom baru 'kelas_rawat_id' setelah 'doctor_id'
            $table->foreignId('kelas_rawat_id')
                ->nullable()
                ->after('doctor_id') // Menempatkan kolom agar rapi
                ->constrained('kelas_rawat')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // 2. Hapus kolom lama 'kelas_kamar'
            $table->dropColumn('kelas_kamar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bayi', function (Blueprint $table) {
            // 1. Tambahkan kembali kolom lama 'kelas_kamar'
            $table->string('kelas_kamar')->nullable()->after('doctor_id');

            // 2. Hapus foreign key dan kolom baru 'kelas_rawat_id'
            $table->dropForeign(['kelas_rawat_id']);
            $table->dropColumn('kelas_rawat_id');
        });
    }
}
