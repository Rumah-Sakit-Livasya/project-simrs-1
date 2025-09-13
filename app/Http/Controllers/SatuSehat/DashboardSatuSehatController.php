<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Departement;
use App\Models\Employee;
use App\Models\FhirLog; // Pastikan Anda membuat model ini
use Illuminate\Support\Facades\DB;

class DashboardSatuSehatController extends Controller
{
    public function index()
    {
        return view('pages.simrs.satu-sehat.dashboard');
    }

    public function getSummaryCards(Request $request)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'))->endOfDay();

        // 1. Hitung Total Registrasi (Ini sudah efisien)
        $registrationsQuery = Registration::whereBetween('created_at', [$startDate, $endDate]);
        $totalRegis = $registrationsQuery->clone()->count();
        $totalRajal = $registrationsQuery->clone()->where('jenis_registrasi', 'RAWAT JALAN')->count();
        $totalIgd = $registrationsQuery->clone()->where('jenis_registrasi', 'IGD')->count();
        $totalRanap = $registrationsQuery->clone()->where('jenis_registrasi', 'RAWAT INAP')->count();

        // 2. Hitung SEMUA data terkirim dalam SATU QUERY
        $sentSummary = FhirLog::where('is_success', 1)
            ->where('resource_type', 'Encounter')
            ->whereBetween('fhir_logs.created_at', [$startDate, $endDate])
            ->join('registrations', 'fhir_logs.registration_id', '=', 'registrations.id')
            ->select(
                DB::raw("COUNT(*) as total_terkirim"),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'RAWAT JALAN' THEN 1 ELSE 0 END) as total_rajal_terkirim"),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'IGD' THEN 1 ELSE 0 END) as total_igd_terkirim"),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'RAWAT INAP' THEN 1 ELSE 0 END) as total_ranap_terkirim")
            )
            ->first();

        return response()->json([
            'total_registrasi' => number_format($totalRegis),
            'total_terkirim' => number_format($sentSummary->total_terkirim ?? 0),
            'total_rajal' => number_format($totalRajal),
            'total_rajal_terkirim' => number_format($sentSummary->total_rajal_terkirim ?? 0),
            'total_igd' => number_format($totalIgd),
            'total_igd_terkirim' => number_format($sentSummary->total_igd_terkirim ?? 0),
            'total_ranap' => number_format($totalRanap),
            'total_ranap_terkirim' => number_format($sentSummary->total_ranap_terkirim ?? 0),
        ]);
    }

    public function getEncounterChart(Request $request)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'))->endOfDay();

        $period = Carbon::parse($startDate)->toPeriod($endDate);
        $labels = collect($period)->map(fn($date) => $date->format('d-m-Y'))->toArray();

        // Query data yang berhasil terkirim dari fhir_logs dengan join ke registrations
        $berhasilData = FhirLog::where('is_success', 1)
            ->where('resource_type', 'Encounter')
            ->whereBetween('fhir_logs.created_at', [$startDate, $endDate])
            ->join('registrations', 'fhir_logs.registration_id', '=', 'registrations.id')
            ->select(
                DB::raw('DATE(fhir_logs.created_at) as date'),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'RAWAT JALAN' THEN 1 ELSE 0 END) as rajal_count"),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'RAWAT INAP' THEN 1 ELSE 0 END) as ranap_count"),
                DB::raw("SUM(CASE WHEN registrations.jenis_registrasi = 'IGD' THEN 1 ELSE 0 END) as igd_count")
            )
            ->groupBy('date')->get()->keyBy(fn($item) => Carbon::parse($item->date)->format('d-m-Y'));

        $rajalBerhasil = [];
        $ranapBerhasil = [];
        $igdBerhasil = [];

        foreach ($labels as $label) {
            $rajalBerhasil[] = $berhasilData[$label]->rajal_count ?? 0;
            $ranapBerhasil[] = $berhasilData[$label]->ranap_count ?? 0;
            $igdBerhasil[] = $berhasilData[$label]->igd_count ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Rawat Jalan [Berhasil]', 'data' => $rajalBerhasil, 'borderColor' => '#7cb5ec', 'backgroundColor' => 'rgba(124, 181, 236, 0.2)'],
                ['label' => 'Rawat Inap [Berhasil]', 'data' => $ranapBerhasil, 'borderColor' => '#8085e9', 'backgroundColor' => 'rgba(128, 133, 233, 0.2)'],
                ['label' => 'IGD [Berhasil]', 'data' => $igdBerhasil, 'borderColor' => '#90ed7d', 'backgroundColor' => 'rgba(144, 237, 125, 0.2)'],
            ]
        ]);
    }

    public function getFhirResourceSummary(Request $request)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'))->endOfDay();

        $summary = FhirLog::where('is_success', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('resource_type', DB::raw('count(*) as total'))
            ->groupBy('resource_type')
            ->pluck('total', 'resource_type');

        return response()->json($summary);
    }

    public function getMasterDataChart(Request $request)
    {
        $deptTotal = Departement::count();
        $deptMappedOrg = Departement::whereNotNull('satu_sehat_organization_id')->count();
        $deptMappedLoc = Departement::whereNotNull('satu_sehat_location_id')->count();

        $nakesTotal = Employee::where('is_active', 1)->count();
        $nakesMapped = Employee::whereNotNull('satu_sehat_practitioner_id')->count();

        $data = [
            ['tipe_data' => 'Department (Org)', 'total_data' => $deptTotal, 'total_mapping' => $deptMappedOrg],
            ['tipe_data' => 'Lokasi (Dept)', 'total_data' => $deptTotal, 'total_mapping' => $deptMappedLoc],
            ['tipe_data' => 'Tenaga Kesehatan', 'total_data' => $nakesTotal, 'total_mapping' => $nakesMapped],
        ];

        $categories = collect($data)->pluck('tipe_data');
        $totalData = collect($data)->pluck('total_data');
        $totalMapping = collect($data)->pluck('total_mapping');
        $totalMappingBelum = $totalData->map(fn($total, $i) => $total - $totalMapping[$i]);

        return response()->json([
            'categories' => $categories,
            'series' => [
                ['name' => 'Total Data', 'data' => $totalData],
                ['name' => 'Total Sudah Mapping', 'data' => $totalMapping, 'color' => '#90ed7d'],
                ['name' => 'Total Belum Mapping', 'data' => $totalMappingBelum, 'color' => '#D04848']
            ]
        ]);
    }

    public function getMappingLogTable(Request $request)
    {
        // Ambil parameter filter dari request
        $type = $request->input('type', 'department');
        $status = $request->input('status'); // 'sukses' atau 'gagal'
        $keyword = $request->input('keyword');

        // Logika untuk mengambil data berdasarkan tipe
        switch ($type) {
            case 'department':
                $query = Departement::query();
                if ($status === 'sukses') {
                    $query->whereNotNull('satu_sehat_organization_id');
                } elseif ($status === 'gagal') {
                    $query->whereNull('satu_sehat_organization_id');
                }
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")->orWhere('kode', 'like', "%{$keyword}%");
                }
                $data = $query->get()->map(fn($item) => [
                    'detail' => "[{$item->kode}] {$item->name}",
                    'status' => $item->satu_sehat_organization_id ? 'Berhasil' : 'Gagal',
                    'hasil' => "ID Mapping: {$item->satu_sehat_organization_id}"
                ]);
                break;

            case 'loc_department':
                $query = Departement::query();
                if ($status === 'sukses') {
                    $query->whereNotNull('satu_sehat_location_id');
                } elseif ($status === 'gagal') {
                    $query->whereNull('satu_sehat_location_id');
                }
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")->orWhere('kode', 'like', "%{$keyword}%");
                }
                $data = $query->get()->map(fn($item) => [
                    'detail' => "[{$item->kode}] {$item->name}",
                    'status' => $item->satu_sehat_location_id ? 'Berhasil' : 'Gagal',
                    'hasil' => "ID Mapping: {$item->satu_sehat_location_id}"
                ]);
                break;

            case 'nakes':
                $query = Employee::where('is_active', 1);
                if ($status === 'sukses') {
                    $query->whereNotNull('satu_sehat_practitioner_id');
                } elseif ($status === 'gagal') {
                    $query->whereNull('satu_sehat_practitioner_id');
                }
                if ($keyword) {
                    $query->where('fullname', 'like', "%{$keyword}%");
                }
                $data = $query->get()->map(fn($item) => [
                    'detail' => "{$item->fullname}",
                    'status' => $item->satu_sehat_practitioner_id ? 'Berhasil' : 'Gagal',
                    'hasil' => "ID Nakes: {$item->satu_sehat_practitioner_id}"
                ]);
                break;

            default:
                $data = collect([]); // Return data kosong untuk tipe lain
                break;
        }

        return response()->json(['data' => $data]);
    }
}
