<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sip;
use App\Models\Str;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EmployeeDashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard.
     */
    public function index()
    {
        return view('pages.pegawai.dashboard.index');
    }

    /**
     * Mengambil data statistik untuk kartu ringkasan.
     */
    public function stats(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Statistik SIP & STR
        $expiredSipCount = Sip::where('sip_expiry_date', '<', $today)->count();
        $expiringSipCount = Sip::whereBetween('sip_expiry_date', [$today, $expiryDateLimit])->count();
        $expiredStrCount = Str::where('is_lifetime', false)->where('str_expiry_date', '<', $today)->count();
        $expiringStrCount = Str::where('is_lifetime', false)->whereBetween('str_expiry_date', [$today, $expiryDateLimit])->count();

        // Statistik Pegawai Baru
        $newEmployeesThisMonth = Employee::whereBetween('join_date', [$startOfMonth, $endOfMonth])->count();

        // Statistik Ulang Tahun Hari Ini
        $birthdaysToday = Employee::whereMonth('birthdate', $today->month)
            ->whereDay('birthdate', $today->day)
            ->count();

        // Statistik Kontrak (Asumsi ada kolom `status`, `join_date` dan `end_status_date` dalam bulan di tabel employees)
        $expiringContracts = Employee::where('employment_status', 'Kontrak') // Sesuaikan dengan nama kolom Anda
            ->where('is_active', 1)
            ->whereBetween('end_status_date', [$today, $expiryDateLimit])
            ->count();

        return response()->json([
            'expired_sip' => $expiredSipCount,
            'expiring_sip' => $expiringSipCount,
            'expired_str' => $expiredStrCount,
            'expiring_str' => $expiringStrCount,
            'new_employees' => $newEmployeesThisMonth,
            'birthdays_today' => $birthdaysToday,
            'expiring_contracts' => $expiringContracts,
        ]);
    }

    /**
     * API endpoint untuk notifikasi SIP kedaluwarsa/mendekati kedaluwarsa.
     */
    public function sipNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $query = Sip::with('employee:id,fullname')
            ->whereBetween('sip_expiry_date', [$today, $expiryDateLimit])
            ->orWhere('sip_expiry_date', '<', $today);

        return DataTables::of($query)
            ->addColumn('status_sip', function ($row) use ($today) {
                $expiry = Carbon::parse($row->sip_expiry_date);
                if ($expiry < $today) {
                    $daysAgo = $expiry->diffInDays($today);
                    return '<span class="badge badge-danger">Kadaluarsa ' . $daysAgo . ' hari lalu</span>';
                }

                $daysRemaining = $today->diffInDays($expiry);
                return '<span class="badge badge-warning">Kadaluarsa dalam ' . $daysRemaining . ' hari</span>';
            })
            ->addColumn('employee_name', fn($row) => $row->employee?->fullname)
            ->rawColumns(['status_sip'])
            ->make(true);
    }

    /**
     * API endpoint untuk notifikasi STR kedaluwarsa/mendekati kedaluwarsa.
     */
    public function strNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $query = Str::with('employee:id,fullname')
            ->where('is_lifetime', false)
            ->where(function ($q) use ($today, $expiryDateLimit) {
                $q->whereBetween('str_expiry_date', [$today, $expiryDateLimit])
                    ->orWhere('str_expiry_date', '<', $today);
            });

        return DataTables::of($query)
            ->addColumn('status_str', function ($row) use ($today) {
                $expiry = Carbon::parse($row->str_expiry_date);
                if ($expiry < $today) {
                    $daysAgo = $expiry->diffInDays($today);
                    return '<span class="badge badge-danger">Kadaluarsa ' . $daysAgo . ' hari lalu</span>';
                }

                $daysRemaining = $today->diffInDays($expiry);
                return '<span class="badge badge-warning">Kadaluarsa dalam ' . $daysRemaining . ' hari</span>';
            })
            ->addColumn('employee_name', fn($row) => $row->employee?->fullname)
            ->rawColumns(['status_str'])
            ->make(true);
    }

    /**
     * API endpoint untuk notifikasi kontrak kerja.
     */
    public function contractNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $query = Employee::where('employment_status', 'Kontrak')
            ->where('is_active', 1)
            ->where('end_status_date', '<=', $expiryDateLimit)
            ->select([
                'id',
                'fullname',
                'end_status_date as contract_end_date'
            ])
            ->orderBy('end_status_date', 'desc');


        return DataTables::of($query)
            ->addColumn('status_kontrak', function ($row) use ($today) {
                $endDate = Carbon::parse($row->contract_end_date);
                if ($endDate < $today) {
                    $daysRemaining = $endDate->diffInDays($today);
                    return '<span class="badge badge-danger">Berakhir ' . $daysRemaining . ' hari lalu</span>';
                } else {
                    $daysRemaining = $today->diffInDays($endDate);
                    return '<span class="badge badge-warning">Berakhir dalam ' . $daysRemaining . ' hari</span>';
                }
            })
            ->rawColumns(['status_kontrak'])
            ->make(true);
    }

    /**
     * API endpoint untuk notifikasi ulang tahun.
     */
    public function birthdayNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $today = Carbon::today();

        $query = Employee::select('id', 'fullname', 'birthdate')
            ->whereNotNull('birthdate')
            ->where(function ($q) use ($days) {
                $q->whereRaw('DAYOFYEAR(birthdate) >= DAYOFYEAR(CURDATE())')
                    ->whereRaw('DAYOFYEAR(birthdate) <= DAYOFYEAR(DATE_ADD(CURDATE(), INTERVAL ? DAY))', [(int) $days]);
            })
            ->orderByRaw('DAYOFYEAR(birthdate) ASC');

        return DataTables::of($query)
            ->addColumn('age', fn($row) => Carbon::parse($row->birthdate)->age . ' tahun')
            ->addColumn('birthday_date', fn($row) => Carbon::parse($row->birthdate)->format('d F'))
            ->make(true);
    }
}
