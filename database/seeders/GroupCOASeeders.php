<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupCOASeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = [
            'Aset (Asset)',
            'Hutang (Liability)',
            'Modal (Equity)',
            'Pendapatan (Income)',
            'Beban (Expense)',
        ];

        foreach ($group as $name) {
            DB::table('group_chart_of_account')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
