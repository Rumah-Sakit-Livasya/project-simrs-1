<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fizar = User::create([
            'employee_id' => 2,
            'name' => 'Fizar Rama Waluyo, S. Kom.',
            'email' => 'fizar@livasya.com',
        ]);

        $fizar->assignRole('admin');

        $dimas = User::create([
            'employee_id' => 1,
            'name' => 'Dimas Chandra',
            'email' => 'dimas@livasya.com',
        ]);

        $dimas->assignRole('admin');
    }
}
