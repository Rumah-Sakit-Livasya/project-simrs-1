<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\AspekPenilaian;
use App\Models\Attendance;
use App\Models\AttendanceCode;
use App\Models\AttendanceOutsource;
use App\Models\AttendanceRequest;
use App\Models\Bank;
use App\Models\BankEmployee;
use App\Models\ChMessage;
use App\Models\Company;
use App\Models\DayOffRequest;
use App\Models\Employee;
use App\Models\GroupPenilaian;
use App\Models\Holiday;
use App\Models\JobLevel;
use App\Models\JobPosition;
use App\Models\Location;
use App\Models\Menu;
use App\Models\Organization;
use App\Models\PayrollComponent;
use App\Models\RekapPenilaianBulanan;
use App\Models\PenilaianPegawai;
use App\Models\Shift;
use App\Models\SIMRS\Departement;
use App\Models\Structure;
use App\Models\Target;
use App\Models\UploadFile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{

    protected function getNotify()
    {
        $day_off_notify = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->latest()->get();
        $attendance_notify = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)->orWhere('approved_line_parent', auth()->user()->employee->id)->latest()->get();
        $day_off_count_child = DayOffRequest::where('approved_line_child', auth()->user()->employee->id)
            ->where(function ($query) {
                $query->where('is_approved', 'Pending')
                    ->orWhere('is_approved', 'Verifikasi');
            })
            ->count();
        $day_off_count_child -= DayOffRequest::where('approved_line_child', auth()->user()->employee->id)
            ->where(function ($query) {
                $query->where('is_approved', 'Verifikasi')
                    ->whereNotNull('approved_line_parent');
            })
            ->count();
        $day_off_count_parent = DayOffRequest::where('approved_line_parent', auth()->user()->employee->id)->where('is_approved', 'Verifikasi')->count();
        $attendance_count_child = AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)
            ->where(function ($query) {
                $query->where('is_approved', 'Pending')
                    ->orWhere('is_approved', 'Verifikasi');
            })->count();

        $attendance_count_child -= AttendanceRequest::where('approved_line_child', auth()->user()->employee->id)
            ->where(function ($query) {
                $query->where('is_approved', 'Verifikasi')
                    ->whereNotNull('approved_line_parent');
            })
            ->count();
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

    public function index(Request $request)
    {
        if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('manager')) {
            $day_off = Attendance::where('is_day_off', 1)->where('date', now()->format('Y-m-d'))->orderBy('day_off_request_id', 'desc')->get();
        } else {
            $day_off = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.id')
                ->where('attendances.is_day_off', 1)
                ->where('employees.company_id', auth()->user()->employee->company_id)
                ->whereDate('attendances.date', now()->format('Y-m-d'))
                ->select('attendances.*', 'employees.company_id')
                ->orderBy('attendances.day_off_request_id', 'desc')
                ->get();
        }

        $employees = Employee::where('is_active', 1)->get();
        $totalKaryawan = $employees->count();

        // Status Kepegawaian
        $jmlPermanen = $employees->where('employment_status', 'Permanen')->count();
        $jmlKontrak = $employees->where('employment_status', 'Kontrak')->count();

        $persentasePermanen = number_format(($jmlPermanen / $totalKaryawan) * 100);
        $persentaseKontrak = number_format(($jmlKontrak / $totalKaryawan) * 100);

        // return $persentaseKontrak;

        $statusKepegawaian = [
            'totalKaryawan' => $totalKaryawan,
            'jmlPermanen' => $jmlPermanen,
            'jmlKontrak' => $jmlKontrak,
            'persentasePermanen' => $persentasePermanen,
            'persentaseKontrak' => $persentaseKontrak
        ];

        // Masa Jabatan
        $lessThan1Year = 0;
        $oneTo3Years = 0;
        $threeTo5Years = 0;
        $fiveTo10Years = 0;
        $moreThan10Years = 0;
        $unassigned = 0;

        $now = Carbon::now();

        foreach ($employees as $employee) {
            if ($employee->join_date === null || $employee->end_status_date === null) {
                $unassigned++;
                continue;
            }

            $joinDate = Carbon::parse($employee->join_date);
            $lengthOfService = $joinDate->diffInYears($now);

            if ($lengthOfService < 1) {
                $lessThan1Year++;
            } elseif ($lengthOfService >= 1 && $lengthOfService <= 3) {
                $oneTo3Years++;
            } elseif ($lengthOfService > 3 && $lengthOfService <= 5) {
                $threeTo5Years++;
            } elseif ($lengthOfService > 5 && $lengthOfService <= 10) {
                $fiveTo10Years++;
            } else {
                $moreThan10Years++;
            }
        }

        // Buat output berupa array dengan jumlah karyawan berdasarkan kategori masa jabatan
        $masaJabatan = [
            'less_than_1_year' => $lessThan1Year,
            '1_to_3_years' => $oneTo3Years,
            '3_to_5_years' => $threeTo5Years,
            '5_to_10_years' => $fiveTo10Years,
            'more_than_10_years' => $moreThan10Years,
            'unassigned' => $unassigned,
        ];

        // Job Level
        $jobLevels = [
            'Director',
            'Owner',
            'Head',
            'Supervisor',
            'Coordinator',
            'Staff',
            'Non Staff',
            'Dokter Full-Time',
            'Dokter Part-Time',
        ];

        $jobLevel = [];

        // Hitung jumlah karyawan untuk setiap tingkatan pekerjaan
        foreach ($jobLevels as $level) {
            $count = Employee::where('is_active', 1)->where('job_level_id', function ($query) use ($level) {
                $query->select('id')->from('job_levels')->where('name', $level);
            })->count();

            $sluggableLevel = Str::slug($level);


            $jobLevel[$sluggableLevel] = $count;
        }

        $totalKaryawan = Employee::where('is_active', 1)->get()->count();
        $jobLevel['totalKaryawan'] = $totalKaryawan;

        // Hitung persentase untuk setiap tingkatan pekerjaan
        foreach ($jobLevel as $level => $count) {
            $percentage = ($count / $totalKaryawan) * 100;
            $jobLevel['persentase-' . $level] = number_format($percentage, 1); // Format menjadi satu desimal
        }

        // return $jobLevel;

        // Jenis Kelamin
        $jmlMale = $employees->where('gender', "Laki-laki")->count();
        $jmlFemale = $employees->where('gender', "Perempuan")->count();
        $persentaseMale = number_format(($jmlMale / $totalKaryawan) * 100);
        $persentaseFemale = number_format(($jmlFemale / $totalKaryawan) * 100);


        $genderDiversity = [
            'totalKaryawan' => $totalKaryawan,
            'lakiLaki' => $jmlMale,
            'persentaseLakiLaki' => $persentaseMale,
            'perempuan' => $jmlFemale,
            'persentasePerempuan' => $persentaseFemale,
        ];

        $userId = auth()->user()->id;

        $message = ChMessage::where('to_id', $userId)->count();

        // Subquery untuk mendapatkan ID pesan terbaru dari setiap pengirim
        $subQuery = ChMessage::select(DB::raw('MAX(id) as latest_id'))
            ->where('to_id', $userId)
            ->groupBy('from_id');

        // Gabungkan subquery dengan tabel ch_messages untuk mendapatkan detail pesan terbaru
        $listMessage = ChMessage::joinSub($subQuery, 'latest_messages', function ($join) {
            $join->on('ch_messages.id', '=', 'latest_messages.latest_id');
        })->get();


        $year = $request->tahun;

        $employees = Employee::where('is_active', 1)->get();
        $totalEmployees = $employees->count();
        $lateCount = [];

        foreach ($employees as $employee) {
            $monthlyLateCount = [];
            $totalLateMinutes = 0; // Inisialisasi total telat untuk karyawan saat ini

            for ($month = 1; $month <= 12; $month++) {
                $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

                $lateMinutes = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('late_clock_in');

                $totalLateMinutes += $lateMinutes; // Menambahkan total telat bulanan ke total telat karyawan saat ini

                $monthlyLateCount[$month] = $lateMinutes > 30 ? 1 : 0;

                // Menyimpan total telat ke dalam array
                $lateCount[$employee->id] = $totalLateMinutes;
            }

            $lateCount[$employee->id] = $monthlyLateCount;
        }

        return view('dashboard', [
            'lateCount' => $lateCount,
            'totalEmployees' => $totalEmployees,
            'jobLevel' => $jobLevel,
            'message' => $message,
            'genderDiversity' => $genderDiversity,
            'masaJabatan' => $masaJabatan,
            'statusKepegawaian' => $statusKepegawaian,
            'day_off' => $day_off,
            'employees' => $employees,
            'getNotify' => $this->getNotify(),
        ]);
    }

    public function  getDataRoles()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $getNotify = $this->getNotify();
        return view('pages.master-data.role.index', compact('roles', 'permissions', 'getNotify'));
    }

    public function  getDataMenus()
    {
        $menus = Menu::all();
        $getNotify = $this->getNotify();
        return view('pages.master-data.menu.index', compact('menus', 'getNotify'));
    }

    public function  getDataUsers()
    {
        $users = User::where('is_active', 1)->get();
        $employees = Employee::where('is_active', 1)->get();
        $roles = Role::all();
        $getNotify = $this->getNotify();
        return view('pages.master-data.user.index', compact('users', 'roles', 'getNotify', 'employees'));
    }

    public function getDataUserAkses()
    {
        $roles = Role::all();

        return view('pages.master-data.user.assign-permissions', compact('roles'));
    }

    public function getDataAssignPermissions($id)
    {
        $user = User::findOrFail($id);
        $user_name = $user->name;
        $user_id = $user->id;
        $userPermissions = $user->permissions->pluck('id')->toArray();
        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        return view('pages.master-data.user.assign-permissions', compact('user_name', 'user_id', 'permissions', 'userPermissions'));
    }

    public function getAllAttendances()
    {
        //====== Data Absensi Hari Ini ==========//
        $total_employee = Employee::where('is_active', 1)->get()->count();
        $total_ontime = Attendance::whereNotNull('clock_in')->where('late_clock_in', null)
            ->whereDate('date', Carbon::now()->format('Y-m-d'))
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1); // Hanya untuk karyawan yang aktif
            })->count();
        $total_latein = Attendance::where('clock_in', '!=', null)->where('late_clock_in', '!=', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('date', Carbon::now()->format('Y-m-d'))->count();
        $total_no_check_in = Attendance::where('clock_in', null)->where('is_day_off', null)->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
            $query->where('organization_id', '!=', 3);
        })->where('date', Carbon::now()->format('Y-m-d'))->count();
        //buat yng cuti
        $total_time_off = Attendance::where('clock_in', null)
            ->where(function ($query) {
                $query->where('day_off_request_id', '!=', null)
                    ->orWhere('attendance_code_id', '!=', null);
            })
            ->whereHas('employees', function ($query) {
                $query->where('is_active', 1); // Hanya untuk karyawan yang aktif
            })
            ->where('is_day_off', 1)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->count();
        // buat hari libur (minggu, hari nasional, lepas libur)
        $total_day_off = Attendance::where('is_day_off', 1)->where(function ($query) {
            $query->where('day_off_request_id',  null)
                ->Where('attendance_code_id', null);
        })->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->where('date', Carbon::now()->format('Y-m-d'))->count();
        $attendance_today = Attendance::where('date', Carbon::now()->format('Y-m-d'))->whereHas('employees', function ($query) {
            $query->where('is_active', 1);  // Hanya untuk karyawan yang aktif
        })->orderBy('clock_in', 'ASC')->get();
        if (auth()->user()->hasRole('hr')) {
            $total_employee = Employee::where('company_id', auth()->user()->employee->company_id)->where('is_active', 1)->count();
            $total_ontime = Attendance::where('clock_in', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->where('late_clock_in', null)->where('early_clock_out', null)
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })->count();
            $total_latein = Attendance::where('clock_in', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->where('late_clock_in', '!=', null)
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })->count();
            $total_no_check_in = Attendance::where('clock_in', null)->where('is_day_off', null)->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })->count();
            //buat yng cuti
            $total_time_off = Attendance::where('clock_in', null)->where('is_day_off', 1)->where(function ($query) {
                $query->where('day_off_request_id', '!=', null)
                    ->orWhere('attendance_code_id', '!=', null);
            })->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })->count();
            // buat hari libur (minggu, hari nasional, lepas libur)
            $total_day_off = Attendance::where('is_day_off', 1)->where(function ($query) {
                $query->where('day_off_request_id',  null)
                    ->Where('attendance_code_id', null);
            })->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })->count();
            $attendance_today = Attendance::where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->where('company_id', auth()->user()->employee->company_id)->where('is_active', 1);
                })
                ->orderBy('employee_id')
                ->get();
        } else if (auth()->user()->hasRole('pj') || auth()->user()->hasRole('manager')) {
            //list organisasi berdasarkan jabatan
            $organizations = [];
            $organizations[] = auth()->user()->employee->organization->id;
            $organizations_parent = auth()->user()->employee->organization->child_structures;
            foreach ($organizations_parent as $row) {
                $organizations[] = $row->organization->id;
                $child = $row->organization->child_structures;
                if ($child->count() > 0) {
                    foreach ($child as $col) {
                        $organizations[] = $col->organization->id;
                        $parent = $col->organization->child_structures;
                        if ($parent->count() > 0) {
                            foreach ($parent as $coll) {
                                $organizations[] = $coll->organization->id;
                            }
                        }
                    }
                }
            }

            $total_employee = Employee::where('company_id', auth()->user()->employee->company_id)
                ->where('is_active', 1)
                ->whereIn('organization_id', $organizations)
                ->count();
            // $total_employee = Employee::where('company_id', auth()->user()->employee->company_id)
            //     ->where('is_active', 1)
            //     ->whereIn('organization_id', $organizations)
            //     ->whereHas('attendances') // Mengambil hanya yang memiliki attendance
            //     ->count();
            // dd($total_employee);
            $total_ontime = Attendance::where('clock_in', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->where('late_clock_in', null)
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })->count();
            $total_latein = Attendance::where('clock_in', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->where('late_clock_in', '!=', null)
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })->count();
            $total_no_check_in = Attendance::where('clock_in', null)->where('is_day_off', null)->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })->count();

            //buat yng cuti
            $total_time_off = Attendance::where('clock_in', null)->where('is_day_off', 1)->where(function ($query) {
                $query->where('day_off_request_id', '!=', null)
                    ->orWhere('attendance_code_id', '!=', null);
            })->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })->count();
            // buat hari libur (minggu, hari nasional, lepas libur)
            $total_day_off = Attendance::where('is_day_off', 1)->where(function ($query) {
                $query->where('day_off_request_id',  null)
                    ->Where('attendance_code_id', null);
            })->where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })->count();
            $attendance_today = Attendance::where('date', Carbon::now()->format('Y-m-d'))
                ->whereIn('employee_id', function ($query) use ($organizations) {
                    $query->select('id')
                        ->from('employees')
                        ->where('is_active', 1)
                        ->where('company_id', auth()->user()->employee->company_id)
                        ->whereIn('organization_id', $organizations);
                })
                ->orderBy('employee_id')
                ->get();
        }

        $shifts = Shift::all();
        // dd(isset($total_time_off));
        return view('pages.monitoring.daftar-absensi.index', [
            'getNotify' => $this->getNotify(),
            'total_employee' => $total_employee,
            'total_ontime' => $total_ontime,
            'total_latein' => $total_latein,
            'total_no_check_in' => $total_no_check_in,
            'total_day_off' => $total_day_off,
            'total_time_off' => $total_time_off,
            'attendance_today' => $attendance_today,
            'shifts' => $shifts,
        ]);
    }

    public function getEmployeeAttendance($id, Request $request)
    {
        $getNotify = $this->getNotify();

        // Periksa apakah permintaan telah menyertakan parameter periode
        if ($request->has('periode')) {
            $periode = $request->periode;
            list($startMonth, $endMonth) = explode(' - ', $periode);
            $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth()->addDays(25); // 26 April 2024
            $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->startOfMonth()->addDays(24);
        } else {
            // Jika tidak, tentukan periode bulan sekarang
            $today = Carbon::now();
            if ($today->day >= 26) {
                // Jika sudah tanggal 26 atau setelahnya, gunakan bulan berikutnya
                $startPeriod = $today->copy()->startOfMonth()->addDays(1); // Tanggal 1 bulan depan
            } else {
                // Jika masih sebelum tanggal 26, gunakan bulan saat ini
                $startPeriod = $today->copy()->subMonth()->startOfMonth()->addDays(25); // Tanggal 26 bulan sebelumnya
            }
            $endPeriod = $today->copy()->subMonth()->endOfMonth()->addDays(25); // Tanggal 25 bulan sekarang
            $periode = $startPeriod->format('F Y') . ' - ' . $endPeriod->format('F Y');
        }

        // Query untuk mencari data absensi sesuai dengan periode yang diminta
        $attendances = Attendance::where('employee_id', $id)
            ->whereBetween('date', [$startPeriod, $endPeriod])
            ->get();

        $shifts = Shift::all();
        return view('pages.monitoring.daftar-absensi.show', compact('attendances', 'shifts'));
    }

    public function getEmployeeAttendancePayroll($id, Request $request)
    {
        $getNotify = $this->getNotify();

        // Periksa apakah permintaan telah menyertakan parameter periode
        if ($request->has('periode')) {
            $periode = $request->periode;
            list($startMonth, $endMonth) = explode(' - ', $periode);
            $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth()->addDays(25); // 26 April 2024
            $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->startOfMonth()->addDays(24);
        } else {
            // Jika tidak, tentukan periode bulan sekarang
            $today = Carbon::now();
            if ($today->day >= 26) {
                // Jika sudah tanggal 26 atau setelahnya, gunakan bulan berikutnya
                $startPeriod = $today->copy()->startOfMonth()->addDays(1); // Tanggal 1 bulan depan
            } else {
                // Jika masih sebelum tanggal 26, gunakan bulan saat ini
                $startPeriod = $today->copy()->subMonth()->startOfMonth()->addDays(25); // Tanggal 26 bulan sebelumnya
            }
            $endPeriod = $today->copy()->subMonth()->endOfMonth()->addDays(25); // Tanggal 25 bulan sekarang
            $periode = $startPeriod->format('F Y') . ' - ' . $endPeriod->format('F Y');
        }

        // Query untuk mencari data absensi sesuai dengan periode yang diminta
        $attendances = Attendance::where('employee_id', $id)
            ->whereBetween('date', [$startPeriod, $endPeriod])
            ->get();
        $employees = Employee::where('id', $id)->get();
        $attendance_codes = AttendanceCode::all();
        $shifts = Shift::all();
        $day_off['ct'] = 12;

        $day_off['cm'] = 12;

        $day_off['cma'] = 12;

        $day_off['cka'] = 12;

        $day_off['cim'] = 12;

        $day_off['ck'] = 12;

        $day_off['ckm'] = 12;

        $day_off['crm'] = 12;

        $day_off['cl'] = 12;

        return view('pages.monitoring.daftar-absensi.payroll', compact('attendances', 'shifts', 'employees', 'attendance_codes', 'day_off'));
    }

    public function getDataUser()
    {
        $userId = auth()->user()->id;
        $getEmployee = Employee::where('is_active', 1)->get();
        $attendances = AttendanceRequest::all();
        $user = User::where('id', $userId)->first();
        $employee = $user->employee;
        $getNotify = $this->getNotify();
        $approvalLine = $getEmployee->where('id', $employee->approval_line)->first();
        $approvalParent = $getEmployee->where('id', $employee->approval_line_parent)->first();
        $employeeGroup = $getEmployee->where('organization_id', $employee->organization_id);
        $upload_files = UploadFile::where('pic', auth()->user()->employee->id)->where('kategori', 'Kepegawaian')->where('tipe', 2)->get();

        $roles = Role::all();
        return view('pages.pegawai.profil-pegawai.index', compact('user', 'roles', 'employee', 'getNotify', 'approvalLine', 'approvalParent', 'employeeGroup', 'upload_files'));
    }

    public function  getDataOrganizations()
    {
        $organizations = Organization::orderBy('name', 'asc')->get();
        $getNotify = $this->getNotify();
        return view('pages.master-data.organization.index', compact('organizations', 'getNotify'));
    }

    public function getDataJobLevels()
    {
        $jobLevel = JobLevel::all();
        $getNotify = $this->getNotify();
        return view('pages.master-data.job-level.index', compact('jobLevel', 'getNotify'));
    }

    public function getDataJobPositions()
    {
        $jobPosition = JobPosition::all();
        $getNotify = $this->getNotify();
        return view('pages.master-data.job-position.index', compact('jobPosition', 'getNotify'));
    }

    public function getDataEmployees()
    {
        $employees = Employee::where('is_active', 1)->get();
        $employees_non_aktif = Employee::where('is_active', null)->orWhere('is_active', 0)->count();
        $jobLevel = JobLevel::all();
        $organizations = Organization::all();
        $departements = Departement::all();
        $jobPosition = JobPosition::all();
        $locations = Location::all();
        $bank = Bank::all();
        $company = Company::all();
        $getNotify = $this->getNotify();

        return view('pages.pegawai.daftar-pegawai.index', compact('employees', 'employees_non_aktif', 'jobLevel', 'organizations', 'departements', 'jobPosition', 'locations', 'bank', 'company', 'getNotify'));
    }

    public function pegawaiNonAktifList(Request $request)
    {
        try {
            // dd($request->status, $request->organization_id);
            $employees = Employee::where('is_active', 1)->get();
            $employees_nonaktif = null;
            $departements = Departement::all();
            $status = isset($request->status) ? ($request->status == 0 ? false : true) : true;
            if (isset($request->status) && isset($request->organization_id)) {
                $employees_nonaktif = Employee::where('is_active', $status)->where('organization_id', $request->organization_id)->get();
            } else {
                if (isset($request->status)) {
                    $employees_nonaktif = Employee::where('is_active', $status)->get();
                }
                if (isset($request->organization_id)) {
                    $employees_nonaktif = Employee::where('organization_id', $request->organization_id)->get();
                }
            }

            $jobLevel = JobLevel::all();
            $organizations = Organization::all();
            $employees_non_aktif = Employee::where('is_active', null)->orWhere('is_active', 0)->count();
            $jobPosition = JobPosition::all();
            $locations = Location::all();
            $bank = Bank::all();
            $company = Company::all();
            $getNotify = $this->getNotify();

            return view('pages.pegawai.daftar-pegawai.index', compact('employees', 'employees_non_aktif', 'jobLevel', 'organizations', 'jobPosition', 'locations', 'bank', 'company', 'getNotify', 'employees_nonaktif', 'departements'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDataHolidays()
    {
        return view('pages.master-data.holidays.index', [
            'holidays' => Holiday::all(),
            'getNotify' => $this->getNotify()
        ]);
    }

    public function getDataAttendanceCodes()
    {
        return view('pages.master-data.attendance-code.index', [
            'attendance_code' => AttendanceCode::all(),
            'getNotify' => $this->getNotify()
        ]);
    }

    public function getDataShifts()
    {
        return view('pages.master-data.shift.index', [
            'shifts' => Shift::all(),
            'getNotify' => $this->getNotify()
        ]);
    }

    public function getDataBanks()
    {
        return view('pages.master-data.banks.index', [
            'banks' => Bank::all(),
            'getNotify' => $this->getNotify()
        ]);
    }

    public function getDataBankEmployees()
    {
        return view('pages.master-data.bank-employees.index', [
            // 'bank_employees' => BankEmployee::where('is_active',1)->get(),
            'employees' => Employee::where('is_active', 1)->get(),
            'getNotify' => $this->getNotify(),
            'banks' => Bank::all()
        ]);
    }

    public function getDataStructures()
    {
        return view('pages.master-data.structures.index', [
            'organizations' => Organization::all(),
            'structures' => Structure::all(),
            'getNotify' => $this->getNotify(),
        ]);
    }

    public function getManagementShift()
    {
        if (auth()->user()->hasRole('super admin')) {
            $employees = Employee::where('is_active', 1)->get();
            $organizations = Organization::all();
        } else if (auth()->user()->hasRole('pj')) {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->where('is_active', 1)->where('organization_id', auth()->user()->employee->organization_id)->get();
            $organizations = [];
        } else {
            $employees = Employee::where('company_id', auth()->user()->employee->company_id)->where('is_active', 1)->get();
            $organizations = [];
        }

        return view('pages.pegawai.manajemen-shift.index', [
            'getNotify' => $this->getNotify(),
            'employees' => $employees,
            'organizations' => $organizations
        ]);
    }

    public function editManagementShift($id)
    {
        $getNotify = $this->getNotify();
        // Mendapatkan tanggal hari ini
        $currentDate = Carbon::now();

        // Jika tanggal sekarang lebih dari 25, mulai dari tanggal 26 bulan lalu
        if ($currentDate->day > 25) {
            $previousMonthStartDate = $currentDate->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan DEPAN (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        } else {
            // Jika tanggal sekarang kurang dari atau sama dengan 25, mulai dari tanggal 26 dua bulan lalu
            $previousMonthStartDate = $currentDate->subMonths()->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan ini (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addDays(24);
        }

        $attendances = Attendance::where('employee_id', $id)
            ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
            ->get();
        // Query untuk mendapatkan data attendances berdasarkan range tanggal dan employee_id

        if ($attendances->count() > 0) {
            $shifts = Shift::all();
            return view('pages.pegawai.manajemen-shift.edit_shift', compact('getNotify', 'attendances', 'shifts'));
        } else {
            return redirect()->route('management-shift')->with('error', 'Shift Tidak ditemukan!');
        }
    }

    public function dayOffRequest()
    {
        $getNotify = $this->getNotify();
        $day_off_requests = DayOffRequest::where('employee_id', auth()->user()->employee->id)->latest()->get();
        $attendance_code_all = AttendanceCode::all();

        if (isset($day_off_requests)) {
            $employee = Employee::find(auth()->user()->employee->id);
            // Menggunakan tahun dan bulan saat ini
            $startDateReport = Carbon::create(
                now()->year,
                1,
                26
            )->subMonth();

            $endDateReport = Carbon::create(
                now()->year,
                12,
                25
            );

            $day_off = [];
            $absensi_pegawai = $employee->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport]);
            $day_off['ct'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 3) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 3) {
                    return true;
                }

                return false;
            })->count();

            $day_off['cm'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 7) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 7) {
                    return true;
                }

                return false;
            })->count();

            $day_off['cma'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 8) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 8) {
                    return true;
                }

                return false;
            })->count();

            $day_off['cka'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 9) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 9) {
                    return true;
                }

                return false;
            })->count();

            $day_off['cim'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 10) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 10) {
                    return true;
                }

                return false;
            })->count();

            $day_off['ck'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 12) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 12) {
                    return true;
                }

                return false;
            })->count();

            $day_off['ckm'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 13) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 13) {
                    return true;
                }

                return false;
            })->count();

            $day_off['crm'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 14) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 14) {
                    return true;
                }

                return false;
            })->count();

            $day_off['cl'] = $absensi_pegawai->filter(function ($attendance) {
                if ($attendance->attendance_code_id == 15) {
                    return true;
                }

                if (is_null($attendance->attendance_code_id) && $attendance->day_off && $attendance->day_off->attendance_code_id == 15) {
                    return true;
                }

                return false;
            })->count();

            $attendance_code = AttendanceCode::whereNotNull('total_hari')->get();
            foreach ($attendance_code as $row) {
                if ($row->id == 3) {
                    $day_off['ct'] = $row->total_hari - $day_off['ct'];
                }
                if ($row->id == 7) {
                    $day_off['cm'] = $row->total_hari - $day_off['cm'];
                }
                if ($row->id == 8) {
                    $day_off['cma'] = $row->total_hari - $day_off['cma'];
                }
                if ($row->id == 9) {
                    $day_off['cka'] = $row->total_hari - $day_off['cka'];
                }
                if ($row->id == 10) {
                    $day_off['cim'] = $row->total_hari - $day_off['cim'];
                }
                if ($row->id == 12) {
                    $day_off['ck'] = $row->total_hari - $day_off['ck'];
                }
                if ($row->id == 13) {
                    $day_off['ckm'] = $row->total_hari - $day_off['ckm'];
                }
                if ($row->id == 14) {
                    $day_off['crm'] = $row->total_hari - $day_off['crm'];
                }
                if ($row->id == 15) {
                    $day_off['cl'] = $row->total_hari - $day_off['cl'];
                }
            }
        }

        return view('pages.absensi.pengajuan-cuti.index', compact('day_off_requests', 'attendance_code', 'attendance_code_all', 'day_off', 'getNotify'));
    }

    public function getAttendances()
    {
        // dd(auth()->user()->getRoleNames());
        if (auth()->user()->employee->employment_status == 'Outsource') {
            return redirect()->route('monitoring.attendances.outsource');
        }
        $getNotify = $this->getNotify();

        // Mendapatkan tanggal hari ini
        $currentDate = Carbon::now();

        // Jika tanggal sekarang lebih dari 25, mulai dari tanggal 26 bulan lalu
        if ($currentDate->day > 25) {
            $previousMonthStartDate = $currentDate->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan DEPAN (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        } else {
            // Jika tanggal sekarang kurang dari atau sama dengan 25, mulai dari tanggal 26 dua bulan lalu
            $previousMonthStartDate = $currentDate->subMonths()->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan ini (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addDays(24);
        }


        // Query untuk mendapatkan data attendances berdasarkan range tanggal dan employee_id
        $attendances = Attendance::where('employee_id', auth()->user()->employee->id)
            ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
            ->get();

        $last_attendance = Attendance::where('employee_id', auth()->user()->employee->id)->where('date', Carbon::now()->format('Y-m-d'))->first();
        $jumlah_hadir = Attendance::where('employee_id', auth()->user()->employee->id)->where('is_day_off', null)->where('clock_in', '!=', null)->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])->count();
        $day_off = Attendance::where('employee_id', auth()->user()->employee->id)->where('day_off_request_id', '!=', null)->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])->get();
        $jumlah_izin = 0;
        $jumlah_sakit = 0;
        $jumlah_cuti = 0;

        foreach ($day_off as $row) {
            $code = $row->day_off->attendance_code->code;
            if ($code == "I") {
                $jumlah_izin++;
            } else if ($code == "S") {
                $jumlah_sakit++;
            } else {
                $jumlah_cuti++;
            }
        }

        $selectedBulan = Carbon::now()->month;
        $selectedTahun = Carbon::now()->year;

        return view('pages.absensi.absensi.index', compact('selectedBulan', 'selectedTahun', 'attendances', 'getNotify', 'jumlah_izin', 'jumlah_sakit', 'jumlah_cuti', 'jumlah_hadir', 'last_attendance'));
    }

    public function getAttendancesFilter()
    {
        // dd(auth()->user()->can('view.tests'));
        if (auth()->user()->employee->employment_status == 'Outsource') {
            return redirect()->route('attendances.outsource');
        }
        $getNotify = $this->getNotify();


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

        $attendances = Attendance::where('employee_id', auth()->user()->employee->id)
            ->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
            ->get();

        $last_attendance = Attendance::where('employee_id', auth()->user()->employee->id)->where('date', Carbon::now()->format('Y-m-d'))->first();
        $jumlah_hadir = Attendance::where('employee_id', auth()->user()->employee->id)->where('is_day_off', null)->where('clock_in', '!=', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])->count();
        $day_off = Attendance::where('employee_id', auth()->user()->employee->id)->where('day_off_request_id', '!=', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])->get();
        $jumlah_izin = 0;
        $jumlah_sakit = 0;
        $jumlah_cuti = 0;

        foreach ($day_off as $row) {
            $code = $row->day_off->attendance_code->code;
            if ($code == "I") {
                $jumlah_izin++;
            } else if ($code == "S") {
                $jumlah_sakit++;
            } else {
                $jumlah_cuti++;
            }
        }


        $selectedBulan = request()->bulan;
        $selectedTahun = request()->tahun;

        return view('pages.absensi.absensi.index', compact('selectedBulan', 'selectedTahun', 'attendances', 'getNotify', 'jumlah_izin', 'jumlah_sakit', 'jumlah_cuti', 'jumlah_hadir', 'last_attendance'));
    }

    public function getAttendancesOutsourcing()
    {
        // Mendapatkan tanggal hari ini
        $currentDate = Carbon::now();
        $previousMonthStartDate = null;
        $currentMonthEndDate = null;

        // Jika tanggal sekarang lebih dari 25, mulai dari tanggal 26 bulan lalu
        if ($currentDate->day > 25) {
            $previousMonthStartDate = $currentDate->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan DEPAN (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        } else {
            // Jika tanggal sekarang kurang dari atau sama dengan 25, mulai dari tanggal 26 dua bulan lalu
            $previousMonthStartDate = $currentDate->subMonths()->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan ini (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addDays(24);
        }

        $attendances = AttendanceOutsource::where('employee_id', auth()->user()->employee_id)
            ->whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])
            ->get();

        return view('pages.absensi.absensi.outsource', compact('attendances'));
    }

    public function getAttendancesOutsourcingAll()
    {
        // Mendapatkan tanggal hari ini
        $currentDate = Carbon::now();
        $previousMonthStartDate = null;
        $currentMonthEndDate = null;

        // Jika tanggal sekarang lebih dari 25, mulai dari tanggal 26 bulan lalu
        if ($currentDate->day > 25) {
            $previousMonthStartDate = $currentDate->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan DEPAN (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        } else {
            // Jika tanggal sekarang kurang dari atau sama dengan 25, mulai dari tanggal 26 dua bulan lalu
            $previousMonthStartDate = $currentDate->subMonths()->startOfMonth()->addDays(25);
            // Menghitung tanggal akhir untuk bulan ini (tanggal 25 bulan ini)
            $currentMonthEndDate = Carbon::now()->startOfMonth()->addDays(24);
        }

        $attendances = AttendanceOutsource::orderBy('date')->get();
        // $attendances = AttendanceOutsource::whereBetween('date', [$previousMonthStartDate, $currentMonthEndDate])->get();
        $employees = Employee::where('employment_status', 'Outsource')->get();
        return view('pages.absensi.absensi.list-outsource', compact('attendances', 'employees'));
    }

    public function getDayOffRequest($id)
    {
        $day_off_requests = DayOffRequest::where('id', $id)->get();
        if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('hr')) {

            $getNotify = $this->getNotify();
            $attendance_code = AttendanceCode::all();
            return view('pages.absensi.pengajuan-cuti.show', compact('day_off_requests', 'attendance_code', 'getNotify'));
        } else {
            if (
                $day_off_requests[0]->approved_line_child == auth()->user()->employee->id ||
                $day_off_requests[0]->approved_line_parent == auth()->user()->employee->id
            ) {
                $getNotify = $this->getNotify();
                $attendance_code = AttendanceCode::all();
                return view('pages.absensi.pengajuan-cuti.show', compact('day_off_requests', 'attendance_code', 'getNotify'));
            } else {
                return redirect()->route('dashboard')->with('error', 'Anda tidak bisa mengakses ini!');
            }
        }
    }

    public function attendanceRequest()
    {

        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        // Set locale ke Indonesia
        Carbon::setLocale('id');
        $getNotify = $this->getNotify();
        $attendance_requests = AttendanceRequest::where('employee_id', auth()->user()->employee->id)->get();
        $attendance = Attendance::where('date', Carbon::now()->format('Y-m-d'))->where('employee_id', auth()->user()->employee->id)->first();
        $is_request = false;
        if ($attendance) {
            $current_time = Carbon::now();
            $shift_time_in = Carbon::createFromFormat('H:i', $attendance->shift->time_in);
            
            // Cek apakah shift time_in lebih dari 1 jam dari sekarang
            $is_request = $shift_time_in->diffInHours($current_time, false) > 1;
        }

        $is_request = !$is_request;

        return view('pages.absensi.pengajuan-absensi.index', compact('attendance_requests', 'getNotify', 'is_request'));
    }
    public function getAttendanceRequest($id)
    {
        $getNotify = $this->getNotify();
        $attendance_requests = AttendanceRequest::where('id', $id)->latest()->get();
        if ($attendance_requests[0]->approved_line_child == auth()->user()->employee_id || $attendance_requests[0]->approved_line_parent == auth()->user()->employee_id || auth()->user()->hasRole('super admin')) {
            return view('pages.absensi.pengajuan-absensi.show', compact('attendance_requests', 'getNotify'));
        } else {
            return redirect()->route('attendance-requests')->with('error', 'Tidak bisa mengakses halaman ini!');
        }
    }
    public function getDataLocations()
    {
        $locations = Location::all();
        return view('pages.lokasi.index', compact('locations'));
    }

    public function getDataRequests()
    {
        //list organisasi berdasarkan jabatan
        $organizations = [];
        $organizations[] = auth()->user()->employee->organization->id;
        $organizations_parent = auth()->user()->employee->organization->child_structures;
        foreach ($organizations_parent as $row) {
            $organizations[] = $row->organization->id;
            $child = $row->organization->child_structures;
            if ($child->count() > 0) {
                foreach ($child as $col) {
                    $organizations[] = $col->organization->id;
                    $parent = $col->organization->child_structures;
                    if ($parent->count() > 0) {
                        foreach ($parent as $coll) {
                            $organizations[] = $coll->organization->id;
                        }
                    }
                }
            }
        }

        $employees = Employee::select('id', 'fullname')->where('is_active', 1)->get();
        $attendance_codes = AttendanceCode::all();

        //monitoring untuk bagian hak akses pj kabag kabid
        if (auth()->user()->hasRole('pj')) {
            $day_off_requests = DayOffRequest::whereIn('employee_id', function ($query) use ($organizations) {
                $query->select('id')
                    ->from('employees')
                    ->whereIn('organization_id', $organizations);
            })->get();
            $attendance_requests = AttendanceRequest::whereIn('employee_id', function ($query) use ($organizations) {
                $query->select('id')
                    ->from('employees')
                    ->whereIn('organization_id', $organizations);
            })->get();

            $total_pending = $day_off_requests->where('is_approved', 'Pending')->count() + $attendance_requests->where('is_approved', 'Pending')->count();
            $total_verifikasi = $day_off_requests->where('is_approved', 'Verifikasi')->count() + $attendance_requests->where('is_approved', 'Verifikasi')->count();
            $total_ditolak = $day_off_requests->where('is_approved', 'Ditolak')->count() + $attendance_requests->where('is_approved', 'Ditolak')->count();
            $total_disetujui = $day_off_requests->where('is_approved', 'Disetujui')->count() + $attendance_requests->where('is_approved', 'Disetujui')->count();
        } else {
            $day_off_requests = DayOffRequest::all();
            $attendance_requests = AttendanceRequest::all();
            $total_disetujui = $day_off_requests->where('is_approved', 'Disetujui')->count() + $attendance_requests->where('is_approved', 'Disetujui')->count();
            $total_pending = $day_off_requests->where('is_approved', 'Pending')->count() + $attendance_requests->where('is_approved', 'Pending')->count();
            $total_verifikasi = $day_off_requests->where('is_approved', 'Verifikasi')->count() + $attendance_requests->where('is_approved', 'Verifikasi')->count();
            $total_ditolak = $day_off_requests->where('is_approved', 'Ditolak')->count() + $attendance_requests->where('is_approved', 'Ditolak')->count();
        }

        $day_off['ct'] = 12;

        $day_off['cm'] = 12;

        $day_off['cma'] = 12;

        $day_off['cka'] = 12;

        $day_off['cim'] = 12;

        $day_off['ck'] = 12;

        $day_off['ckm'] = 12;

        $day_off['crm'] = 12;

        $day_off['cl'] = 12;

        return view('pages.monitoring.daftar-pengajuan.index', compact('day_off_requests', 'attendance_requests', 'total_disetujui', 'total_pending', 'total_verifikasi', 'total_ditolak', 'attendance_codes', 'employees', 'day_off'));
    }

    public function getGroupPenilaian()
    {
        $group_penilaian = GroupPenilaian::all();
        return view('pages.kpi.group-penilaian.index', compact('group_penilaian'));
    }

    public function editGroupPenilaian($id)
    {
        $group_penilaian = GroupPenilaian::find($id);
        $employee = Employee::get(['id', 'fullname']);
        return view('pages.kpi.group-penilaian.edit', compact('group_penilaian', 'employee'));
    }

    public function tbhAspekPenilaian()
    {
        return view('pages.kpi.aspek_penilaian.tambah');
    }

    public function tbhGroupPenilaian()
    {
        $employee = Employee::where('is_active', 1)->get(['id', 'fullname']);
        return view('pages.kpi.group-penilaian.tambah', compact('employee'));
    }

    public function tbhPenilaian($id)
    {
        $group_penilaian = GroupPenilaian::find($id);
        $employees = Employee::where('is_active', 1)->get(['id', 'fullname']);
        $penilai_parent = Employee::where('is_active', 1)->where('id', $group_penilaian->penilai)->firstOrFail(['fullname', 'employee_code', 'job_position_id', 'organization_id']);
        $pejabat_penilai_parent = Employee::where('is_active', 1)->where('id', $group_penilaian->pejabat_penilai)->firstOrFail(['fullname', 'employee_code', 'job_position_id', 'organization_id']);

        $penilai = [
            'nama' => $penilai_parent->fullname,
            'jabatan' => JobPosition::find($penilai_parent->job_position_id)->name,
            'unit' => Organization::find($penilai_parent->organization_id)->name,
            'nip' => $penilai_parent->employee_code
        ];

        $pejabat_penilai = [
            'nama' => $pejabat_penilai_parent->fullname,
            'jabatan' => JobPosition::find($pejabat_penilai_parent->job_position_id)->name,
            'unit' => Organization::find($pejabat_penilai_parent->organization_id)->name,
            'nip' => $pejabat_penilai_parent->employee_code
        ];

        $rumus_penilaian = $group_penilaian->rumus_penilaian;

        return view('pages.kpi.penilaian.index', compact('group_penilaian', 'employees', 'penilai', 'pejabat_penilai', 'rumus_penilaian'));
    }

    public function showPenilaianBulanan($id_form, $id_pegawai, $periode, $tahun)
    {
        $group_penilaian = GroupPenilaian::find($id_form);
        $penilaian_pegawai = PenilaianPegawai::where('group_penilaian_id', $id_form)->where('employee_id', $id_pegawai)->where('periode', $periode)->where('tahun', $tahun)->orderBy('indikator_penilaian_id', 'asc')->get();
        $penilai_parent = Employee::where('is_active', 1)->where('id', $group_penilaian->penilai)->firstOrFail(['fullname', 'employee_code', 'job_position_id', 'organization_id']);
        $pejabat_penilai_parent = Employee::where('is_active', 1)->where('id', $group_penilaian->pejabat_penilai)->firstOrFail(['fullname', 'employee_code', 'job_position_id', 'organization_id']);
        $catatan = RekapPenilaianBulanan::where('employee_id', $id_pegawai)->where('group_penilaian_id', $id_form)->where('periode', $periode)->first();

        $total_nilai_all = [];
        $nilai_kalkulasi = null;
        $total_nilai = null;
        $nilai = null;
        $index = 0;
        $total_akhir = null;
        foreach ($group_penilaian->aspek_penilaians as $aspek) {
            $nilai = 0;
            foreach ($aspek->indikator_penilaians->sortBy('id') as $indikator) {
                // foreach($penilaian_pegawai)
                if ($indikator->id == $penilaian_pegawai[$index]->indikator_penilaian_id) {
                    $nilai += $penilaian_pegawai[$index]->nilai;
                    $index++;
                }
            }
            $nilai_kalkulasi = ($nilai / 40) * ($aspek->bobot / 100);
            $total_nilai = $nilai_kalkulasi * 100;
            $total_akhir += round($total_nilai);
            $total_nilai_all[] = [
                'nilai' => $nilai,
                'nilai_kalkulasi' => floor($nilai_kalkulasi * 1000) / 1000,
                'total_nilai' => round($total_nilai),
            ];
        }

        $penilai = [
            'nama' => $penilai_parent->fullname,
            'jabatan' => JobPosition::find($penilai_parent->job_position_id)->name,
            'unit' => Organization::find($penilai_parent->organization_id)->name,
            'nip' => $penilai_parent->employee_code
        ];

        $pejabat_penilai = [
            'nama' => $pejabat_penilai_parent->fullname,
            'jabatan' => JobPosition::find($pejabat_penilai_parent->job_position_id)->name,
            'unit' => Organization::find($pejabat_penilai_parent->organization_id)->name,
            'nip' => $pejabat_penilai_parent->employee_code
        ];
        // $data = ['title' => 'Welcome to Laravel PDF!'];
        $pdf = Pdf::loadView('pages.kpi.penilaian.show', compact('pejabat_penilai', 'penilai', 'penilaian_pegawai', 'group_penilaian', 'catatan', 'total_nilai_all', 'total_akhir'));
        $nama = $catatan->employee->fullname . " " . $periode . " " . $tahun . ".pdf";
        $namaFile = str_replace(' ', '_', $nama);
        // return $pdf->download($namaFile);

        $bulan = explode(" - ", $periode);

        // Mendapatkan bulan awal dan bulan akhir
        $bulanAwal = trim($bulan[0]);
        $bulanAkhir = trim($bulan[1]);

        // Pemetaan bulan dari bahasa Indonesia ke bahasa Inggris
        $bulanMapping = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];

        // Mengonversi bulan ke bahasa Inggris
        $bulanFixStart = $bulanMapping[$bulanAwal];
        $bulanFixEnd = $bulanMapping[$bulanAkhir];

        $employee = Employee::find($id_pegawai);

        $startDateReport = Carbon::create(
            $tahun,
            $bulanFixStart,
            26
        );

        $endDateReport = Carbon::create(
            $tahun,
            $bulanFixEnd,
            25
        );

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
        $total_hari = Attendance::where('employee_id', $id_pegawai)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
            ->count();
        $total_libur = Attendance::where('employee_id', $id_pegawai)->where('is_day_off', 1)->where('day_off_request_id', null)->where('attendance_code_id', null)->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()])
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

        return view('pages.kpi.penilaian.show', compact('pejabat_penilai', 'penilai', 'penilaian_pegawai', 'group_penilaian', 'catatan', 'total_nilai_all', 'total_akhir', 'attendances'));
    }

    public function rekapPenilaianBulanan()
    {
        $rekap_penilaian = RekapPenilaianBulanan::all();
        $employee = Employee::all();
        $nilai = null;
        $nilai_pegawai = [];
        // Menghitung total nilai untuk setiap pegawai
        foreach ($employee as $row) {
            $nilai = 0;
            if ($row->rekap_penilaians) {
                foreach ($row->rekap_penilaians as $col) {
                    $nilai += $col->total_nilai;
                }
            }
            $nilai_pegawai[] = [
                Str::limit($row->fullname, 10),
                $nilai
            ];
        }

        // Mengurutkan array berdasarkan total nilai secara descending
        usort($nilai_pegawai, function ($a, $b) {
            return $b[1] - $a[1];
        });

        // Mengambil 5 pegawai dengan total nilai terbanyak
        $top_5_pegawai = array_slice($nilai_pegawai, 0, 5);

        $sangat_baik = $rekap_penilaian->where('tahun', Carbon::now()->format('Y'))->where('total_nilai', '>', 95);
        $baik = $rekap_penilaian->where('tahun', Carbon::now()->format('Y'))->where('total_nilai', '>', 85)->where('total_nilai', '<', 96);
        $cukup = $rekap_penilaian->where('tahun', Carbon::now()->format('Y'))->where('total_nilai', '>', 65)->where('total_nilai', '<', 86);
        $kurang = $rekap_penilaian->where('tahun', Carbon::now()->format('Y'))->where('total_nilai', '>', 50)->where('total_nilai', '<', 66);
        $sangat_kurang = $rekap_penilaian->where('tahun', Carbon::now()->format('Y'))->where('total_nilai', '<=', 50);
        return view('pages.kpi.penilaian.lists', compact('rekap_penilaian', 'sangat_baik', 'baik', 'cukup', 'kurang', 'sangat_kurang', 'top_5_pegawai'));
    }

    public function getAspekPenilaian()
    {
        $aspek_penilaians = AspekPenilaian::all();
        return view('pages.kpi.aspek_penilaian.index', compact('aspek_penilaians'));
    }

    public function getDataPermissions()
    {
        $permissions = Permission::orderBy('group')->get();
        return view('pages.master-data.permissions.index', compact('permissions'));
    }

    // Target
    public function getDataTargets(Request $request)
    {
        $employees = Employee::where('is_active', 1)->get();

        // Mendapatkan input dari form
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');
        $organizationId = auth()->user()->can('admin okr') ? $request->input('organization_id') : auth()->user()->employee->organization_id;

        // Mengambil data target sesuai dengan filter

        $targets = $this->getFilteredTargets($organizationId, $bulan, $tahun, $status);

        // $targetNames = $targets->pluck('title')->toArray();
        // return dd($targets);
        // Menghitung data target
        $namaOrganisasi = Organization::where('id', $organizationId)->first();
        $targetData = $this->calculateTargetStats($targets, $namaOrganisasi);

        // Ambil persentase dan nama target untuk view
        $percentages = $targets->pluck('persentase')->toArray();
        $targetNames = $targets->pluck('title')->toArray();

        return view('pages.target.index', [
            'organizations' => Organization::whereHas('targets')->get(),
            'employees' => $employees,
            'targets' => $targets,
            'targetData' => $targetData,
            'percentages' => $percentages,
            'targetNames' => $targetNames,
            'getNotify' => $this->getNotify(),
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
            'selectedOrganization' => $organizationId,
        ]);
    }

    public function getDataTargetReport(Request $request)
    {
        // Mendapatkan organization_id dari pengguna yang sedang login
        $organizationId = auth()->user()->employee->organization_id;
        $organizationIdInput = $request->input('organization_id', $organizationId);

        // Mendapatkan input dari form
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        // Mengambil data target sesuai filter dan mengurutkan sesuai dengan status dan persentase
        $organizationTarget = Organization::with(['targets' => function ($query) use ($bulan, $tahun, $status, $organizationIdInput) {
            $this->applyFilters($query, $bulan, $tahun, $status, $organizationIdInput);
            // Hanya menambahkan urutan berdasarkan status dan persentase, tanpa mempengaruhi perhitungan statistik
            $query->orderByRaw("FIELD(status, 'invalid', 'red', 'yellow', 'blue', 'green')")
                ->orderBy('persentase', 'asc');
        }])->whereHas('targets', function ($query) use ($bulan, $tahun, $status, $organizationIdInput) {
            $this->applyFilters($query, $bulan, $tahun, $status, $organizationIdInput);
        })->get();

        // Menghitung data target per organisasi dengan nama organisasi
        $organizationData = $organizationTarget->map(fn($org) => $this->calculateTargetStats($org->targets, $org));

        // Urutkan dan siapkan data untuk grafik
        $sortedOrganizationDataRev = $organizationData->sortBy(function ($item) {
            // Mengurutkan berdasarkan status menggunakan FIELD
            $statusOrder = [
                'invalid' => 1,
                'red' => 2,
                'yellow' => 3,
                'blue' => 4,
                'green' => 5,
            ];

            // Mengembalikan urutan berdasarkan status dan persentase
            return [$statusOrder[$item['status']] ?? 99, $item['percentage']];
        })->values();
        $organizationList = $sortedOrganizationDataRev->pluck('name');
        $percentages = $sortedOrganizationDataRev->pluck('percentage');
        $targetData = array_fill(0, $organizationList->count(), 80);

        // Mem-flatten dan mengurutkan target sesuai status
        $flattenedTargets = $organizationTarget->pluck('targets')->flatten()
            ->sortBy(function ($target) {
                // Mengurutkan berdasarkan status
                return array_search($target->status, ['invalid', 'red', 'yellow', 'blue', 'green']);
            })
            ->sortBy('persentase'); // Anda bisa menambahkan urutan berdasarkan persentase jika diperlukan


        return view('pages.target.report', [
            'targets' => $flattenedTargets,
            'jumlahUnit' => $organizationList->count(),
            'organizations' => Organization::whereHas('targets')->get(),
            'organizationTarget' => $organizationTarget,
            'organizationList' => $organizationList,
            'organizationData' => $sortedOrganizationDataRev,
            'percentages' => $percentages,
            'targetData' => $targetData,
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
            'selectedOrganization' => $organizationIdInput,
            'getNotify' => $this->getNotify()
        ]);
    }


    // Method untuk filter query berdasarkan bulan, tahun, dan status
    private function applyFilters($query, $bulan, $tahun, $status, $organization)
    {
        $query->when($bulan, fn($q) => $q->where('bulan', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($organization, fn($q) => $q->where('organization_id', $organization));
        // ->where('organization_id', $organizationId);
    }

    // Method untuk mengambil target berdasarkan filter dan mengurutkannya sesuai urutan status
    private function getFilteredTargets($organizationId, $bulan, $tahun, $status)
    {
        return Target::where('organization_id', $organizationId)
            ->when($bulan, fn($q) => $q->where('bulan', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByRaw("FIELD(status, 'invalid', 'red', 'yellow', 'blue', 'green')") // Urutkan sesuai urutan status
            ->orderBy('persentase', 'asc') // Urutkan berdasarkan persentase terkecil
            ->get();
    }

    // Method untuk menghitung statistik target dengan nama organisasi opsional
    private function calculateTargetStats($targets, $organisasi = null)
    {
        $totalTargets = $targets->count();
        $matchingTargets = $targets->where('persentase', '>=', 100)->count();
        // $hampirTercapai = $targets->whereBetween('persentase', [60, 99])->count();
        $tidakTercapai = $targets->whereBetween('persentase', [0, 99])->count();
        // $minimProgress = $targets->where('persentase', '<', 30)->count();
        // $noMoveTarget = $targets->where('movement', 0)->count();
        $percentage = $totalTargets > 0 ? round(($matchingTargets / $totalTargets) * 100, 1) : 0;

        // Menentukan status berdasarkan persentase
        if ($percentage >= 100) {
            $status = 'green';
        } elseif ($percentage >= 60) {
            $status = 'blue';
        } elseif ($percentage >= 30) {
            $status = 'yellow';
        } elseif ($percentage < 30) {
            $status = 'red';
        } else {
            $status = 'invalid';
        }


        return [
            'name' => $organisasi->name ?? 'Tidak Ada Nama Organisasi', // Nama organisasi opsional
            'percentage' => $percentage,
            'jumlah_target' => $totalTargets,
            'target_tercapai' => $matchingTargets,
            // 'target_hampir_tercapai' => $hampirTercapai,
            'target_tidak_tercapai' => $tidakTercapai,
            // 'minim_progress' => $minimProgress,
            // 'target_tidak_dikerjakan' => $noMoveTarget,
            'status' => $status,
        ];
    }
}
