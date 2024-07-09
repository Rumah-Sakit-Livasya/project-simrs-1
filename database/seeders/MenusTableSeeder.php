<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission as ModelsPermission;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $absensi = Menu::create([
            'title' => 'Absensi',
            'url' => '#',
            'icon' => 'bx bxs-user-pin',
            'permission' => 'view absensi',
            'sort_order' => 1,
        ]);

        Menu::create([
            'title' => 'Absensi',
            'url' => '/employee/attendances',
            'parent_id' => 1,
            'sort_order' => 1,
            'permission' => 'view absensi',
        ]);

        Menu::create([
            'title' => 'Pengajuan Absen',
            'url' => '/employee/attendance-requests',
            'parent_id' => 1,
            'sort_order' => 2,
            'permission' => 'view attendance requests',
        ]);

        Menu::create([
            'title' => 'Pengajuan Cuti/Izin/Sakit',
            'url' => '/employee/day-off-requests',
            'parent_id' => 1,
            'sort_order' => 3,
            'permission' => 'view day off requests',
        ]);

        ModelsPermission::create(['name' => 'view kpi']);
        ModelsPermission::create(['name' => 'view daily form']);
        ModelsPermission::create(['name' => 'view monthly form']);

        $kpi = Menu::create([
            'title' => 'KPI',
            'url' => '#',
            'icon' => 'bx bxs-bar-chart-alt-2',
            'sort_order' => 2,
            'permission' => 'view kpi'
        ]);

        Menu::create([
            'title' => 'Daftar Form Harian',
            'url' => '/reports/attendance',
            'parent_id' => $kpi->id,
            'sort_order' => 1,
            'permission' => 'view daily form'
        ]);

        Menu::create([
            'title' => 'Daftar Form Bulanan',
            'url' => '/kpi/get/group-penilaian',
            'parent_id' => $kpi->id,
            'sort_order' => 2,
            'permission' => 'view monthly form'
        ]);

        Menu::create([
            'title' => 'Rekap Penilaian',
            'url' => '#',
            'parent_id' => $kpi->id,
            'sort_order' => 3,
            'permission' => 'view rekap penilaian'
        ]);

        Menu::create([
            'title' => 'Harian',
            'url' => '/kpi/get/group-penilaian',
            'parent_id' => $kpi->id,
            'sort_order' => 4,
            'permission' => 'view daily rekap'
        ]);

        Menu::create([
            'title' => 'Bulanan',
            'url' => '/kpi/get/group-penilaian',
            'parent_id' => $kpi->id,
            'sort_order' => 5,
            'permission' => 'view monthly rekap'
        ]);
    }
}
