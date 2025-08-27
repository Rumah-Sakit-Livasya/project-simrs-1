<?php

namespace Database\Seeders;

use App\Models\LinenCategory;
use Illuminate\Database\Seeder;

class LinenCategorySeeder extends Seeder
{
    public function run(): void
    {
        LinenCategory::create(['name' => 'Infeksius']);
        LinenCategory::create(['name' => 'Non Infeksius']);
    }
}
