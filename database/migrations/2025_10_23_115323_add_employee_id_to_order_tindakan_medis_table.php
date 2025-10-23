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
        Schema::table('order_tindakan_medis', function (Blueprint $table) {
            // Menambahkan kolom employee_id sebagai foreign key yang merujuk ke tabel 'employees'
            // Mengizinkan nilai NULL jika ada data lama yang tidak memiliki employee_id
            // Menempatkan kolom ini setelah 'doctor_id' untuk kerapian
            $table->foreignId('employee_id')
                    ->nullable() // Tambahkan nullable() jika ada kemungkinan data lama tidak memiliki employee_id
                    ->constrained('employees') // Sesuaikan 'employees' jika nama tabel Anda berbeda
                    ->after('doctor_id'); // Posisikan kolom setelah doctor_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_tindakan_medis', function (Blueprint $table) {
            // Logika untuk membatalkan migrasi (menghapus kolom dan foreign key)
            // Laravel 8+ akan otomatis menangani penghapusan foreign key jika menggunakan constrained()
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }
};
