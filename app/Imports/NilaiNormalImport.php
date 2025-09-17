<?php

namespace App\Imports;

use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NilaiNormalImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cari parameter_id berdasarkan kode_parameter dari excel
        $parameter = ParameterLaboratorium::where('kode', $row['kode_parameter'])->first();

        // Jika parameter tidak ditemukan, lewati baris ini
        if (!$parameter) {
            return null;
        }

        // Gabungkan kembali umur dari kolom tahun, bulan, hari
        $dari_umur = ($row['dari_tahun'] ?? 0) . '-' . ($row['dari_bulan'] ?? 0) . '-' . ($row['dari_hari'] ?? 0);
        $sampai_umur = ($row['sampai_tahun'] ?? 0) . '-' . ($row['sampai_bulan'] ?? 0) . '-' . ($row['sampai_hari'] ?? 0);

        // Gunakan updateOrCreate untuk menghindari duplikasi data berdasarkan kunci unik
        // Anda bisa sesuaikan field kunci uniknya. Di sini contohnya adalah kombinasi parameter, jk, dan umur.
        return NilaiNormalLaboratorium::updateOrCreate(
            [
                'parameter_laboratorium_id' => $parameter->id,
                'jenis_kelamin'             => $row['jenis_kelamin'],
                'dari_umur'                 => $dari_umur,
                'sampai_umur'               => $sampai_umur,
            ],
            [
                'tanggal'       => now(),
                'user_input'    => Auth::id() ?? 1, // Ganti dengan user id yang sedang login
                'min'           => $row['min'],
                'max'           => $row['max'],
                'nilai_normal'  => $row['nilai_normal_text'],
                'keterangan'    => $row['keterangan'],
                'min_kritis'    => $row['min_kritis'],
                'max_kritis'    => $row['max_kritis'],
            ]
        );
    }

    public function rules(): array
    {
        // Tambahkan aturan validasi untuk setiap kolom
        return [
            'kode_parameter' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,Semua',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
        ];
    }
}
