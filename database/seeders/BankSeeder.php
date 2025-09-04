<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['name' => 'BJB'],
            ['name' => 'BRI'],
            ['name' => 'BCA'],
            ['name' => 'Mandiri'],
            ['name' => 'BSI'],
            ['name' => 'Bank Jago'],
            ['name' => 'Bank Majalengka']
        ];

        foreach ($banks as $data) {
            Bank::create($data);
        }
    }
}
