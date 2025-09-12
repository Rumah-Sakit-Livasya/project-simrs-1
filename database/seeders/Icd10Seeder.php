<?php

namespace Database\Seeders;

use App\Imports\Icd10Import; // <-- Tambahkan ini
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Icd10Seeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('icd10_diagnostics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $filePath = database_path('seeders/data/icd10_diagnostics.xlsx');
        $this->command->info('Memulai seeding data ICD-10 menggunakan Import Class...');

        try {
            // Gunakan Excel::import dengan class yang sudah kita buat
            Excel::import(new Icd10Import, $filePath);
            $this->command->info('Seeding data ICD-10 berhasil.');
        } catch (\Exception $e) {
            $this->command->error('Terjadi kesalahan saat seeding: ' . $e->getMessage());
        }
    }
}
