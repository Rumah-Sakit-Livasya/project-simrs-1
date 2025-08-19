<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk memperbarui tabel.
     *
     * @return void
     */
    public function up()
    {
        // Menggunakan Schema::table untuk mengubah tabel yang sudah ada
        Schema::table('ap_supplier_detail', function (Blueprint $table) {

            // 1. Ganti nama kolom terlebih dahulu.
            $table->renameColumn('penerimaan_barang_id', 'penerimaan_barang_header_id');

            // 2. Hapus kolom 'penerimaan_barang_type' yang sudah tidak digunakan.
            $table->dropColumn('penerimaan_barang_type');
        });

        // 3. Lakukan perubahan tipe data pada kolom yang sudah di-rename.
        //    Seringkali lebih aman melakukannya di blok terpisah.
        Schema::table('ap_supplier_detail', function (Blueprint $table) {
            // Ubah tipe data dari INT (default rename) menjadi BIGINT UNSIGNED
            $table->bigInteger('penerimaan_barang_header_id')->unsigned()->change();
        });
    }

    /**
     * Batalkan migrasi (rollback).
     *
     * @return void
     */
    public function down()
    {
        // Logika di sini harus membalikkan semua yang dilakukan di method up()
        Schema::table('ap_supplier_detail', function (Blueprint $table) {

            // 1. Ubah tipe data kembali ke integer sebelum di-rename
            $table->integer('penerimaan_barang_header_id')->change();

            // 2. Ganti nama kolom kembali ke nama aslinya
            $table->renameColumn('penerimaan_barang_header_id', 'penerimaan_barang_id');

            // 3. Tambahkan kembali kolom yang dihapus
            $table->enum('penerimaan_barang_type', ['farmasi', 'non_farmasi'])
                ->after('penerimaan_barang_id'); // Menempatkannya kembali di posisi semula
        });
    }
};
