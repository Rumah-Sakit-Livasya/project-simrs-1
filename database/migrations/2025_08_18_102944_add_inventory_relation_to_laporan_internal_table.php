<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_internal', function (Blueprint $table) {
            // Kolom untuk menandai apakah ini maintenance atau perbaikan
            $table->string('jenis_kendala')->nullable()->after('jenis');

            // Kolom untuk relasi ke ruangan
            $table->foreignId('room_maintenance_id')->nullable()->after('unit_terkait')
                ->constrained('room_maintenance')->onDelete('set null');

            // Kolom untuk relasi ke barang
            $table->foreignId('barang_id')->nullable()->after('room_maintenance_id')
                ->constrained('barang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_internal', function (Blueprint $table) {
            $table->dropForeign(['room_maintenance_id']);
            $table->dropForeign(['barang_id']);
            $table->dropColumn(['jenis_kendala', 'room_maintenance_id', 'barang_id']);
        });
    }
};
