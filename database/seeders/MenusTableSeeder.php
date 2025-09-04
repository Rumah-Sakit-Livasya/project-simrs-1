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
        $dashboard = Menu::create([
            'title' => 'Dashboard',
            'url' => '#',
            'icon' => 'bx bxs-dashboard',
            'permission' => 'view dashboard',
            'sort_order' => 1,
        ]);

        $absensi = Menu::create([
            'title' => 'Absensi',
            'url' => '#',
            'icon' => 'bx bxs-user-pin',
            'permission' => 'view absensi',
            'sort_order' => 2,
        ]);

        Menu::create([
            'title' => 'Absensi',
            'url' => '/attendances',
            'parent_id' => $absensi->id,
            'sort_order' => 1,
            'permission' => 'view absensi',
        ]);

        Menu::create([
            'title' => 'Pengajuan Absen',
            'url' => '/attendances/attendance-requests',
            'parent_id' => $absensi->id,
            'sort_order' => 2,
            'permission' => 'view pengajuan absensi',
        ]);

        Menu::create([
            'title' => 'Pengajuan Cuti/Izin/Sakit',
            'url' => '/attendances/day-off-requests',
            'parent_id' => $absensi->id,
            'sort_order' => 3,
            'permission' => 'view pengajuan cuti',
        ]);

        Menu::create([
            'title' => 'Laporan',
            'icon' => 'bx bxs-report',
            'url' => '/attendances/reports',
            'parent_id' => $absensi->id,
            'sort_order' => 4,
            'permission' => 'view pengajuan cuti',
        ]);

        //Monitoring
        $monitoring = Menu::create([
            'title' => 'Monitoring',
            'url' => '#',
            'icon' => 'bx bxs-pie-chart-alt-2',
            'permission' => 'monitoring view absensi',
            'sort_order' => 3,
        ]);

        Menu::create([
            'title' => 'Absensi Pegawai',
            'url' => '/monitoring/attendances',
            'parent_id' => $monitoring->id,
            'sort_order' => 1,
            'permission' => 'monitoring view absensi',
        ]);

        Menu::create([
            'title' => 'Absensi Outsource',
            'url' => '/monitoring/outsource/attendances/all',
            'parent_id' => $monitoring->id,
            'sort_order' => 2,
            'permission' => 'monitoring view absensi outsource',
        ]);

        Menu::create([
            'title' => 'Daftar Pengajuan',
            'url' => '/monitoring/all-requests',
            'parent_id' => $monitoring->id,
            'sort_order' => 3,
            'permission' => 'monitoring view pengajuan',
        ]);

        //Pegawai
        $pegawai = Menu::create([
            'title' => 'Pegawai',
            'url' => '#',
            'icon' => 'bx bxs-user-detail',
            'permission' => 'view pegawai',
            'sort_order' => 4,
        ]);

        Menu::create([
            'title' => 'Daftar Pegawai',
            'url' => '/employees',
            'parent_id' => $pegawai->id,
            'sort_order' => 1,
            'permission' => 'view pegawai',
        ]);

        Menu::create([
            'title' => 'Manajemen Shift',
            'url' => '/employees/management-shift',
            'parent_id' => $pegawai->id,
            'sort_order' => 2,
            'permission' => 'view manajemen shift',
        ]);

        Menu::create([
            'title' => 'Laporan',
            'icon' => 'bx bxs-report',
            'url' => '/employees/reporsts',
            'parent_id' => $pegawai->id,
            'sort_order' => 3,
            'permission' => 'view laporan pegawai',
        ]);

        $penilaian = Menu::create([
            'title' => 'Penilaian',
            'url' => '#',
            'icon' => 'bx bxs-bar-chart-alt-2',
            'permission' => 'view penilaian',
            'sort_order' => 5,
        ]);

        Menu::create([
            'title' => 'Daftar Form',
            'url' => '/penilaian/daftar-form',
            'parent_id' => $penilaian->id,
            'sort_order' => 1,
            'permission' => 'view penilaian',
        ]);

        Menu::create([
            'title' => 'Laporan',
            'icon' => 'bx bxs-report',
            'url' => '/penilaian/reports',
            'parent_id' => $penilaian->id,
            'sort_order' => 2,
            'permission' => 'view laporan penilaian',
        ]);

        $payroll = Menu::create([
            'title' => 'Payroll',
            'url' => '#',
            'icon' => 'bx bxs-bar-chart-alt-2',
            'permission' => 'view payroll',
            'sort_order' => 5,
        ]);

        $master_data_payroll = Menu::create([
            'title' => 'Master Data',
            'url' => '#',
            'parent_id' => $payroll->id,
            'sort_order' => 1,
            'permission' => 'view master data payroll',
        ]);

        Menu::create([
            'title' => 'Gaji & Tunjangan',
            'url' => '/payroll/allowance',
            'parent_id' => $master_data_payroll->id,
            'sort_order' => 1,
            'permission' => 'import/export gaji & tunjangan',
        ]);

        Menu::create([
            'title' => 'Potongan',
            'url' => '/payroll/deduction',
            'parent_id' => $master_data_payroll->id,
            'sort_order' => 2,
            'permission' => 'import/export potongan',
        ]);

        Menu::create([
            'title' => 'Run Payroll',
            'url' => '/payroll/run-payroll',
            'parent_id' => $payroll->id,
            'sort_order' => 2,
            'permission' => 'view run payroll',
        ]);

        Menu::create([
            'title' => 'Payroll History',
            'url' => '/payroll/payroll-history',
            'parent_id' => $payroll->id,
            'sort_order' => 3,
            'permission' => 'view payroll history',
        ]);

        Menu::create([
            'title' => 'Cetak Slip Gaji',
            'url' => '/payroll/payslip',
            'parent_id' => $payroll->id,
            'sort_order' => 4,
            'permission' => 'cetak slip gaji',
        ]);

        Menu::create([
            'title' => 'Slip Gaji',
            'url' => '/payroll/show',
            'parent_id' => $payroll->id,
            'sort_order' => 5,
            'permission' => 'view slip gaji',
        ]);

        $whatsapp = Menu::create([
            'title' => 'Whatsapp',
            'url' => '#',
            'icon' => 'bx bxl-whatsapp',
            'permission' => 'view messages',
            'sort_order' => 6,
        ]);

        Menu::create([
            'title' => 'Kirim Pesan',
            'url' => '/whatsapp',
            'parent_id' => $whatsapp->id,
            'sort_order' => 1,
            'permission' => 'view messages',
        ]);

        Menu::create([
            'title' => 'Group Kontak',
            'url' => '/whatsapp/group_kontak',
            'parent_id' => $whatsapp->id,
            'sort_order' => 2,
            'permission' => 'view group kontak',
        ]);

        Menu::create([
            'title' => 'Broadcast',
            'url' => '/whatsapp/broadcast',
            'parent_id' => $whatsapp->id,
            'sort_order' => 3,
            'permission' => 'view broadcast',
        ]);

        $master_data = Menu::create([
            'title' => 'Master Data',
            'url' => '#',
            'icon' => 'bx bx-cube',
            'permission' => 'view master data',
            'sort_order' => 6,
        ]);

        $perusahaan = Menu::create([
            'title' => 'Perusahaan',
            'url' => '#',
            'icon' => 'fas fa-building',
            'parent_id' => $master_data->id,
            'sort_order' => 1,
            'permission' => 'view master data payroll',
        ]);

        Menu::create([
            'title' => 'Daftar Perusahaan',
            'url' => '/master-data/companies',
            'parent_id' => $perusahaan->id,
            'sort_order' => 1,
            'permission' => 'view perusahaan',
        ]);

        Menu::create([
            'title' => 'Organisasi (Unit)',
            'url' => '/master-data/organizations',
            'parent_id' => $perusahaan->id,
            'sort_order' => 2,
            'permission' => 'view organisasi',
        ]);

        Menu::create([
            'title' => 'Struktur',
            'url' => '/master-data/structures',
            'parent_id' => $perusahaan->id,
            'sort_order' => 3,
            'permission' => 'view struktur organisasi',
        ]);

        Menu::create([
            'title' => 'Job Position',
            'url' => '/master-data/job-position',
            'parent_id' => $perusahaan->id,
            'sort_order' => 4,
            'permission' => 'view job position',
        ]);

        Menu::create([
            'title' => 'Job Level',
            'url' => '/master-data/job-level',
            'parent_id' => $perusahaan->id,
            'sort_order' => 5,
            'permission' => 'view job level',
        ]);

        $manajemen_waktu = Menu::create([
            'title' => 'Manajemen Waktu',
            'url' => '#',
            'icon' => 'fas fa-clock',
            'parent_id' => $master_data->id,
            'sort_order' => 2,
            'permission' => 'view kode absensi',
        ]);

        Menu::create([
            'title' => 'Kode Absensi',
            'url' => '/master-data/attendance-codes',
            'parent_id' => $perusahaan->id,
            'sort_order' => 1,
            'permission' => 'view kode absensi',
        ]);

        Menu::create([
            'title' => 'Kode Shift',
            'url' => '/master-data/shift-codes',
            'parent_id' => $perusahaan->id,
            'sort_order' => 1,
            'permission' => 'view kode shift',
        ]);

        $master_bank = Menu::create([
            'title' => 'Master Bank',
            'url' => '#',
            'icon' => 'fas fa-money-bill-alt',
            'parent_id' => $master_data->id,
            'sort_order' => 3,
            'permission' => 'view master bank',
        ]);

        Menu::create([
            'title' => 'Daftar Bank',
            'url' => '/master-data/banks',
            'parent_id' => $master_bank->id,
            'sort_order' => 1,
            'permission' => 'view master bank',
        ]);

        Menu::create([
            'title' => 'Bank Pegawai',
            'url' => '/master-data/bank-employees',
            'parent_id' => $master_bank->id,
            'sort_order' => 2,
            'permission' => 'view bank pegawai',
        ]);

        $master_tarif = Menu::create([
            'title' => 'Master Tarif',
            'url' => '#',
            'icon' => 'fas fa-credit-card',
            'parent_id' => $master_data->id,
            'sort_order' => 4,
            'permission' => 'view master tarif',
        ]);

        Menu::create([
            'title' => 'Tipe Tarif',
            'url' => '/master-data/tarifs',
            'parent_id' => $master_tarif->id,
            'sort_order' => 1,
            'permission' => 'view master tarif',
        ]);

        Menu::create([
            'title' => 'Tipe Tarif',
            'url' => '/master-data/tarif-types',
            'parent_id' => $master_tarif->id,
            'sort_order' => 2,
            'permission' => 'view tipe tarif',
        ]);

        $master_menu = Menu::create([
            'title' => 'Master Menu',
            'url' => '#',
            'icon' => 'fas fa-list',
            'parent_id' => $master_data->id,
            'sort_order' => 5,
            'permission' => 'view master tarif',
        ]);

        Menu::create([
            'title' => 'Daftar Menu',
            'url' => '/master-data/daftar-menu',
            'parent_id' => $master_menu->id,
            'sort_order' => 1,
            'permission' => 'view master menu',
        ]);

        $users = Menu::create([
            'title' => 'Users',
            'url' => '#',
            'icon' => 'fas fa-users',
            'parent_id' => $master_data->id,
            'sort_order' => 6,
            'permission' => 'view users',
        ]);

        Menu::create([
            'title' => 'Daftar User',
            'url' => '/master-data/users',
            'parent_id' => $users->id,
            'sort_order' => 1,
            'permission' => 'view users',
        ]);

        Menu::create([
            'title' => 'Roles',
            'url' => '/master-data/roles-permissions/roles',
            'parent_id' => $users->id,
            'sort_order' => 2,
            'permission' => 'view roles',
        ]);

        Menu::create([
            'title' => 'Permissions',
            'url' => '/master-data/roles-permissions/permissions',
            'parent_id' => $users->id,
            'sort_order' => 3,
            'permission' => 'view permissions',
        ]);
    }
}
