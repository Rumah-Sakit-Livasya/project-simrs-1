<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sip;
use App\Models\Str;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class EmployeeDashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard.
     */
    public function index()
    {
        // Tampilkan view dashboard
        return view('pages.pegawai.dashboard.index');
    }

    /**
     * Mengambil data statistik untuk kartu ringkasan.
     */
    public function stats(Request $request)
    {
        $days = (int) $request->input('days', 30); // Ensure integer
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $expiredSipCount = Sip::where('sip_expiry_date', '<', $today)->count();
        $expiringSipCount = Sip::whereBetween('sip_expiry_date', [$today, $expiryDateLimit])->count();

        $expiredStrCount = Str::where('is_lifetime', false)->where('str_expiry_date', '<', $today)->count();
        $expiringStrCount = Str::where('is_lifetime', false)->whereBetween('str_expiry_date', [$today, $expiryDateLimit])->count();
        $lifetimeStrCount = Str::where('is_lifetime', true)->count();

        return response()->json([
            'expired_sip' => $expiredSipCount,
            'expiring_sip' => $expiringSipCount,
            'expired_str' => $expiredStrCount,
            'expiring_str' => $expiringStrCount,
            'lifetime_str' => $lifetimeStrCount,
        ]);
    }

    /**
     * API endpoint untuk data tabel notifikasi SIP.
     */
    public function sipNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30); // Ensure integer
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $query = Sip::with('employee:id,fullname')
            ->where('sip_expiry_date', '<=', $expiryDateLimit)
            ->select('sips.*');

        return DataTables::of($query)
            ->addColumn('status', function ($row) use ($today) {
                $expiryDate = Carbon::parse($row->sip_expiry_date);
                if ($expiryDate < $today) {
                    $daysRemaining = $expiryDate->diffInDays($today);
                    return '<span class="badge badge-danger">Kadaluarsa ' . $daysRemaining . ' hari lalu</span>';
                } else {
                    $daysRemaining = $today->diffInDays($expiryDate);
                    return '<span class="badge badge-warning">Kadaluarsa dalam ' . $daysRemaining . ' hari</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    /**
     * API endpoint untuk data tabel notifikasi STR.
     */
    public function strNotifications(Request $request)
    {
        $days = (int) $request->input('days', 30); // Ensure integer
        $today = Carbon::today();
        $expiryDateLimit = $today->copy()->addDays($days);

        $query = Str::with('employee:id,fullname')
            ->where(function ($q) use ($today, $expiryDateLimit) {
                // Ambil yang akan kadaluarsa atau sudah kadaluarsa
                $q->where('is_lifetime', false)
                    ->where('str_expiry_date', '<=', $expiryDateLimit);
            })
            ->orWhere('is_lifetime', true) // Atau yang seumur hidup
            ->select('strs.*');

        return DataTables::of($query)
            ->addColumn('status', function ($row) use ($today) {
                if ($row->is_lifetime) {
                    return '<span class="badge badge-success">Seumur Hidup</span>';
                }

                $expiryDate = Carbon::parse($row->str_expiry_date);
                if ($expiryDate < $today) {
                    $daysRemaining = $expiryDate->diffInDays($today);
                    return '<span class="badge badge-danger">Kadaluarsa ' . $daysRemaining . ' hari lalu</span>';
                } else {
                    $daysRemaining = $today->diffInDays($expiryDate);
                    return '<span class="badge badge-warning">Kadaluarsa dalam ' . $daysRemaining . ' hari</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
