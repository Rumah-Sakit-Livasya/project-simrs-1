<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeductionImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        dd($row);
        // Cari karyawan berdasarkan nama lengkap
        $employee = Employee::where('email', $row['fullname'])->first();

        // Jika karyawan tidak ditemukan, abaikan baris ini
        if (!$employee) {
            return null;
        }

        // Cari organisasi berdasarkan nama organisasi
        $organization = Organization::where('name', $row['organization_name'])->first();

        // Jika organisasi tidak ditemukan, abaikan baris ini
        if (!$organization) {
            return null;
        }


        // Set nilai Basic Salary, Tunjangan Jabatan, Tunjangan Profesi, Tunjangan Masa Kerja,
        // Guarantee Fee, Uang Duduk, dan Tax Allowance sesuai dengan nilai di file Excel
        $employee->deduction()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'potongan_keterlambatan' => $row['potongan_keterlambatan'] ?? 0,
                'potongan_izin' => $row['potongan_izin'] ?? 0,
                'potongan_sakit' => $row['potongan_sakit'] ?? 0,
                'simpanan_pokok' => $row['simpanan_pokok'] ?? 0,
                'potongan_koperasi' => $row['potongan_koperasi'] ?? 0,
                'potongan_absensi' => $row['potongan_absensi'] ?? 0,
                'potongan_bpjs_kesehatan' => $row['potongan_bpjs_kesehatan'],
                'potongan_bpjs_ketenagakerjaan' => $row['potongan_bpjs_ketenagakerjaan'],
                'potongan_pajak' => $row['potongan_pajak']
            ]
        );

        return null;
    }
}
