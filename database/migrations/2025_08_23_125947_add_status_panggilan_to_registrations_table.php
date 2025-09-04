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
        Schema::table('registrations', function (Blueprint $table) {
            // Menambahkan kolom untuk status panggilan
            // ENUM membatasi nilai hanya pada yang kita tentukan, ini lebih baik untuk integritas data.
            $table->enum('status_panggilan', ['waiting', 'calling', 'finished', 'skipped'])
                ->default('waiting')
                ->after('registration_type'); // Atur posisi kolom jika perlu

            // Menambahkan kolom untuk mencatat kapan pasien dipanggil
            $table->timestamp('waktu_panggil')->nullable()->after('status_panggilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Ini akan menghapus kolom jika Anda melakukan rollback migrasi
            $table->dropColumn('status_panggilan');
            $table->dropColumn('waktu_panggil');
        });
    }
};
