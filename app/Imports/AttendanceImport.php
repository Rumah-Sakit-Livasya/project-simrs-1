<?php

namespace App\Imports;

use App\Models\LaporanInternal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Ambil tanggal-tanggal dari baris pertama, mulai dari kolom ketiga
        $dates = $rows->first()->slice(3);

        // Hapus judul kolom dari data
        $rows = $rows->slice(1);

        foreach ($rows as $row) {
            $employeeEmail = $row[1];
            $employeeName = $row[2];
            $employeeId = Employee::where('email', $employeeEmail)->value('id');
            $employeeApproval = Employee::where('email', $employeeEmail)->first(['id', 'approval_line', 'approval_line_parent']);
            if ($employeeId !== null) {
                foreach ($row->slice(3) as $dateIndex => $attendanceCode) {
                    // Pastikan kode absensi tidak kosong
                    if (!empty($attendanceCode)) {
                        // Jika kode absensi bukan kosong, simpan data kehadiran
                        $cuti = null;
                        $employeeShift = null;
                        $is_day_off = ($attendanceCode == 'dayoff' || $attendanceCode == 'National Holiday' || $attendanceCode == 'CT' || $attendanceCode == 'CM' || $attendanceCode == 'I') ? 1 : null;
                        if ($attendanceCode == 'CT' || $attendanceCode == 'CM' || $attendanceCode == 'I') {
                            $cuti = AttendanceCode::where('code', $attendanceCode)->value('id');
                            $employeeShift = Shift::where('name', 'like', '%dayoff%')->value('id');
                        } else {
                            $employeeShift = Shift::where('name', $attendanceCode)->value('id');
                        }
                        if (isset($employeeShift) || isset($cuti)) {
                            $check_attendance = Attendance::where('employee_id', $employeeId)->where('date', Carbon::parse($dates[$dateIndex])->format('Y-m-d'))->first();
                            if (isset($check_attendance)) {
                                if ($cuti != null && $check_attendance->day_off_request_id == null) {
                                    $day_off = DayOffRequest::where('employee_id', $employeeId)->where('start_date', \Carbon\Carbon::parse($dates[$dateIndex])->format('Y-m-d'))->first();
                                    if (!$day_off) {
                                        DayOffRequest::create([
                                            'attendance_code_id' => 1,
                                            'employee_id' => $employeeId,
                                            'start_date' => \Carbon\Carbon::parse($dates[$dateIndex])->format('Y-m-d'),
                                            'end_date' => \Carbon\Carbon::parse($dates[$dateIndex])->format('Y-m-d'),
                                            'description' => $cuti,
                                            'is_approved' => 'Pending',
                                            'approval_line_child' => $employeeApproval->approval_line,
                                            'approval_line_parent' => $employeeApproval->approval_line_parent,
                                        ]);
                                    }
                                }
                                if ($cuti == null && $check_attendance->day_off_request_id == null) {
                                    $check_attendance->update([
                                        'employee_id' => $employeeId,
                                        'shift_id' => $employeeShift,
                                        'is_day_off' => $is_day_off,
                                        'attendance_code_id' => $cuti,
                                        'date' => \Carbon\Carbon::parse($dates[$dateIndex])->format('Y-m-d'),
                                    ]);
                                }
                            } else {
                                Attendance::create([
                                    'employee_id' => $employeeId,
                                    'shift_id' => $employeeShift,
                                    'is_day_off' => $is_day_off,
                                    'date' => \Carbon\Carbon::parse($dates[$dateIndex])->format('Y-m-d'),
                                    'attendance_code_id' => $cuti,
                                ]);
                            }
                        } else {
                            throw new \Exception("Shift tanggal " . $dates[$dateIndex] . " pada " . $employeeName . " kosong!");
                        }
                    } else {
                        throw new \Exception("Shift " . $employeeName . " pada " . $dates[$dateIndex] . " tidak ditemukan.");
                    }
                }
            } else {
                throw new \Exception("Pegawai " . $employeeName . " tidak ditemukan.");
            }
        }
    }
}
