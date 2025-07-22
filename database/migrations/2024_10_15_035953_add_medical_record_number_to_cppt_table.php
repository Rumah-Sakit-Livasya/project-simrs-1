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
        Schema::table('patients', function (Blueprint $table) {
            // Tambahkan kolom 'medical_record_number' di tabel 'patients' dan pastikan unik
            $table->string('medical_record_number')->unique()->change();
        });

        Schema::table('cppt', function (Blueprint $table) {
            // Tambahkan kolom 'medical_record_number' di tabel 'cppt' dan buat foreign key ke 'patients'
            $table->string('medical_record_number')->after('registration_id');
            $table->foreign('medical_record_number')
                ->references('medical_record_number')->on('patients')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cppt', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['medical_record_number']);
            $table->dropColumn('medical_record_number');
        });

        Schema::table('patients', function (Blueprint $table) {
            // Hapus kolom 'medical_record_number' dari tabel 'patients'
            $table->dropColumn('medical_record_number');
        });
    }
};
