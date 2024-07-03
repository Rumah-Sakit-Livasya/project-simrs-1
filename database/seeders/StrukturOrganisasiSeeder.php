<?php

namespace Database\Seeders;

use App\Models\Structure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrukturOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $struktur = [
            [
                'child_organization' => 1,
                'parent_organization' => 3
            ],
            [
                'child_organization' => 7,
                'parent_organization' => 1
            ],
            [
                'child_organization' => 17,
                'parent_organization' => 7
            ],
            [
                'child_organization' => 18,
                'parent_organization' => 7
            ],
            [
                'child_organization' => 20,
                'parent_organization' => 7
            ],
            [
                'child_organization' => 8,
                'parent_organization' => 1
            ],
            [
                'child_organization' => 4,
                'parent_organization' => 8
            ],
            [
                'child_organization' => 21,
                'parent_organization' => 8
            ],
            [
                'child_organization' => 12,
                'parent_organization' => 8
            ],
            [
                'child_organization' => 16,
                'parent_organization' => 8
            ],
            [
                'child_organization' => 31,
                'parent_organization' => 8
            ],
            [
                'child_organization' => 9,
                'parent_organization' => 1
            ],
            [
                'child_organization' => 30,
                'parent_organization' => 9
            ],
            [
                'child_organization' => 32,
                'parent_organization' => 9
            ],
            [
                'child_organization' => 2,
                'parent_organization' => 3
            ],
            [
                'child_organization' => 5,
                'parent_organization' => 2
            ],
            [
                'child_organization' => 13,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 14,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 22,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 23,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 25,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 26,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 27,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 28,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 33,
                'parent_organization' => 5
            ],
            [
                'child_organization' => 6,
                'parent_organization' => 2
            ],
            [
                'child_organization' => 10,
                'parent_organization' => 6
            ],
            [
                'child_organization' => 11,
                'parent_organization' => 6
            ],
            [
                'child_organization' => 19,
                'parent_organization' => 6
            ],
            [
                'child_organization' => 24,
                'parent_organization' => 6
            ],
            [
                'child_organization' => 29,
                'parent_organization' => 6
            ],
        ];

        foreach ($struktur as $data) {
            Structure::create($data);
        }
    }
}
