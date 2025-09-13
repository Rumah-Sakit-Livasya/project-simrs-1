<?php

namespace App\Http\Controllers\SatuSehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
// Import model-model yang relevan
use App\Models\SIMRS\Registration; // Ganti jika path model berbeda
use App\Models\SIMRS\Departement;  // Ganti jika path model berbeda
use App\Models\Employee;            // Ganti jika path model berbeda

class DashboardSatuSehatController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard.
     */
    public function index()
    {
        // Pastikan path view ini benar sesuai struktur folder Anda
        return view('pages.simrs.satu-sehat.dashboard');
    }

    /**
     * Mengambil data untuk summary cards.
     */
    public function getSummaryCards(Request $request)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'))->endOfDay();

        // GANTI DENGAN LOGIKA QUERY NYATA ANDA
        // Ini adalah contoh data statis berdasarkan screenshot Anda
        $data = [
            'total_registrasi' => number_format(2394),
            'total_terkirim' => number_format(211),
            'total_rajal' => number_format(1512),
            'total_rajal_terkirim' => number_format(137),
            'total_igd' => number_format(438),
            'total_igd_terkirim' => number_format(0),
            'total_ranap' => number_format(444),
            'total_ranap_terkirim' => number_format(74),
        ];

        return response()->json($data);
    }

    /**
     * Mengambil data untuk chart Encounter (kunjungan).
     */
    public function getEncounterChart(Request $request)
    {
        // GANTI DENGAN LOGIKA QUERY NYATA ANDA
        // Anda perlu query log FHIR Anda dan mengelompokkannya per tanggal

        // Contoh data statis
        $labels = ['01-09-2025', '02-09-2025', '03-09-2025', '04-09-2025', '05-09-2025', '06-09-2025', '07-09-2025', '08-09-2025', '09-09-2025', '10-09-2025', '11-09-2025', '12-09-2025', '13-09-2025'];
        $rajalBerhasil = [10, 12, 15, 11, 14, 13, 15, 9, 11, 12, 11, 14, 10];
        $ranapBerhasil = [5, 6, 8, 5, 7, 7, 8, 6, 5, 6, 7, 8, 6];
        $igdBerhasil = [2, 1, 3, 2, 1, 1, 2, 3, 2, 1, 2, 3, 2];

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Rawat Jalan [Berhasil]', 'data' => $rajalBerhasil, 'borderColor' => '#7cb5ec', 'backgroundColor' => 'rgba(124, 181, 236, 0.2)'],
                ['label' => 'Rawat Inap [Berhasil]', 'data' => $ranapBerhasil, 'borderColor' => '#8085e9', 'backgroundColor' => 'rgba(128, 133, 233, 0.2)'],
                ['label' => 'IGD [Berhasil]', 'data' => $igdBerhasil, 'borderColor' => '#90ed7d', 'backgroundColor' => 'rgba(144, 237, 125, 0.2)'],
            ]
        ]);
    }

    /**
     * Mengambil data untuk chart Master Data.
     */
    public function getMasterDataChart(Request $request)
    {
        // GANTI DENGAN LOGIKA QUERY NYATA ANDA
        $deptTotal = Departement::count();
        $deptMappedOrg = Departement::whereNotNull('satu_sehat_organization_id')->count();
        $deptMappedLoc = Departement::whereNotNull('satu_sehat_location_id')->count();

        $nakesTotal = Employee::where('is_active', 1)->count();
        $nakesMapped = Employee::whereNotNull('satu_sehat_practitioner_id')->count();

        $data = [
            ['tipe_data' => 'Department (Org)', 'total_data' => $deptTotal, 'total_mapping' => $deptMappedOrg],
            ['tipe_data' => 'Lokasi (Dept)', 'total_data' => $deptTotal, 'total_mapping' => $deptMappedLoc],
            ['tipe_data' => 'Tenaga Kesehatan', 'total_data' => $nakesTotal, 'total_mapping' => $nakesMapped],
            // Tambahkan data master lainnya di sini
            ['tipe_data' => 'Obat', 'total_data' => 1677, 'total_mapping' => 48],
            ['tipe_data' => 'Alat Kesehatan', 'total_data' => 774, 'total_mapping' => 10],
        ];

        return response()->json($data);
    }
}
