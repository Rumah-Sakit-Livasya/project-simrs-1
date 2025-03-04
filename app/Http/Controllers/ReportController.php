<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\DayOffRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected function getNotify()
    {
        $day_off_notify = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->latest()->get();
        $attendance_notify = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->get();
        $day_off_count_child = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)->where('is_approved', 'Pending')->count();
        $day_off_count_parent = DayOffRequest::where('approved_line_parent', auth()->user()->employee->id)->where('is_approved', 'Verifikasi')->count();
        $attendance_count_child = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)->where('is_approved', 'Pending')->count();
        $attendance_count_parent = AttendanceRequest::where('approved_line_parent', auth()->user()->employee->id)->where('is_approved', 'Verifikasi')->count();

        return [
            'day_off_notify' => $day_off_notify,
            'attendance_notify' => $attendance_notify,
            'day_off_count_child' => $day_off_count_child,
            'day_off_count_parent' => $day_off_count_parent,
            'attendance_count_parent' => $attendance_count_parent,
            'attendance_count_child' => $attendance_count_child,
        ];
    }

    public function attendances(Request $request)
    {
        $year = $request->input('tahun-filter', Carbon::now()->year); // Ambil tahun dari request, default ke tahun sekarang
        $total_employee = Employee::where('is_active', 1)->count();
        $total_ontime = Attendance::where('clock_in', '!=', null)
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('date', Carbon::now())
            ->where('late_clock_in', null)
            ->where('early_clock_out', null)
            ->count();
        $total_latein = Attendance::where('clock_in', '!=', null)
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('date', Carbon::now())
            ->where('late_clock_in', '!=', null)
            ->count();
        $total_no_check_in = Attendance::where('clock_in', null)
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('date', Carbon::now())
            ->count();
        $total_time_off = Attendance::where('clock_in', null)
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('is_day_off', 1)
            ->where('day_off_request_id', '!=', null)
            ->where('date', Carbon::now())
            ->count();
        $total_day_off = Attendance::where('clock_in', null)
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('is_day_off', 1)
            ->where('date', Carbon::now())
            ->count();

        // Inisialisasi array untuk menyimpan semua data attendances
        $attendancesAllMonths = [];

        // Looping melalui setiap bulan dalam tahun yang diminta
        for ($month = 1; $month <= 12; $month++) {
            // Menghitung tanggal awal untuk bulan sebelumnya (tanggal 26 bulan sebelumnya)
            $previousMonthStartDate = Carbon::create($year, $month - 1, 26);

            // Menghitung tanggal akhir untuk bulan ini (tanggal 25 bulan sekarang)
            $currentMonthEndDate = Carbon::create($year, $month, 25);

            $total_ontime_all = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', null)
                ->where('early_clock_out', null)
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })
                ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
                ->count();
            $total_latein_all = Attendance::where('clock_in', '!=', null)
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })
                ->where('late_clock_in', '!=', null)
                ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
                ->count();
            $total_time_off_all = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })
                ->where('is_day_off', 1)
                ->where('day_off_request_id', '!=', null)
                ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
                ->count();
            $total_day_off_all = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })
                ->where('is_day_off', 1)
                ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
                ->count();

            // Menyimpan data attendances ke dalam array untuk bulan ini
            $attendancesAllMonths[$currentMonthEndDate->format('F')] = [
                'total_ontime_all' => $total_ontime_all,
                'total_latein_all' => $total_latein_all,
                'total_time_off_all' => $total_time_off_all,
                'total_day_off_all' => $total_day_off_all,
            ];
        }

        return view('pages.laporan.absensi.index', [
            'getNotify' => $this->getNotify(),
            'total_employee' => $total_employee,
            'total_ontime' => $total_ontime,
            'total_latein' => $total_latein,
            'total_no_check_in' => $total_no_check_in,
            'total_day_off' => $total_day_off,
            'total_time_off' => $total_time_off,
            'attendancesAllMonths' => $attendancesAllMonths,
            'selectedTahun' => $year // Menyimpan tahun yang dipilih untuk tampilan
        ]);
    }

    public function attendanceReports(Request $request)
    {

        if (Auth::check() && !Auth::user()->hasRole('super admin')) {
            return redirect()->route('attendances')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }


        $total_absent_this_month = 0;
        $total_ontime_this_month = 0;
        $total_latein_this_month = 0;
        $total_nocheckin_this_month = 0;
        $total_dayoff_this_month = 0;
        $total_timeoff_this_month = 0;
        $year = Carbon::now()->year;
        if ($request->year) {
            $year = $request->year;
        }
        $attendancesAllMonths = [];

        // Mulai dari Januari hingga Desember
        for ($month = 1; $month <= 12; $month++) {
            // Tanggal mulai: 26 bulan sebelumnya
            $startDate = Carbon::create($year, $month, 26)->subMonth();
            // Tanggal akhir: 25 bulan sekarang
            $endDate = Carbon::create($year, $month, 25);

            $attendances = Attendance::where('employee_id', auth()->user()->employee->id)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('clock_in', 'ASC')
                ->get();

            $total_ontime_all = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', null)
                ->where('early_clock_out', null)->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
                })
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
            $total_latein_all = Attendance::where('clock_in', '!=', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })->where('late_clock_in', '!=', null)->whereBetween('date', [$startDate, $endDate])->count();
            $total_time_off_all = Attendance::where('is_day_off', 1)
                ->where(function ($query) {
                    $query->orWhere('day_off_request_id', '!=', null)
                        ->orWhere('attendance_code_id', '!=', null);
                })
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
            $total_absent_all = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDate, $endDate])->count();

            if (Carbon::now()->month == $month && Carbon::now()->year == $year) {
                if ($endDate->gt(Carbon::now())) {
                    $total_absent_all = 0;
                }
                if (Carbon::now()->day >= 26) {
                    if ($startDate->format('F') == Carbon::now()->format('F')) {
                        $total_absent_all = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
                        })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDate, Carbon::now()])->count();
                        $total_absent_this_month = $total_absent_all;
                    }
                }
                if (Carbon::now()->day < 26) {
                    if ($endDate->format('F') == Carbon::now()->format('F')) {
                        $total_absent_all = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
                        })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDate, Carbon::now()])->count();
                        $total_absent_this_month = $total_absent_all;
                    }
                }
            }

            $attendancesAllMonths[$endDate->format('F')] = [
                'start_date' => $startDate->format('d F'),
                'end_date' => $endDate->format('d F'),
                'total_ontime_all' => $total_ontime_all,
                'total_latein_all' => $total_latein_all,
                'total_time_off_all' => $total_time_off_all,
                'total_absent_all' => $total_absent_all,
            ];
        }


        if (Carbon::now()->day >= 26) {
            $startDateReport = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                26
            )->addMonth()->subMonth();


            $endDateReport = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                25
            )->addMonth();
        } else {
            $startDateReport = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                26
            )->subMonth();


            $endDateReport = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                25
            );
        }

        $total_absent_this_month = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])->count();
        $total_ontime_this_month = Attendance::where('clock_in', '!=', null)
            ->where('late_clock_in', null)
            ->where('early_clock_out', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })
            ->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])
            ->count();
        $total_latein_this_month = Attendance::where('clock_in', '!=', null)->where('late_clock_in', '!=', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])->count();
        $total_nocheckin_this_month = Attendance::where('clock_in',  null)->where('is_day_off', '!=', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })
            ->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])
            ->count();
        $total_dayoff_this_month = Attendance::where('is_day_off',  null)->where('attendance_code_id', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('day_off_request_id', null)
            ->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])
            ->count();
        $total_timeoff_this_month = Attendance::where('is_day_off', 1)
            ->where(function ($query) {
                $query->orWhere('day_off_request_id', '!=', null)
                    ->orWhere('attendance_code_id', '!=', null);
            })
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
            ->count();

        $bulan = array_keys($attendancesAllMonths);
        $employees = Employee::where('is_active', 1)->get();
        $attendances = [];
        $on_time_reports = [];

        foreach ($employees as $employee) {
            $total_late_in = 0;
            $total_early_out = 0;
            $total_hadir = $employee->attendance->where('clock_in', '!=', null)
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
                ->count();
            $total_izin = 0;
            $total_absent = $employee->attendance->where('clock_in', null)->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDateReport->toDateString(), Carbon::now()])->count();
            $total_cuti = 0;
            $total_sakit = 0;
            $absensi_pegawai = $employee->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport]);

            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif ($absensi->attendance_code_id == null || $absensi->attendance_code_id == "") {
                        // Jika attendance_code_id di Attendance tidak ada, cek di DayOffRequest melalui relasi day_off
                        if ($absensi->day_off) {
                            // Cek apakah day_off_request memiliki attendance_code_id yang diinginkan
                            if ($absensi->day_off->attendance_code_id == 1) {
                                $total_izin += 1;
                            } elseif ($absensi->day_off->attendance_code_id == 2) {
                                $total_sakit += 1;
                            } else {
                                $total_cuti += 1;
                            }
                        }
                    }
                }

                $total_late_in += $absensi->late_clock_in;
                $total_early_out += $absensi->early_clock_out;
            }
            // Push data ke dalam array attendances
            $attendances[] = [
                'employee_name' => $employee->fullname,
                'organization_name' => $employee->organization->name,
                'avatar' => $employee->foto,
                'gender' => $employee->gender,
                'total_hadir' => $total_hadir,
                'total_late_in' => $total_late_in,
                'total_early_out' => $total_early_out,
                'total_izin' => $total_izin,
                'total_sakit' => $total_sakit,
                'total_absent' => $total_absent,
                'total_cuti' => $total_cuti,
            ];

            //ambil jumlah ontime
            $grafik_jumlah_ontime = $employee->attendance->where('clock_in', '!=', null)->where('late_clock_in', null)->where('is_day_off', null)->count();
            $on_time_reports[] = [
                Str::limit($employee->fullname, 8),
                $grafik_jumlah_ontime,
            ];
        }
        // Mengurutkan array berdasarkan nilai grafik_jumlah_ontime secara descending
        usort($on_time_reports, function ($a, $b) {
            return $b[1] - $a[1];
        });

        // Mengambil lima elemen pertama dari array yang telah diurutkan
        $top_5_ontime_reports = array_slice($on_time_reports, 0, 5);

        $groupReport = [
            'PELMED' => [
                'Rawat Inap 1',
                'Rawat Inap 2',
                'Rawat Jalan',
                'IGD',
                'Intensif Care',
                'OK',
                'VK & PONEK',
                'Perinatologi'
            ],
            'PENMED' => [
                'Farmasi',
                'Farmasi Rajal',
                'Farmasi Ranap',
                'Gudang Farmasi',
                'Penunjang Medis',
                'Gizi',
                'Laboratorium',
                'Pendaftaran dan RM',
                'CSSD',
                'Radiologi'
            ],
            'KEU' => [
                'Keuangan'
            ],
            'HRD' => [
                'SDM'
            ],
            'UMUM' => [
                'Security',
                'Sanitasi'
            ],
            'MARKETING' => [
                'Marketing'
            ]
        ];

        $grafik_laporan = [];

        $endDateGrafikLaporan = Carbon::now();

        foreach ($groupReport as $groupName => $units) {
            // Query untuk mengambil attendances dengan tambahan filter tanggal
            $attendancesgr = Attendance::whereHas('employees', function ($query) use ($units) {
                $query->whereHas('organization', function ($query) use ($units) {
                    $query->whereIn('name', $units);
                });
            })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->get();

            // Query untuk menghitung jumlah izin
            $izinCount = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->whereHas('day_off', function ($query) {
                        $query->where('attendance_code_id', 1);
                    })->orWhere('attendance_code_id', 1);
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->count();

            // Query untuk menghitung jumlah sakit
            $sakitCount = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->whereHas('day_off', function ($query) {
                        $query->where('attendance_code_id', 2);
                    })->orWhere('attendance_code_id', 2);
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->count();

            // Query untuk menghitung jumlah cuti
            $cutiCount = Attendance::where('is_day_off', 1)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereNotNull('day_off_request_id')
                            ->whereHas('day_off', function ($query) {
                                $query->where('attendance_code_id', '!=', 1)
                                    ->where('attendance_code_id', '!=', 2);
                            });
                    })->orWhere(function ($query) {
                        $query->whereNotNull('attendance_code_id')
                            ->where('attendance_code_id', '!=', 1)
                            ->where('attendance_code_id', '!=', 2);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->count();

            // Query untuk menghitung jumlah terlambat masuk
            $lateInCount = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', '!=', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->count();

            // Query untuk menghitung jumlah on time
            $onTimeCount = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', null)
                ->where('early_clock_out', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateGrafikLaporan->toDateString()])
                ->count();

            // Query untuk menghitung jumlah hadir
            $hadirCount = $attendancesgr->where('clock_in', '!=', null)
                ->where('is_day_off', null)
                ->count();

            // Query untuk menghitung jumlah absen
            $absenCount = $attendancesgr->where('clock_in', null)
                ->where('is_day_off', null)
                ->where('attendance_code_id', null)
                ->where('day_off_request_id', null)
                ->count();

            // Query untuk menghitung jumlah day off
            $dayOffCount = $attendancesgr->where('clock_in', null)
                ->where('attendance_code_id', '!=', null)
                ->where('day_off_request_id', '!=', null)
                ->count();

            // Query untuk menghitung jumlah libur
            $liburCount = $attendancesgr->where('clock_in', null)
                ->where('is_day_off', 1)
                ->where('attendance_code_id', null)
                ->where('day_off_request_id', null)
                ->count();

            // Menyusun array report untuk grafik laporan
            $grafik_laporan[$groupName] = [
                'Izin' => $izinCount,
                'Sakit' => $sakitCount,
                'Cuti' => $cutiCount,
                'LateIn' => $lateInCount,
                'OnTime' => $onTimeCount,
                'Hadir' => $hadirCount,
                'Absent' => $absenCount,
                'DayOff' => $dayOffCount,
                'Libur' => $liburCount,
            ];
        }
        return view('pages.laporan.absensi.index', [
            'attendancesAllMonths' => $attendancesAllMonths,
            'bulan' => $bulan,
            'selectedBulan' => Carbon::now()->month,
            'selectedTahun' => Carbon::now()->year,
            'selectedTahunGrafikAbsensi' => $year,
            'attendances' => $attendances,
            'employees' => $employees,
            'startDateReport' => $startDateReport,
            'endDateReport' => $endDateReport,
            'total_absent_this_month' => $total_absent_this_month,
            'total_ontime_this_month' => $total_ontime_this_month,
            'total_latein_this_month' => $total_latein_this_month,
            'total_nocheckin_this_month' => $total_nocheckin_this_month,
            'total_dayoff_this_month' => $total_dayoff_this_month,
            'total_timeoff_this_month' => $total_timeoff_this_month,
            'top_5_ontime_reports' => $top_5_ontime_reports,
            'grafik_report_per_unit' => json_encode($grafik_laporan),
        ]);
    }

    public function filterAttendanceReports()
    {

        /*================================================
            START ABSENSI ALL MONTHS REPORTS
        ==================================================*/

        $total_absent_this_month = 0;
        $total_ontime_this_month = 0;
        $total_latein_this_month = 0;
        $total_nocheckin_this_month = 0;
        $total_dayoff_this_month = 0;
        $total_timeoff_this_month = 0;
        $year = Carbon::now()->year;
        $attendancesAllMonths = [];

        // Mulai dari Januari hingga Desember
        for ($month = 1; $month <= 12; $month++) {
            // Tanggal mulai: 26 bulan sebelumnya
            $startDate = Carbon::create($year, $month, 26)->subMonth();
            // Tanggal akhir: 25 bulan sekarang
            $endDate = Carbon::create($year, $month, 25);
            $attendances = Attendance::where('employee_id', auth()->user()->employee->id)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('clock_in', 'ASC')
                ->get();

            $total_ontime_all = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', null)
                ->where('early_clock_out', null)->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
                })
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
            $total_latein_all = Attendance::where('clock_in', '!=', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })->where('late_clock_in', '!=', null)->whereBetween('date', [$startDate, $endDate])->count();
            $total_time_off_all = Attendance::where('is_day_off', 1)
                ->where(function ($query) {
                    $query->orWhere('day_off_request_id', '!=', null)
                        ->orWhere('attendance_code_id', '!=', null);
                })
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
            $total_absent_all = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDate, $endDate])->count();
            if ($endDate->gt(Carbon::now())) {
                $total_absent_all = 0;
            }
            if ($endDate->format('F') == Carbon::now()->format('F')) {
                $total_absent_all = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
                })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDate, Carbon::now()])->count();
                $total_absent_this_month = $total_absent_all;
            }

            $attendancesAllMonths[$endDate->format('F')] = [
                'start_date' => $startDate->format('d F'),
                'end_date' => $endDate->format('d F'),
                'total_ontime_all' => $total_ontime_all,
                'total_latein_all' => $total_latein_all,
                'total_time_off_all' => $total_time_off_all,
                'total_absent_all' => $total_absent_all,
            ];
        }

        /*================================================
            END ABSENSI ALL MONTHS REPORTS
        ==================================================*/
        //--------------------------------------------------------------------------------
        /*================================================
            START ABSENSI THIS MONTH / FILTERED MONTH
        ==================================================*/

        $startDateReport = Carbon::create(
            request()->tahun,
            request()->bulan,
            26
        )->subMonth();


        $endDateReport = Carbon::create(
            request()->tahun,
            request()->bulan,
            25
        );

        if (request()->bulan == Carbon::now()->month && request()->tahun == Carbon::now()->year) {
            if (Carbon::now()->day != 26) {
                $endDateReport = Carbon::now();
            }
        }

        $total_absent_this_month = Attendance::where('clock_in', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])->count();

        $total_ontime_this_month = Attendance::where('clock_in', '!=', null)
            ->where('late_clock_in', null)
            ->where('early_clock_out', null)->whereHas('employees', function ($query) {
                $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            })
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
            ->count();
        $total_latein_this_month = Attendance::where('clock_in', '!=', null)->where('late_clock_in', '!=', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])->count();
        $total_nocheckin_this_month = Attendance::where('clock_in',  null)->where('is_day_off', '!=', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
            ->count();
        $total_dayoff_this_month = Attendance::where('is_day_off',  null)->where('attendance_code_id', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('day_off_request_id', null)
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
            ->count();
        $total_timeoff_this_month = Attendance::where('is_day_off', 1)
            ->where(function ($query) {
                $query->orWhere('day_off_request_id', '!=', null)
                    ->orWhere('attendance_code_id', '!=', null);
            })
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
            ->count();

        /*================================================
            END ABSENSI THIS MONTH / FILTERED MONTH
        ==================================================*/
        //-------------------------------------------------------------------------------------------
        /*================================================
            START TOP 5 RANK ABSENSI
        ==================================================*/
        $bulan = array_keys($attendancesAllMonths);
        $employees = Employee::where('is_active', 1)->get();
        $attendances = [];
        $on_time_reports = [];

        foreach ($employees as $employee) {
            $total_late_in = 0;
            $total_early_out = 0;
            $total_hadir = $employee->attendance->where('clock_in', '!=', null)->where('is_day_off', null)
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])
                ->count();
            $total_izin = 0;
            $total_absent = $employee->attendance->where('clock_in', null)->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport])->count();
            $total_cuti = 0;
            $total_sakit = 0;
            $absensi_pegawai = $employee->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport]);

            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif ($absensi->attendance_code_id == null || $absensi->attendance_code_id == "") {
                        // Jika attendance_code_id di Attendance tidak ada, cek di DayOffRequest melalui relasi day_off
                        if ($absensi->day_off) {
                            // Cek apakah day_off_request memiliki attendance_code_id yang diinginkan
                            if ($absensi->day_off->attendance_code_id == 1) {
                                $total_izin += 1;
                            } elseif ($absensi->day_off->attendance_code_id == 2) {
                                $total_sakit += 1;
                            } else {
                                $total_cuti += 1;
                            }
                        }
                    }
                }

                $total_late_in += $absensi->late_clock_in;
                $total_early_out += $absensi->early_clock_out;
            }
            // Push data ke dalam array attendances
            $attendances[] = [
                'employee_name' => $employee->fullname,
                'organization_name' => $employee->organization->name,
                'avatar' => $employee->foto,
                'gender' => $employee->gender,
                'total_hadir' => $total_hadir,
                'total_late_in' => $total_late_in,
                'total_early_out' => $total_early_out,
                'total_izin' => $total_izin,
                'total_sakit' => $total_sakit,
                'total_absent' => $total_absent,
                'total_cuti' => $total_cuti,
            ];


            //ambil jumlah ontime
            $grafik_jumlah_ontime = $employee->attendance->where('clock_in', '!=', null)->where('late_clock_in', null)->where('is_day_off', null)->count();
            $on_time_reports[] = [
                Str::limit($employee->fullname, 8),
                $grafik_jumlah_ontime,
            ];
        }
        // Mengurutkan array berdasarkan nilai grafik_jumlah_ontime secara descending
        usort($on_time_reports, function ($a, $b) {
            return $b[1] - $a[1];
        });

        // Mengambil lima elemen pertama dari array yang telah diurutkan
        $top_5_ontime_reports = array_slice($on_time_reports, 0, 5);

        /*================================================
            END TOP 5 RANK ABSENSI
        ==================================================*/
        //--------------------------------------------------------------------------------------------

        $groupReport = [
            'PELMED' => [
                'Unit Rawat Inap',
                'Unit Rawat Jalan',
                'Unit IGD',
                'Unit OK',
                'Unit Perinatologi'
            ],
            'PENMED' => [
                'Unit Farmasi',
                'Unit Radiologi'
            ],
            'KEU' => [
                'Unit Keuangan'
            ],
            'HRD' => [
                'Unit SDM'
            ],
            'UMUM' => [
                'Unit Umum Security',
                'Sanitasi'
            ],
            'MARKETING' => [
                'Unit Marketing'
            ]
        ];

        $grafik_laporan = [];

        foreach ($groupReport as $groupName => $units) {
            // Query untuk mengambil attendances dengan tambahan filter tanggal
            $attendances_grafik_filter = Attendance::whereHas('employees', function ($query) use ($units) {
                $query->whereHas('organization', function ($query) use ($units) {
                    $query->whereIn('name', $units);
                });
            })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->get();

            // Query untuk menghitung jumlah izin
            $izinCount = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->whereHas('day_off', function ($query) {
                        $query->where('attendance_code_id', 1);
                    })->orWhere('attendance_code_id', 1);
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();

            // Query untuk menghitung jumlah sakit
            $sakitCount = Attendance::where('clock_in', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->whereHas('day_off', function ($query) {
                        $query->where('attendance_code_id', 2);
                    })->orWhere('attendance_code_id', 2);
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();

            // Query untuk menghitung jumlah cuti
            $cutiCount = Attendance::where('is_day_off', 1)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereNotNull('day_off_request_id')
                            ->whereHas('day_off', function ($query) {
                                $query->where('attendance_code_id', '!=', 1)
                                    ->where('attendance_code_id', '!=', 2);
                            });
                    })->orWhere(function ($query) {
                        $query->whereNotNull('attendance_code_id')
                            ->where('attendance_code_id', '!=', 1)
                            ->where('attendance_code_id', '!=', 2);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();

            // Query untuk menghitung jumlah terlambat masuk
            $lateInCount = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', '!=', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();

            // Query untuk menghitung jumlah on time
            $onTimeCount = Attendance::where('clock_in', '!=', null)
                ->where('late_clock_in', null)
                ->where('early_clock_out', null)
                ->whereHas('employees', function ($query) use ($units) {
                    $query->whereHas('organization', function ($query) use ($units) {
                        $query->whereIn('name', $units);
                    });
                })
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();

            // Query untuk menghitung jumlah hadir
            $hadirCount = $attendances_grafik_filter->where('clock_in', '!=', null)
                ->where('is_day_off', null)
                ->count();

            // Query untuk menghitung jumlah absen
            $absenCount = $attendances_grafik_filter->where('clock_in', null)
                ->where('is_day_off', null)
                ->where('attendance_code_id', null)
                ->where('day_off_request_id', null)
                ->count();

            // Query untuk menghitung jumlah day off
            $dayOffCount = $attendances_grafik_filter->where('clock_in', null)
                ->where('attendance_code_id', '!=', null)
                ->where('day_off_request_id', '!=', null)
                ->count();

            // Query untuk menghitung jumlah libur
            $liburCount = $attendances_grafik_filter->where('clock_in', null)
                ->where('is_day_off', 1)
                ->where('attendance_code_id', null)
                ->where('day_off_request_id', null)
                ->count();

            // Menyusun array report untuk grafik laporan
            $grafik_laporan[$groupName] = [
                'Izin' => $izinCount,
                'Sakit' => $sakitCount,
                'Cuti' => $cutiCount,
                'LateIn' => $lateInCount,
                'OnTime' => $onTimeCount,
                'Hadir' => $hadirCount,
                'Absent' => $absenCount,
                'DayOff' => $dayOffCount,
                'Libur' => $liburCount,
            ];
        }

        // dd($attendances[0]);
        return view('pages.laporan.absensi.index', [
            'attendancesAllMonths' => $attendancesAllMonths,
            'bulan' => $bulan,
            'selectedBulan' => request()->bulan,
            'selectedTahun' => request()->tahun,
            'selectedTahunGrafikAbsensi' => request()->tahun,
            'attendances' => $attendances,
            'employees' => $employees,
            'startDateReport' => $startDateReport,
            'endDateReport' => $endDateReport,
            'total_absent_this_month' => $total_absent_this_month,
            'total_ontime_this_month' => $total_ontime_this_month,
            'total_latein_this_month' => $total_latein_this_month,
            'total_nocheckin_this_month' => $total_nocheckin_this_month,
            'total_dayoff_this_month' => $total_dayoff_this_month,
            'total_timeoff_this_month' => $total_timeoff_this_month,
            'top_5_ontime_reports' => $top_5_ontime_reports,
            'grafik_report_per_unit' => json_encode($grafik_laporan),
        ]);
    }

    public function getReportAttendancesEmployee($employee_id, $tahun = null)
    {

        $parts = explode("-", request()->periode);

        $startMonth = $parts[0];
        $endMonth = $parts[1];

        $startDateReport = Carbon::create(
            $tahun,
            $startMonth,
            26
        )->subMonth();

        $endDateReport = Carbon::create(
            $tahun,
            $endMonth,
            25
        );


        try {
            $employee = Employee::find($employee_id);

            $attendances = [];

            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found'
                ], 404);
            }
            $total_late_in = 0;
            $total_hadir = $employee->attendance->where('clock_in', '!=', null)->where('is_day_off', null)
                ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();
            $total_hari = Attendance::where('employee_id', $employee_id)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();
            $total_libur = Attendance::where('employee_id', $employee_id)->where('is_day_off', 1)->where('day_off_request_id', null)->where('attendance_code_id', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
                ->count();
            $total_izin = 0;
            $total_absent = $employee->attendance->where('clock_in', null)->where('clock_out', null)->where('is_day_off', null)->where('attendance_code_id', null)->where('day_off_request_id', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])->count();
            $total_cuti = 0;
            $total_sakit = 0;
            $absensi_pegawai = $employee->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()]);

            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif ($absensi->attendance_code_id == null || $absensi->attendance_code_id == "") {
                        // Jika attendance_code_id di Attendance tidak ada, cek di DayOffRequest melalui relasi day_off
                        if ($absensi->day_off) {
                            // Cek apakah day_off_request memiliki attendance_code_id yang diinginkan
                            if ($absensi->day_off->attendance_code_id == 1) {
                                $total_izin += 1;
                            } elseif ($absensi->day_off->attendance_code_id == 2) {
                                $total_sakit += 1;
                            } else {
                                $total_cuti += 1;
                            }
                        }
                    }
                }

                $total_late_in += $absensi->late_clock_in;
            }
            // Push data ke dalam array attendances
            $attendances = [
                'total_hari' => $total_hari,
                'total_hadir' => $total_hadir,
                'total_telat' => $total_late_in,
                'total_izin' => $total_izin,
                'total_sakit' => $total_sakit,
                'total_absent' => $total_absent,
                'total_cuti' => $total_cuti,
                'total_libur' => $total_libur,
            ];

            return response()->json($attendances, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function dayOffReqReports(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Set locale ke Indonesia
        Carbon::setLocale('id');

        $currentYear = $request->input('tahun', Carbon::now()->year) ?? Carbon::now()->year;

        // Tentukan startDate dan endDate berdasarkan tahun yang diberikan
        $startDate = Carbon::create($currentYear - 1, 12, 26);
        $endDate = Carbon::create($currentYear, 12, 25);
        $employees = Employee::where('is_active', 1)->get();
        $attendances = [];

        foreach ($employees as $employee) {

            $total_izin = 0;
            $total_cuti = 0;
            $total_sakit = 0;
            $absensi_pegawai = $employee->attendance->where('is_day_off', 1)->whereBetween('date', [$startDate->toDateString(), $endDate]);

            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif ($absensi->attendance_code_id == null || $absensi->attendance_code_id == "") {
                        // Jika attendance_code_id di Attendance tidak ada, cek di DayOffRequest melalui relasi day_off
                        if ($absensi->day_off) {
                            // Cek apakah day_off_request memiliki attendance_code_id yang diinginkan
                            if ($absensi->day_off->attendance_code_id == 1) {
                                $total_izin += 1;
                            } elseif ($absensi->day_off->attendance_code_id == 2) {
                                $total_sakit += 1;
                            } else {
                                $total_cuti += 1;
                            }
                        }
                    }
                }
            }
            // Push data ke dalam array attendances
            $attendances[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->fullname,
                'organization_name' => $employee->organization->name,
                'total_izin' => $total_izin,
                'total_sakit' => $total_sakit,
                'total_cuti' => $total_cuti,
            ];
        }

        // Query untuk mendapatkan data attendances berdasarkan range tanggal dan employee_id
        // $attendances = Attendance::where('employee_id', auth()->user()->employee->id)
        //     ->whereBetween('date', [$startDate, $endDate])
        //     ->get();

        // $last_attendance = Attendance::where('employee_id', auth()->user()->employee->id)->where('date', Carbon::now()->format('Y-m-d'))->first();
        // $jumlah_hadir = Attendance::where('employee_id', auth()->user()->employee->id)->where('is_day_off', null)->where('clock_in', '!=', null)->whereBetween('date', [$startDate, $endDate])->count();
        // $day_off = Attendance::where('employee_id', auth()->user()->employee->id)->where('day_off_request_id', '!=', null)->whereBetween('date', [$startDate, $endDate])->get();
        // $jumlah_izin = 0;
        // $jumlah_sakit = 0;
        // $jumlah_cuti = 0;

        // foreach ($day_off as $row) {
        //     $code = $row->day_off->attendance_code->code;
        //     if ($code == "I") {
        //         $jumlah_izin++;
        //     } else if ($code == "S") {
        //         $jumlah_sakit++;
        //     } else {
        //         $jumlah_cuti++;
        //     }
        // }


        return view('pages.laporan.daftar-cuti.index', compact('currentYear', 'attendances'));
    }

    public function dayOffReqReportDetail(Request $request, $id, $tahun)
    {

        $currentYear = $tahun ?? Carbon::now()->year;
        $startDate = Carbon::create($currentYear - 1, 12, 26);
        $endDate = Carbon::create($currentYear, 12, 25);

        // Query untuk mendapatkan data attendances berdasarkan range tanggal dan employee_id
        // $attendances = Attendance::where('employee_id', $id)
        //     ->whereBetween('date', [$startDate, $endDate])
        //     ->get();
        // $day_off = Attendance::where('employee_id', $id)->where('day_off_request_id', '!=', null)->whereBetween('date', [$startDate, $endDate])->get();
        $day_off = Attendance::where('is_day_off', 1)
            ->where('employee_id', $id)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('day_off_request_id')
                        ->whereHas('day_off'); // Tidak perlu titik koma di akhir
                })->orWhere(function ($query) {
                    $query->whereNotNull('attendance_code_id'); // Tetap cek kalau attendance_code_id tidak null
                });
            })
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();


        return view('pages.laporan.daftar-cuti.detail', compact('day_off', 'currentYear'));
    }
}
