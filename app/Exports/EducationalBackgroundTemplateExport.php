<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EducationalBackgroundTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Ambil semua pegawai aktif beserta relasi riwayat hasOne pendidikannya
        $employees = Employee::where('is_active', 1)
            ->with('educationalBackground') // Eager load relasi
            ->orderBy('fullname', 'asc')
            ->get();

        $rows = [];
        foreach ($employees as $employee) {
            $background = $employee->educationalBackground;

            if ($background) {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    $background->last_education,
                    $background->graduation_year,
                    $background->diploma_number,
                    $background->basic_qualifications,
                    $background->initial_competency,
                ];
            } else {
                $rows[] = [
                    $employee->id,
                    $employee->fullname,
                    '', // last_education (kosong untuk diisi)
                    '', // graduation_year (kosong untuk diisi)
                    '', // diploma_number (kosong untuk diisi)
                    '', // basic_qualifications (kosong untuk diisi)
                    '', // initial_competency (kosong untuk diisi)
                ];
            }
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Pegawai',
            'Nama Pegawai',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'No Ijazah',
            'Kualifikasi Dasar',
            'Kompetensi Awal',
        ];
    }
}
