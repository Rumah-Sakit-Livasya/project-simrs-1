<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cari karyawan berdasarkan nama lengkap
        $employee = Employee::where('fullname', $row['fullname'])->first();

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
        $employee->salary()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'basic_salary' => $row['basic_salary'] ?? 0,
                'tunjangan_jabatan' => $row['tunjangan_jabatan'] ?? 0,
                'tunjangan_profesi' => $row['tunjangan_profesi'] ?? 0,
                'tunjangan_makan_dan_transport' => $row['tunjangan_makan_transport'] ?? 0,
                'tunjangan_masa_kerja' => $row['tunjangan_masa_kerja'] ?? 0,
                'guarantee_fee' => $row['guarantee_fee'] ?? 0,
                'uang_duduk' => $row['uang_duduk'] ?? 0,
                'tax_allowance' => $row['tax_allowance'] ?? 0
            ]
        );

        return null;
    }
}
