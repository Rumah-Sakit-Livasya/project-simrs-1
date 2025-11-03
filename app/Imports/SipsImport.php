<?php

namespace App\Imports;

use App\Models\Sip;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class SipsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row): ?Sip
    {
        $expiryRaw = $row['sip_expiry_date_wajib_format_yyyy_mm_dd'];

        // Handle if Excel serial number detected (numeric)
        if (is_numeric($expiryRaw)) {
            try {
                // Excel's epoch starts at 1900-01-01
                $expiryDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($expiryRaw))
                    ->format('Y-m-d');
            } catch (\Throwable $e) {
                // fallback, let it fail validation or show original value
                $expiryDate = $expiryRaw;
            }
        } else {
            // Try parse as date (could be string e.g. 2024-01-01)
            try {
                $expiryDate = Carbon::parse($expiryRaw)->format('Y-m-d');
            } catch (\Throwable $e) {
                $expiryDate = $expiryRaw;
            }
        }

        return Sip::updateOrCreate(
            [
                'employee_id' => $row['employee_id_jangan_diubah'],
            ],
            [
                'sip_number' => (string) $row['sip_number_wajib'],
                'sip_expiry_date' => $expiryDate,
            ]
        );
    }

    public function rules(): array
    {
        return [
            '*.employee_id_jangan_diubah' => 'required|numeric|exists:employees,id',
            '*.sip_number_wajib' => 'required',
            '*.sip_expiry_date_wajib_format_yyyy_mm_dd' => 'required',
        ];
    }
}
