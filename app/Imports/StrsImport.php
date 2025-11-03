<?php

namespace App\Imports;

use App\Models\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\Rule;

class StrsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cek input is_lifetime
        $isLifetime = isset($row['is_lifetime_isi_ya_jika_seumur_hidup_selain_itu_tidak']) &&
            strtolower($row['is_lifetime_isi_ya_jika_seumur_hidup_selain_itu_tidak']) === 'ya';

        return Str::updateOrCreate(
            [
                // Gunakan kunci yang lebih bersih dan unik untuk updateOrCreate
                'employee_id' => $row['employee_id_jangan_diubah'],
                'str_number'  => $row['str_number_wajib'],
            ],
            [
                'is_lifetime' => $isLifetime,
                'str_expiry_date' => $isLifetime ? null : $row['str_expiry_date_wajib_jika_tidak_seumur_hidup_format_yyyy_mm_dd'],
            ]
        );
    }

    /**
     * Aturan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Kunci array (header excel) harus sama persis
            'employee_id_jangan_diubah' => ['required', 'numeric', 'exists:employees,id'],
            'str_number_wajib' => ['required'],
            'is_lifetime_isi_ya_jika_seumur_hidup_selain_itu_tidak' => ['nullable', 'string'],

            // Validasi kondisional untuk tanggal kadaluarsa
            'str_expiry_date_wajib_jika_tidak_seumur_hidup_format_yyyy_mm_dd' => [
                'nullable',
                // Gunakan Closure untuk validasi kondisional
                function ($attribute, $value, $fail) {
                    // $this->data adalah array yang berisi baris saat ini yang sedang divalidasi
                    $currentRow = $this->data[0] ?? [];

                    // Ambil nilai 'is_lifetime' dari baris saat ini
                    $isLifetimeValue = $currentRow['is_lifetime_isi_ya_jika_seumur_hidup_selain_itu_tidak'] ?? null;

                    // Cek jika 'is_lifetime' bukan 'ya' DAN 'expiry_date' kosong, maka gagal validasi.
                    if (strtolower($isLifetimeValue) !== 'ya' && empty($value)) {
                        $fail('Tanggal kadaluarsa wajib diisi jika STR tidak berlaku seumur hidup.');
                    }

                    // Cek format tanggal jika ada isinya
                    // if (!empty($value) && !\DateTime::createFromFormat('Y-m-d', $value)) {
                    //     $fail('Format tanggal kadaluarsa harus YYYY-MM-DD.');
                    // }
                },
            ],
        ];
    }

    /**
     * Variabel untuk menyimpan baris data saat ini untuk digunakan di dalam rules().
     */
    private $data;

    /**
     * Menyiapkan data untuk validasi.
     *
     * @param array $data
     */
    public function prepareForValidation($data, $index)
    {
        // Simpan baris saat ini ke properti $data
        $this->data = [$data];
        return $data;
    }
}
