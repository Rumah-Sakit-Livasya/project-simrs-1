<?php

namespace App\Imports;

use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NilaiNormalImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Cari parameter_id berdasarkan kode_parameter dari excel
        $parameter = ParameterLaboratorium::where('kode', $row['kode_parameter'])->first();

        // Jika parameter tidak ditemukan, lewati baris ini
        if (!$parameter) {
            Log::warning('[NILAI_NORMAL_IMPORT] PARAMETER TIDAK DITEMUKAN untuk kode: ' . ($row['kode_parameter'] ?? 'KOSONG'));
            return null;
        }

        // Gabungkan kembali umur dari kolom tahun, bulan, hari
        $dari_umur = ($row['dari_tahun'] ?? 0) . '-' . ($row['dari_bulan'] ?? 0) . '-' . ($row['dari_hari'] ?? 0);
        $sampai_umur = ($row['sampai_tahun'] ?? 0) . '-' . ($row['sampai_bulan'] ?? 0) . '-' . ($row['sampai_hari'] ?? 0);

        // Siapkan data untuk keamanan
        $attributes = [
            'parameter_laboratorium_id' => $parameter->id,
            'jenis_kelamin'             => $row['jenis_kelamin'] ?? null,
            'dari_umur'                 => $dari_umur,
            'sampai_umur'               => $sampai_umur,
        ];

        $values = [
            'tanggal'       => now(),
            'user_input'    => Auth::id() ?? 1,
            'min'           => $row['min'] ?? null,
            'max'           => $row['max'] ?? null,
            'nilai_normal'  => $row['nilai_normal_text'] ?? null,
            'keterangan'    => $row['keterangan'] ?? null,
            'min_kritis'    => $row['min_kritis'] ?? null,
            'max_kritis'    => $row['max_kritis'] ?? null,
        ];

        // Log data sebelum mencoba menyimpan untuk debugging
        Log::info('[NILAI_NORMAL_IMPORT] Data untuk updateOrCreate: ', ['attributes' => $attributes, 'values' => $values]);

        // Gunakan updateOrCreate dengan data yang sudah disiapkan
        return NilaiNormalLaboratorium::updateOrCreate($attributes, $values);
    }

    public function rules(): array
    {
        return [
            'kode_parameter' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Laki - Laki,Perempuan,Semua',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
        ];
    }
}
