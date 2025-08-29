<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            // Hapus kolom string 'ruangan' yang lama
            $table->dropColumn('ruangan');

            // Tambahkan kolom foreign key baru setelah jenis_kegiatan_id
            $table->foreignId('room_maintenance_id')
                ->after('jenis_kegiatan_id')
                ->constrained('room_maintenance');
        });
    }

    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            // Rollback: Hapus foreign key dan kolomnya
            $table->dropForeign(['room_maintenance_id']);
            $table->dropColumn('room_maintenance_id');

            // Rollback: Kembalikan kolom string 'ruangan'
            $table->string('ruangan')->after('jenis_kegiatan_id');
        });
    }
};
