<?php

namespace App\Imports;

use App\Models\WarehousePabrik;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehousePabrikImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): ?WarehousePabrik
    {
        try {
            // Cek satu-satu dan pastikan field sesuai ekspektasi
            $nama = isset($row['nama']) ? trim((string) $row['nama']) : null;
            $alamat = isset($row['alamat']) ? trim((string) $row['alamat']) : null;
            $telp = isset($row['telp']) ? (is_numeric($row['telp']) ? (string) $row['telp'] : trim((string) $row['telp'])) : null;
            $contactPerson = isset($row['contact_person']) ? trim((string) $row['contact_person']) : null;
            $contactPersonPhone = isset($row['contact_person_phone']) ? trim((string) $row['contact_person_phone']) : null;
            $aktif = isset($row['aktif']) ? filter_var($row['aktif'], FILTER_VALIDATE_BOOLEAN) : false;

            return new WarehousePabrik([
                'nama' => $nama,
                'alamat' => $alamat,
                'telp' => $telp,
                'contact_person' => $contactPerson,
                'contact_person_phone' => $contactPersonPhone,
                'aktif' => $aktif,
            ]);
        } catch (\Throwable $e) {
            Log::error('WarehousePabrikImport error: ' . $e->getMessage(), [
                'row' => $row,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telp' => ['nullable'],
            'contact_person' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string'],
            'aktif' => ['required', 'in:0,1'],
        ];
    }
}
