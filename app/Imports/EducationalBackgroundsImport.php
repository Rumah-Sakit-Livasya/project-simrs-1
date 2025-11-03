<?php

namespace App\Imports;

use App\Models\EducationalBackground;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EducationalBackgroundsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Map row to EducationalBackground model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan semua kolom sesuai dengan template export
        // Kolom: 'id_pegawai', 'nama_pegawai', 'pendidikan_terakhir', 'tahun_lulus', 'no_ijazah', 'kualifikasi_dasar', 'kompetensi_awal'

        // Cek apakah employee_id ada dan valid
        $employeeId = $row['id_pegawai'] ?? null;

        if (!$employeeId || !Employee::where('id', $employeeId)->exists()) {
            // Skip jika tidak ada atau tidak valid
            return null;
        }

        // Import atau update data berdasarkan employee_id dan pendidikan_terakhir
        return EducationalBackground::updateOrCreate(
            [
                'employee_id'     => $employeeId,
                'last_education'  => $row['pendidikan_terakhir'] ?? '',
            ],
            [
                'graduation_year'       => $row['tahun_lulus'] ?? null,
                'diploma_number'        => $row['no_ijazah'] ?? null,
                'basic_qualifications'  => $row['kualifikasi_dasar'] ?? null,
                'initial_competency'    => $row['kompetensi_awal'] ?? null,
            ]
        );
    }

    /**
     * Validation rules for imported rows.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.id_pegawai' => 'required|numeric|exists:employees,id',
            '*.pendidikan_terakhir' => 'required|string|max:100',
            '*.tahun_lulus' => 'nullable|numeric|digits:4',
            '*.no_ijazah' => 'nullable|string|max:150',
            '*.kualifikasi_dasar' => 'nullable|string',
            '*.kompetensi_awal' => 'nullable|string',
        ];
    }
}
