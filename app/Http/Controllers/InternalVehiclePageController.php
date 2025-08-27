<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Employee;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\InspectionResult;
use App\Models\InternalVehicle;
use App\Models\User;
use App\Models\VehicleLog;
use App\Models\VehicleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalVehiclePageController extends Controller
{
    // Menampilkan halaman daftar kendaraan
    public function index()
    {
        $vehicles = InternalVehicle::latest()->paginate(10);
        return view('pages.internal-vehicle.index', compact('vehicles'));
    }

    // Menampilkan halaman driver
    public function drivers()
    {
        $employees = Employee::orderBy('fullname')->get();
        return view('pages.drivers.index', compact('employees'));
    }

    // Menampilkan halaman dashboard manajemen kendaraan
    public function dashboard()
    {
        // === BAGIAN 1: NOTIFIKASI & PERINGATAN PROAKTIF ===
        $alerts = [
            'expiring_taxes' => InternalVehicle::where('tax_due_date', '>=', now())
                ->where('tax_due_date', '<=', now()->addDays(30))->get(),
            'expiring_stnk' => InternalVehicle::where('stnk_due_date', '>=', now())
                ->where('stnk_due_date', '<=', now()->addDays(60))->get(),
            'expiring_licenses' => Driver::where('masa_berlaku_sim', '>=', now())
                ->where('masa_berlaku_sim', '<=', now()->addDays(30))->get(),
            // Asumsi: tabel 'internal_vehicles' memiliki kolom 'current_km' dan 'last_oil_change_km'
            // dan interval ganti oli adalah 5000 KM.
            'oil_change_due' => InternalVehicle::whereRaw('current_km - last_oil_change_km >= 4900')->get(),
        ];
        $totalAlerts = count($alerts['expiring_taxes']) + count($alerts['expiring_stnk']) + count($alerts['expiring_licenses']) + count($alerts['oil_change_due']);

        // === BAGIAN 2: DATA UNTUK KARTU KPI ===
        $kpi = [
            'total_vehicles' => InternalVehicle::count(),
            'open_tickets' => VehicleService::where('status', 'Open')->count(),
            'total_alerts' => $totalAlerts,
            'total_cost_this_month' => VehicleService::whereMonth('service_date', now()->month)
                ->sum(DB::raw('labor_cost + parts_cost')),
        ];

        // === BAGIAN 3: LAPORAN PERAWATAN (UNTUK TABEL & GRAFIK) ===
        // KODE BARU YANG BENAR DAN EFISIEN
        // Ganti 'vehicle_services' menjadi 'services' jika Anda sudah mengubah nama relasinya
        $maintenanceReport = InternalVehicle::with('vehicle_services') // 1. Load semua relasi service
            ->withCount('vehicle_services as total_tickets') // 2. Hitung jumlah tiketnya
            ->get();

        $maintenanceCosts = VehicleService::select(
            DB::raw('YEAR(service_date) as year, MONTH(service_date) as month'),
            DB::raw('SUM(labor_cost + parts_cost) as total_cost')
        )
            ->whereNotNull('service_date')->where('service_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();
        $chartLabels = $maintenanceCosts->map(fn($item) => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)));
        $chartData = $maintenanceCosts->pluck('total_cost');

        // === BAGIAN 4: LAPORAN INSPEKSI (RINGKASAN) ===
        $mostCommonFinding = InspectionResult::where('status', 'Rusak')
            ->with('item:id,name') // Hanya ambil id dan name dari relasi item
            ->select('inspection_item_id', DB::raw('count(*) as total'))
            ->groupBy('inspection_item_id')
            ->orderBy('total', 'desc')
            ->first();
        $inspectionSummary = [
            'total_sessions' => Inspection::whereMonth('inspection_date', now()->month)->count(),
            'total_findings' => InspectionResult::where('status', 'Rusak')
                ->whereHas('inspection', fn($q) => $q->whereMonth('inspection_date', now()->month))
                ->count(),
            'most_common_finding' => $mostCommonFinding,
        ];

        // === BARU: AMBIL DATA KENDARAAN YANG SEDANG DIGUNAKAN ===
        $vehiclesInUse = VehicleLog::with('internal_vehicle', 'driver')
            ->where('status', 'Digunakan')
            ->latest('start_datetime') // Tampilkan yang paling baru keluar di atas
            ->get();

        // === BAGIAN 5: LAPORAN OPERASIONAL (UNTUK TABEL) ===
        // Note: Query ini bisa menjadi berat jika data logs sangat banyak.
        // Asumsi: tabel 'vehicle_logs' memiliki kolom 'fuel_liters'
        $operationalReport = InternalVehicle::get()->map(function ($vehicle) {
            $logs = $vehicle->vehicleLogs()->whereNotNull('end_odometer')->get();
            $totalDistance = $logs->sum(fn($log) => $log->kilometer_akhir - $log->kilometer_awal);
            $totalFuel = $logs->sum('fuel_liters'); // Ganti 'fuel_liters' dengan nama kolom Anda
            return (object) [
                'name' => $vehicle->name,
                'total_distance' => $totalDistance,
                'total_fuel' => $totalFuel,
                'average' => $totalFuel > 0 ? $totalDistance / $totalFuel : 0,
            ];
        });

        return view('pages.internal-vehicle.dashboard', compact(
            'kpi',
            'alerts',
            'maintenanceReport',
            'chartLabels',
            'chartData',
            'inspectionSummary',
            'operationalReport',
            'vehiclesInUse'
        ));
    }


    // Menampilkan halaman daftar inspection item kendaraan internal
    public function inspection_item()
    {
        return view('pages.inspection_items.index');
    }

    // Menampilkan halaman daftar vendor kendaraan internal
    public function vendors()
    {
        return view('pages.workshop-vendors.index');
    }

    public function vehicle_logs()
    {
        // 1. Ambil semua ID kendaraan dan pengemudi yang statusnya 'Digunakan'.
        $inUseLogs = VehicleLog::where('status', 'Digunakan');

        $unavailableVehicleIds = $inUseLogs->pluck('internal_vehicle_id')->toArray();
        $unavailableDriverIds = $inUseLogs->pluck('driver_id')->toArray();

        // 2. Ambil Kendaraan yang 'tersedia' (ID-nya TIDAK ADA di dalam daftar yang tidak tersedia).
        $vehicles = InternalVehicle::whereNotIn('id', $unavailableVehicleIds)
            ->orderBy('name')
            ->get();

        // 3. Ambil Pengemudi yang 'tersedia' (ID-nya TIDAK ADA di dalam daftar yang tidak tersedia).
        $drivers = Driver::whereNotIn('id', $unavailableDriverIds)
            ->with('employee')
            ->get()
            ->sortBy('employee.name');

        // 4. Kirim data yang sudah difilter ke view.
        return view('pages.vehicle-logs.index', compact('vehicles', 'drivers'));
    }

    public function inspections()
    {
        return view('pages.inspections.index');
    }

    public function service_tickets()
    {
        // Nantinya kita akan buat file view ini
        return view('pages.vehicle-services.index');
    }

    // Halaman untuk menampilkan form input inspeksi baru
    public function create_inspection()
    {
        $inspectionItems = InspectionItem::where('is_active', true)->orderBy('id')->get();
        $inspectors = User::whereHas('employee.organization', function ($q) {
            $q->where('name', 'driver');
        })->orderBy('name')->get();

        // HAPUS SEMUA LOGIKA UNTUK MENGECEK INSPEKSI YANG SUDAH ADA.
        // Variabel $existingInspection dan $resultsByVehicle tidak lagi dibuat.

        // Logika untuk status bulanan (ikon checklist di accordion) bisa tetap dipertahankan
        // karena ini memberikan informasi yang berguna bagi petugas.
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastInspectionsThisMonth = DB::table('inspection_results as ir')
            ->join('inspections as i', 'ir.inspection_id', '=', 'i.id')
            ->select('ir.internal_vehicle_id', DB::raw('MAX(i.inspection_date) as last_date'))
            ->where('i.inspection_date', '>=', $startOfMonth)
            ->groupBy('ir.internal_vehicle_id')
            ->pluck('last_date', 'ir.internal_vehicle_id');

        $vehicles = InternalVehicle::orderBy('name')->get()->map(function ($vehicle) use ($lastInspectionsThisMonth) {
            $vehicle->inspection_status = $lastInspectionsThisMonth->has($vehicle->id) ? 'checked' : 'pending';
            return $vehicle;
        });

        // Kirim hanya data yang diperlukan untuk form KOSONG.
        return view('pages.inspections.create', compact(
            'vehicles',
            'inspectionItems',
            'inspectors'
        ));
    }
}
