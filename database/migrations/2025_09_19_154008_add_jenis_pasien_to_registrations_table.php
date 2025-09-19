<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Perintah untuk MENAMBAHKAN kolom baru ke tabel 'registrations'
        Schema::table('registrations', function (Blueprint $table) {
            // Kita akan menambahkan kolom 'jenis_pasien' setelah kolom 'status' agar rapi.
            // Anda bisa mengubah 'status' dengan nama kolom lain jika perlu.
            $table->string('jenis_pasien', 20)->nullable()->after('registration_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    // Perintah untuk MENGHAPUS kolom 'jenis_pasien' jika migrasi di-rollback
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('jenis_pasien');
        });
    }
};
