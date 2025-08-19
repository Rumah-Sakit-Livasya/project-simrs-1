<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WasteCategory;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        WasteCategory::create(['name' => 'Infeksius']);
        WasteCategory::create(['name' => 'Benda Tajam']);
        WasteCategory::create(['name' => 'Plabot']);
        WasteCategory::create(['name' => 'Domestik']);
    }
}
