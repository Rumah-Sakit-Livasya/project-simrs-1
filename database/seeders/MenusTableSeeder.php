<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $absensi = Menu::create([
            'name' => 'Absensi',
            'url' => '#',
            'icon' => 'bx bxs-user-pin',
            'permission' => 'view absensi',
            'parent_id' => null
        ]);

        Menu::create([
            'name' => 'Absensi',
            'url' => route('attendances'),
            'icon' => '',
            'permission' => 'view absensi',
            'parent_id' => $absensi->id
        ]);

        Menu::create([
            'name' => 'Pengajuan Absen',
            'url' => '/employee/attendance-requests',
            'icon' => '',
            'permission' => 'view attendance requests',
            'parent_id' => $absensi->id
        ]);

        Menu::create([
            'name' => 'Pengajuan Cuti/Izin/Sakit',
            'url' => '/employee/day-off-requests',
            'icon' => '',
            'permission' => 'view day off requests',
            'parent_id' => $absensi->id
        ]);
    }
}
