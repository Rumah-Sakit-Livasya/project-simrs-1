<?php

namespace App\Http\Controllers\API;

use App\Exports\PayrollDeductionsExport;
use App\Http\Controllers\Controller;
use App\Imports\PayrollDeductionsImport;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PayrollApiController extends Controller
{
    public function get($id)
    {
        try {
            // Mengambil data payroll berdasarkan ID
            $payroll = Payroll::findOrFail($id);

            // Mengembalikan data dalam format JSON
            return response()->json($payroll, 200);
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kembalikan respons dengan status error
            return response()->json(['message' => 'Data payroll tidak ditemukan.'], 404);
        }
    }

    public function getDeduction($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);

            $deductions = [
                'potongan_keterlambatan' => $payroll->potongan_keterlambatan,
                'potongan_izin' => $payroll->potongan_izin,
                'potongan_sakit' => $payroll->potongan_sakit,
                'simpanan_pokok' => $payroll->simpanan_pokok,
                'potongan_koperasi' => $payroll->potongan_koperasi,
                'potongan_absensi' => $payroll->potongan_absensi,
                'potongan_bpjs_kesehatan' => $payroll->potongan_bpjs_kesehatan,
                'potongan_bpjs_ketenagakerjaan' => $payroll->potongan_bpjs_ketenagakerjaan,
                'potongan_pajak' => $payroll->potongan_pajak,
                'total_deduction' => $payroll->total_deduction,
            ];

            return response()->json($deductions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getAllowance($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);

            $allowances = [
                'tunjangan_jabatan' => $payroll->tunjangan_jabatan,
                'tunjangan_profesi' => $payroll->tunjangan_profesi,
                'tunjangan_makan_dan_transport' => $payroll->tunjangan_makan_dan_transport,
                'tunjangan_masa_kerja' => $payroll->tunjangan_masa_kerja,
                'guarantee_fee' => $payroll->guarantee_fee,
                'uang_duduk' => $payroll->uang_duduk,
                'tax_allowance' => $payroll->tax_allowance,
                'total_allowance' => $payroll->total_allowance,
            ];

            return response()->json($allowances);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getTotalPayroll($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);

            $total = [
                'basic_salary' => $payroll->basic_salary,
                'total_allowance' => $payroll->total_allowance,
                'total_deduction' => $payroll->total_deduction,
                'take_home_pay' => $payroll->take_home_pay,
            ];

            return response()->json($total);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Mengambil data payroll berdasarkan ID
            $payroll = Payroll::where('periode', $request->periode)->get();

            // Mengembalikan data dalam format JSON
            return response()->json($payroll, 200);
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kembalikan respons dengan status error
            return response()->json(['message' => 'Data payroll tidak ditemukan.'], 404);
        }
    }

    public function store(Request $request)
    {

        try {
            if (!empty($request['employee_id'])) {
                $employees = Employee::whereIn('id', $request['employee_id'])->get();
            } else {
                $employees = Employee::all();
            }

            foreach ($employees as $employee) {
                // Mendapatkan tanggal awal dan akhir dari periode yang diminta
                $periode = convertPeriodePayroll($request->periode);
                list($startMonth, $endMonth) = explode(' - ', $periode);
                $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth()->addDays(25);
                $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth()->subMonth()->addDays(25);

                $startFormatted = $startPeriod->translatedFormat('F Y');
                $endFormatted = $endPeriod->translatedFormat('F Y');

                $periode = $startFormatted . ' - ' . $endFormatted;

                // Mengkonversi detik ke menit dan membulatkannya ke bawah
                $totalLateInMinutes = floor(Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->sum('late_clock_in'));
                $userId = $request->user_id;

                // return $totalLateInMinutes;
                // Allowance
                $basicSalary = $employee->salary->basic_salary ?? 0;
                $tunjanganJabatan = $employee->salary->tunjangan_jabatan ?? 0;
                $tunjanganProfesi = $employee->salary->tunjangan_profesi ?? 0;
                $tunjanganMakanDanTransport = $employee->salary->tunjangan_makan_dan_transport ?? 0;
                $tunjanganMasaKerja = $employee->salary->tunjangan_masa_kerja ?? 0;
                $guaranteeFee = $employee->salary->guarantee_fee ?? 0;
                $uangDuduk = $employee->salary->uang_duduk ?? 0;
                $taxAllowance = $employee->salary->tax_allowance ?? 0;

                // Deduction
                $potonganKeterlambatanValue = 0;
                $simpananPokok = $employee->deduction->simpanan_pokok ?? 0;
                $potonganIzinValue = 0;
                $potonganSakitValue = 0;
                $potonganAbsensiValue = 0;
                $potonganKoperasiValue = $employee->deduction->potongan_koperasi ?? 0;
                $potonganBPJSKesehatanValue = $employee->deduction->potongan_bpjs_kesehatan ?? 0;
                $potonganBPJSKetenagakerjaanValue = $employee->deduction->potongan_bpjs_ketenagakerjaan ?? 0;
                $potonganPajakValue = $employee->deduction->potongan_pajak ?? 0;

                // Logika potongan gaji berdasarkan total keterlambatan masuk
                if ($totalLateInMinutes > 480) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 9;
                } elseif ($totalLateInMinutes > 420) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 8;
                } elseif ($totalLateInMinutes > 360) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 7;
                } elseif ($totalLateInMinutes > 300) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 6;
                } elseif ($totalLateInMinutes > 240) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 5;
                } elseif ($totalLateInMinutes > 180) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 4;
                } elseif ($totalLateInMinutes > 120) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 3;
                } elseif ($totalLateInMinutes > 60) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 2;
                } elseif ($totalLateInMinutes > 30) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 1;
                }
                $potonganKeterlambatanValue = intval($potonganKeterlambatanValue);

                // Query untuk mencari data absensi sesuai dengan periode yang diminta
                $endPeriodHariKerja = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth()->subMonth()->addDays(24);
                $absensi = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriodHariKerja])
                    ->whereNull('attendance_code_id')
                    ->whereNull('day_off_request_id')
                    ->whereNull('clock_out')
                    ->whereNull('clock_in')
                    ->whereNull('is_day_off')
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan absensi sebesar 99000

                // Query untuk mencari data absensi sesuai dengan periode yang diminta
                $hariKerja = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->whereNull('attendance_code_id')
                    ->whereNull('day_off_request_id')
                    ->whereNotNull('clock_in')
                    ->whereNull('is_day_off')
                    ->count();


                // Cek apakah terdapat catatan kehadiran yang memenuhi syarat potongan izin
                $izin = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->where('attendance_code_id', 1)
                    ->where('is_day_off', 1)
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan izin sebesar 99000
                // if ($izin) {
                //     $potonganIzinValue = ($employee->deduction->potongan_izin ?? 0) * $izin;
                // }

                // Cek apakah terdapat catatan kehadiran yang memenuhi syarat potongan izin
                $sakit = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->where('attendance_code_id', 2)
                    ->where('is_day_off', 1)
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan sakit sebesar 99000
                if ($sakit) {
                    $potonganSakitValue = ($employee->deduction->potongan_sakit ?? 0) * $sakit;
                }

                $totalAllowance = $tunjanganJabatan + $tunjanganProfesi + $tunjanganMakanDanTransport + $tunjanganMasaKerja + $guaranteeFee + $uangDuduk + $taxAllowance;
                if ($absensi) {
                    $potonganAbsensiValue = $totalAllowance ?? 0;
                    $potonganAbsensiValue = $potonganAbsensiValue + $basicSalary;
                    $potonganAbsensiValue = ($potonganAbsensiValue * $absensi) / 25;
                }
                if ($izin) {
                    $potonganIzinValue = $totalAllowance ?? 0;
                    $potonganIzinValue = $potonganIzinValue + $basicSalary;

                    $potonganIzinValue = (($totalAllowance ?? 0) * $izin)  / 25;
                }
                $totalDeduction = $potonganKeterlambatanValue + $potonganIzinValue + $potonganSakitValue + $simpananPokok + $potonganKoperasiValue + $potonganAbsensiValue + $potonganBPJSKesehatanValue + $potonganBPJSKetenagakerjaanValue + $potonganPajakValue;
                $takeHomePay = $basicSalary + $totalAllowance - $totalDeduction;


                // Menyimpan potongan gaji ke dalam array
                $payroll[] = [
                    'user_id' => $userId,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->fullname,
                    'basic_salary' => $basicSalary,
                    'tunjangan_jabatan' => $tunjanganJabatan,
                    'tunjangan_profesi' => $tunjanganProfesi,
                    'tunjangan_makan_dan_transport' => $tunjanganMakanDanTransport,
                    'tunjangan_masa_kerja' => $tunjanganMasaKerja,
                    'guarantee_fee' => $guaranteeFee,
                    'uang_duduk' => $uangDuduk,
                    'tax_allowance' => $taxAllowance,
                    'total_allowance' => $totalAllowance,
                    'potongan_keterlambatan' => $potonganKeterlambatanValue,
                    'potongan_izin' => $potonganIzinValue,
                    'potongan_sakit' => $potonganSakitValue,
                    'simpanan_pokok' => $simpananPokok,
                    'potongan_koperasi' => $potonganKoperasiValue,
                    'potongan_absensi' => $potonganAbsensiValue,
                    'potongan_bpjs_kesehatan' => $potonganBPJSKesehatanValue,
                    'potongan_bpjs_ketenagakerjaan' => $potonganBPJSKetenagakerjaanValue,
                    'potongan_pajak' => $potonganPajakValue,
                    'total_deduction' => $totalDeduction,
                    'take_home_pay' => $takeHomePay,
                    'periode' => $periode,
                    'hari_kerja' => $hariKerja,
                    'is_review' => 0,
                ];
            }

            foreach ($payroll as $data) {
                // Cek apakah payroll untuk karyawan tersebut sudah ada dalam database
                $existingPayroll = Payroll::where('employee_id', $data['employee_id'])
                    ->where('periode', $periode)
                    ->first();

                if ($existingPayroll) {
                    // Jika sudah ada, update data payroll yang ada dengan data baru
                    $existingPayroll->update($data);
                } else {
                    // Jika belum ada, buat data payroll baru
                    Payroll::create($data);
                }
            }
            return response()->json(['message' => 'Payroll berhasil di tambah!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'potongan_absensi' => 'required',
            'potongan_keterlambatan' => 'required',
            'potongan_izin' => 'required',
            'simpanan_pokok' => 'required',
            'potongan_koperasi' => 'required',
            'potongan_bpjs_kesehatan' => 'required',
            'potongan_bpjs_ketenagakerjaan' => 'required',
            'tunjangan_masa_kerja' => 'required',
            'tunjangan_makan_dan_transport' => 'required',
            'tunjangan_jabatan' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();

            return response()->json([
                'message' => implode("\n", $errorMessages)
            ], 422);
        }

        try {
            // Find payroll by ID
            $payroll = Payroll::findOrFail($id);

            // Update payroll details
            $payroll->update($request->all());

            // Return success response
            return response()->json(['message' => 'Detail payroll berhasil diperbarui!']);
        } catch (\Exception $e) {
            // Return error response if payroll not found
            return response()->json(['message' => 'Data payroll tidak ditemukan.'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan data payroll berdasarkan ID
            $payroll = Payroll::findOrFail($id);
            // Hapus data payroll
            $payroll->delete();
            // Kirim respons sukses
            return response()->json(['message' => 'Data payroll berhasil dihapus.']);
        } catch (\Exception $e) {
            // Tangani kesalahan jika data tidak ditemukan atau terjadi kesalahan lainnya
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function runPayroll(Request $request)
    {
        // Mengubah status is_review dari semua data payroll menjadi 1
        try {
            Payroll::query()->update(['is_review' => 1]);
            return response()->json(['message' => 'Payroll successfully marked as reviewed'], 200);
        } catch (\Exception $e) {
            // Menangani kesalahan jika terjadi
            return response()->json(['error' => 'Failed to mark payroll as reviewed', 'details' => $e->getMessage()], 500);
        }
    }

    public function exportPayrollDeductions(Request $request)
    {
        // Nama file Excel
        $periode = $request->periode;
        $filename = 'payroll_deductions_' . $periode . '.xlsx';

        // Export data karyawan dan data nama shift ke dalam file Excel
        return Excel::download(new PayrollDeductionsExport($periode), $filename);
    }

    public function importPayrollDeductions(Request $request)
    {
        try {
            $file = $request->file('potongan');
            // Lakukan impor menggunakan SalaryImport
            \Maatwebsite\Excel\Facades\Excel::import(new PayrollDeductionsImport, $file);

            return response()->json(['message' => 'Data Gaji berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
