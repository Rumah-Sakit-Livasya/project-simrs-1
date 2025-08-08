<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jasa_dokter', function (Blueprint $table) {
            // Tambah kolom 'nominal'
            $table->decimal('nominal', 15, 2)
                ->after('nama_tindakan')
                ->nullable()
            ;
        });

        // Hapus komentar dari kolom (karena Laravel belum support dropComment secara langsung)
        DB::statement("ALTER TABLE jasa_dokter MODIFY ap_number VARCHAR(255) NULL");
        DB::statement("ALTER TABLE jasa_dokter MODIFY ap_date DATE NULL");
        DB::statement("ALTER TABLE jasa_dokter MODIFY bill_date DATE NULL");
    }

    public function down(): void
    {
        Schema::table('jasa_dokter', function (Blueprint $table) {
            $table->dropColumn('nominal');
        });

        // Tambahkan kembali comment jika dibutuhkan
        DB::statement("ALTER TABLE jasa_dokter MODIFY ap_number VARCHAR(255) NULL COMMENT 'AP number'");
        DB::statement("ALTER TABLE jasa_dokter MODIFY ap_date DATE NULL COMMENT 'Tanggal AP dibuat'");
        DB::statement("ALTER TABLE jasa_dokter MODIFY bill_date DATE NULL COMMENT 'Tanggal Bill/Tagihan'");
    }
};
