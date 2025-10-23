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
            // Menghapus kolom doctor_id dan foreign key-nya
            if (Schema::hasColumn('order_tindakan_medis', 'doctor_id')) {
                $table->dropForeign(['doctor_id']);
                $table->dropColumn('doctor_id');
            }

            // Menambahkan kolom employee_id sebagai foreign key yang merujuk ke tabel 'employees'
            // Mengizinkan nilai NULL jika ada data lama yang tidak memiliki employee_id
            $table->foreignId('employee_id')
                    ->nullable() // Tambahkan nullable() jika ada kemungkinan data lama tidak memiliki employee_id
                    ->after('user_id')
                    ->constrained('employees'); // Sesuaikan 'employees' jika nama tabel Anda berbeda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_tindakan_medis', function (Blueprint $table) {
            // Menghapus kolom employee_id dan foreign key-nya
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');

            // Menambahkan kembali kolom doctor_id sebagai foreign key
            $table->foreignId('doctor_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('doctors');
        });
    }
};
