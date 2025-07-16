<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_internal', function (Blueprint $table) {
            // Menambahkan kolom 'unit_terkait' sebagai unsignedBigInteger jika merujuk ke ID unit
            $table->unsignedBigInteger('unit_terkait')->nullable()->after('jenis');

            // Jika 'unit_terkait' mengacu pada tabel lain (misal 'organizations'), tambahkan foreign key
            $table->foreign('unit_terkait')->references('id')->on('organizations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_internal', function (Blueprint $table) {
            // Menghapus kolom 'unit_terkait' dan foreign key jika ada
            $table->dropForeign(['unit_terkait']);
            $table->dropColumn('unit_terkait');
        });
    }
};
