<?php

namespace Database\Seeders;

use App\Models\JobLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisasi = [
            ['name' => 'Director'],
            ['name' => 'Owner'],
            ['name' => 'Head'],
            ['name' => 'Supervisor'],
            ['name' => 'Coordinator'],
            ['name' => 'Staff'],
            ['name' => 'Non Staff'],
            ['name' => 'Dokter Full-Time'],
            ['name' => 'Dokter Part-Time'],
            ['name' => 'Komisaris'],
            ['name' => 'Sekretaris'],
            ['name' => 'Admin'],
            ['name' => 'Purchasing'],
        ];

        foreach ($organisasi as $data) {
            JobLevel::create($data);
        }
    }
}
