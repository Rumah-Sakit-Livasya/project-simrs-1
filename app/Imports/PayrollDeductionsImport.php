<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollDeductionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        // Cari karyawan berdasarkan nama lengkap
        $employee = Employee::where('fullname', $row['fullname'])->first();
        // Jika karyawan tidak ditemukan, abaikan baris ini
        if (!$employee) {
            return null;
        }

        $periode = convertPeriodePayroll($row['periode']);
        list($startMonth, $endMonth) = explode(' - ', $periode);
        $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth()->addDays(25);
        $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth()->subMonth()->addDays(25);

        $startFormatted = $startPeriod->translatedFormat('F Y');
        $endFormatted = $endPeriod->translatedFormat('F Y');

        $periode = $startFormatted . ' - ' . $endFormatted;
        $payroll = Payroll::where('employee_id', $employee->id)->where('periode', $periode)->first();

        $payroll->update([
            'potongan_keterlambatan' => $row['potongan_keterlambatan'] ?? 0,
            'potongan_izin' => $row['potongan_izin'] ?? 0,
            'potongan_sakit' => $row['potongan_sakit'] ?? 0,
            'simpanan_pokok' => $row['simpanan_pokok'] ?? 0,
            'potongan_koperasi' => $row['potongan_koperasi'] ?? 0,
            'potongan_absensi' => $row['potongan_absensi'] ?? 0,
            'potongan_bpjs_kesehatan' => $row['potongan_bpjs_kesehatan'] ?? 0,
            'potongan_bpjs_ketenagakerjaan' => $row['potongan_bpjs_ketenagakerjaan'] ?? 0,
            'potongan_pajak' => $row['potongan_pajak'] ?? 0
        ]);

        return null;
    }
}
