<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'ASKEP',
            'CATATAN MEDIS',
            'HASIL LABORATORIUM',
            'HASIL RADIOLOGI',
            'IGD',
            'LAIN-LAIN',
            'PENUNJANG MEDIS',
            'POLIKLINIK',
            'SURAT RUJUKAN'
        ];
        foreach ($categories as $category) {
            DocumentCategory::firstOrCreate(['name' => $category]);
        }
    }
}
