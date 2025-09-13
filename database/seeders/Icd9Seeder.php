<?php

namespace Database\Seeders;

use App\Imports\Icd9Import; // <-- Tambahkan ini
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Icd9Seeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('icd9_procedures')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $filePath = database_path('seeders/data/icd9_procedures.xlsx');
        $this->command->info('Memulai seeding data ICD-9 menggunakan Import Class...');

        try {
            // Gunakan Excel::import dengan class yang sudah kita buat
            Excel::import(new Icd9Import, $filePath);
            $this->command->info('Seeding data ICD-9 berhasil.');
        } catch (\Exception $e) {
            $this->command->error('Terjadi kesalahan saat seeding: ' . $e->getMessage());
        }
    }
}
