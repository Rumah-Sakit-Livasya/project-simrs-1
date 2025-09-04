<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'super admin', 'guard_name' => 'web']);
        Role::create(['name' => 'manager', 'guard_name' => 'web']);
        Role::create(['name' => 'hr', 'guard_name' => 'web']);
        Role::create(['name' => 'employee', 'guard_name' => 'web']);
        Role::create(['name' => 'PJ', 'guard_name' => 'web']);

        $permissions = [
            ['name' => 'view dashboard', 'guard_name' => 'web', 'group' => 'Dashboard'],

            ['name' => 'view absensi', 'guard_name' => 'web', 'group' => 'Absensi'],
            ['name' => 'view pengajuan absensi', 'guard_name' => 'web', 'group' => 'Absensi'],
            ['name' => 'create pengajuan absensi', 'guard_name' => 'web', 'group' => 'Absensi'],
            ['name' => 'view pengajuan cuti', 'guard_name' => 'web', 'group' => 'Absensi'],
            ['name' => 'create pengajuan cuti', 'guard_name' => 'web', 'group' => 'Absensi'],
            ['name' => 'view laporan absensi', 'guard_name' => 'web', 'group' => 'Absensi'],

            ['name' => 'monitoring view absensi', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring edit absensi', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring view absensi outsource', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring create absensi outsource', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring view pengajuan', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring edit pengajuan absensi', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring delete pengajuan absensi', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring edit pengajuan cuti', 'guard_name' => 'web', 'group' => 'Monitoring'],
            ['name' => 'monitoring delete pengajuan cuti', 'guard_name' => 'web', 'group' => 'Monitoring'],

            ['name' => 'view pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'tambah pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'import pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'nonaktif pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'edit approval pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'edit pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'edit lokasi absen pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'view manajemen shift', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'edit manajemen shift', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'import/export manajemen shift', 'guard_name' => 'web', 'group' => 'Pegawai'],
            ['name' => 'view laporan pegawai', 'guard_name' => 'web', 'group' => 'Pegawai'],

            ['name' => 'view penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],
            ['name' => 'tambah form penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],
            ['name' => 'edit form penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],
            ['name' => 'tambah penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],
            ['name' => 'edit penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],
            ['name' => 'view laporan penilaian', 'guard_name' => 'web', 'group' => 'Penilaian'],

            ['name' => 'view payroll', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'view master data payroll', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'import/export gaji & tunjangan', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'import/export potongan', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'view run payroll', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'run payroll', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'view payroll history', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'cetak slip gaji', 'guard_name' => 'web', 'group' => 'Payroll'],
            ['name' => 'view slip gaji', 'guard_name' => 'web', 'group' => 'Payroll'],

            ['name' => 'view group kontak', 'guard_name' => 'web', 'group' => 'Whatsapp'],
            ['name' => 'view broadcast', 'guard_name' => 'web', 'group' => 'Whatsapp'],
            ['name' => 'send broadcast', 'guard_name' => 'web', 'group' => 'Whatsapp'],
            ['name' => 'view messages', 'guard_name' => 'web', 'group' => 'Whatsapp'],
            ['name' => 'send messages', 'guard_name' => 'web', 'group' => 'Whatsapp'],


            //Master Data
            ['name' => 'view master data', 'guard_name' => 'web', 'group' => 'Master Data'],

            ['name' => 'view perusahaan', 'guard_name' => 'web', 'group' => 'Perusahaan'],
            ['name' => 'tambah perusahaan', 'guard_name' => 'web', 'group' => 'Perusahaan'],
            ['name' => 'edit perusahaan', 'guard_name' => 'web', 'group' => 'Perusahaan'],
            ['name' => 'delete perusahaan', 'guard_name' => 'web', 'group' => 'Perusahaan'],
            ['name' => 'set lokasi perusahaan', 'guard_name' => 'web', 'group' => 'Perusahaan'],

            ['name' => 'view organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'tambah organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'edit organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'delete organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'view struktur organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'tambah struktur organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'edit struktur organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],
            ['name' => 'delete struktur organisasi', 'guard_name' => 'web', 'group' => 'Organisasi'],

            ['name' => 'view job level', 'guard_name' => 'web', 'group' => 'Job Level'],
            ['name' => 'tambah job level', 'guard_name' => 'web', 'group' => 'Job Level'],
            ['name' => 'edit job level', 'guard_name' => 'web', 'group' => 'Job Level'],
            ['name' => 'delete job level', 'guard_name' => 'web', 'group' => 'Job Level'],

            ['name' => 'view job position', 'guard_name' => 'web', 'group' => 'Job Position'],
            ['name' => 'tambah job position', 'guard_name' => 'web', 'group' => 'Job Position'],
            ['name' => 'edit job position', 'guard_name' => 'web', 'group' => 'Job Position'],
            ['name' => 'delete job position', 'guard_name' => 'web', 'group' => 'Job Position'],

            ['name' => 'view kode absensi', 'guard_name' => 'web', 'group' => 'Kode Absensi'],
            ['name' => 'tambah kode absensi', 'guard_name' => 'web', 'group' => 'Kode Absensi'],
            ['name' => 'edit kode absensi', 'guard_name' => 'web', 'group' => 'Kode Absensi'],
            ['name' => 'delete kode absensi', 'guard_name' => 'web', 'group' => 'Kode Absensi'],

            ['name' => 'view kode shift', 'guard_name' => 'web', 'group' => 'Master Shift'],
            ['name' => 'tambah kode shift', 'guard_name' => 'web', 'group' => 'Master Shift'],
            ['name' => 'edit kode shift', 'guard_name' => 'web', 'group' => 'Master Shift'],
            ['name' => 'delete kode shift', 'guard_name' => 'web', 'group' => 'Master Shift'],

            ['name' => 'view master bank', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'tambah master bank', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'edit master bank', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'delete master bank', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'view bank pegawai', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'tambah bank pegawai', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'edit bank pegawai', 'guard_name' => 'web', 'group' => 'Master Bank'],
            ['name' => 'delete bank pegawai', 'guard_name' => 'web', 'group' => 'Master Bank'],

            ['name' => 'view master tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'tambah master tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'edit master tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'delete master tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'view tipe tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'tambah tipe tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'edit tipe tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],
            ['name' => 'delete tipe tarif', 'guard_name' => 'web', 'group' => 'Master Tarif'],

            ['name' => 'view master menu', 'guard_name' => 'web', 'group' => 'Master Menu'],
            ['name' => 'create master menu', 'guard_name' => 'web', 'group' => 'Master Menu'],
            ['name' => 'edit master menu', 'guard_name' => 'web', 'group' => 'Master Menu'],
            ['name' => 'delete master menu', 'guard_name' => 'web', 'group' => 'Master Menu'],

            ['name' => 'view users', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'tambah users', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'edit users', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'delete users', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'view roles', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'tambah roles', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'edit roles', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'delete roles', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'view permissions', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'tambah permissions', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'edit permissions', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'delete permissions', 'guard_name' => 'web', 'group' => 'Users'],
            ['name' => 'edit user akses', 'guard_name' => 'web', 'group' => 'Users'],

        ];

        foreach ($permissions as $data) {
            Permission::create([
                'name' => $data['name'],
                'guard_name' => $data['guard_name'],
                'group' => $data['group'],
            ]);
        }
    }
}
