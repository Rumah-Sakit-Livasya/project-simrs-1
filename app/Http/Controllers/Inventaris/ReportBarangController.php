<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\ReportBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportBarangController extends Controller
{
    public function index()
    {
        return view('pages.inventaris.report-barang.index', [
            'rooms' => RoomMaintenance::all(),
            'category' => CategoryBarang::all(),
            'template' => TemplateBarang::all(),
            'barang' => Barang::all(),
            'users' => User::all(),
            'reports' => ReportBarang::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function laporanBulanan(Request $request)
    {
        // Get the selected month and year from the request
        $month = $request->input('month', date('n')); // Default to current month
        $year = $request->input('year', date('Y')); // Default to current year

        // Menghitung total maintenance per kategori dengan filter bulan dan tahun
        $totalMaintenance = Barang::select('category_barang.name as category_name', DB::raw('count(maintenance_barang.id) as total_maintenance'))
            ->leftJoin('maintenance_barang', 'barang.id', '=', 'maintenance_barang.barang_id')
            ->leftJoin('category_barang', 'barang.category_barang_id', '=', 'category_barang.id')
            ->whereYear('maintenance_barang.created_at', $year)
            ->whereMonth('maintenance_barang.created_at', $month)
            ->groupBy('category_barang.name')
            ->get();

        // Inisialisasi array untuk menyimpan total maintenance per kategori
        $totalPerbaikanAlat = [];

        // Memisahkan hasil menjadi variabel dinamis berdasarkan kategori
        foreach ($totalMaintenance as $item) {
            $totalPerbaikanAlat[$item->category_name] = $item->total_maintenance;
        }

        // Mengambil semua kategori dari category_barang untuk memastikan semua kategori ada
        $kategoriBarang = DB::table('category_barang')->pluck('name')->toArray();

        // Inisialisasi variabel untuk kategori yang tidak ada dalam total maintenance
        foreach ($kategoriBarang as $kategori) {
            if (!isset($totalPerbaikanAlat[$kategori])) {
                $totalPerbaikanAlat[$kategori] = 0; // Set ke 0 jika tidak ada maintenance
            }
        }

        // Menghitung jumlah alat berdasarkan kategori
        $alatMedis = Barang::where('category_barang_id', 2)->count();
        $alatNonmedis = Barang::where('category_barang_id', 1)->count();
        $alatIT = Barang::where('category_barang_id', 3)->count();

        // Menghitung perbaikan berdasarkan kategori
        $perbaikanAlatMedis = isset($totalPerbaikanAlat['MEDIS']) ? $totalPerbaikanAlat['MEDIS'] : 0;
        $perbaikanAlatNonMedis = isset($totalPerbaikanAlat['NON MEDIS']) ? $totalPerbaikanAlat['NON MEDIS'] : 0;
        $perbaikanAlatIT = isset($totalPerbaikanAlat['IT']) ? $totalPerbaikanAlat['IT'] : 0;

        // Menghitung total alat
        $totalAlat = $alatMedis + $alatNonmedis + $alatIT;

        // Menghitung total tidak berfungsi, perbaikan, dan tidak tersedia
        $alatMedisTidakBerfungsi = 0; // Ganti dengan logika yang sesuai
        $alatNonmedisTidakBerfungsi = 0; // Ganti dengan logika yang sesuai

        // Mengambil alat Medis yang Pending dengan filter bulan dan tahun
        $perbaikanAlatMedisPending = Barang::where(
            'category_barang_id',
            2
        )
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->whereIn('status', [
                    'menunggu-sparepart',
                    'dalam-proses',
                    'ditunda',
                    'diperlukan-persetujuan'
                ])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        // Mengambil alat Non Medis yang Pending dengan filter bulan dan tahun
        $perbaikanAlatNonMedisPending = Barang::where(
            'category_barang_id',
            1
        )
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->whereIn('status', [
                    'menunggu-sparepart',
                    'dalam-proses',
                    'ditunda',
                    'diperlukan-persetujuan'
                ])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        // Mengambil alat IT yang Pending dengan filter bulan dan tahun
        $perbaikanAlatITPending = Barang::where(
            'category_barang_id',
            3
        )
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->whereIn('status', [
                    'menunggu-sparepart',
                    'dalam-proses',
                    'ditunda',
                    'diperlukan-persetujuan'
                ])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        // Mengambil alat Medis yang tidak dapat diperbaiki dengan filter bulan dan tahun
        $alatMedisTidakTersedia = Barang::where('category_barang_id', 2)
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->where('status', 'tidak-dapat-diperbaiki')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        // Mengambil alat NonMedis yang tidak dapat diperbaiki dengan filter bulan dan tahun
        $alatNonMedisTidakTersedia = Barang::where('category_barang_id', 1)
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->where('status', 'tidak-dapat-diperbaiki')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        // Mengambil alat IT yang tidak dapat diperbaiki dengan filter bulan dan tahun
        $alatITTidakTersedia = Barang::where('category_barang_id', 3)
            ->whereHas('maintenance', function ($query) use ($month, $year) {
                $query->where('status', 'tidak-dapat-diperbaiki')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->count();

        $alatITTidakBerfungsi = 0; // Ganti dengan logika yang sesuai

        $totalTidakBerfungsi = $alatMedisTidakBerfungsi + $alatNonmedisTidakBerfungsi + $alatITTidakBerfungsi;
        $totalPerbaikan = $perbaikanAlatMedis + $perbaikanAlatNonMedis + $perbaikanAlatIT;
        $totalTidakTersedia = $alatMedisTidakTersedia + $alatNonMedisTidakTersedia + $alatITTidakTersedia;

        return view('pages.inventaris.report-barang.laporan-bulanan', compact(
            'alatMedis',
            'alatMedisTidakBerfungsi',
            'alatNonmedisTidakBerfungsi',
            'alatMedisTidakTersedia',
            'alatNonmedis',
            'perbaikanAlatMedis',
            'alatNonMedisTidakTersedia',
            'alatIT',
            'alatITTidakBerfungsi',
            'perbaikanAlatIT',
            'perbaikanAlatITPending',
            'perbaikanAlatNonMedis',
            'perbaikanAlatNonMedisPending',
            'perbaikanAlatMedisPending',
            'alatITTidakTersedia',
            'totalAlat',
            'totalTidakBerfungsi',
            'totalPerbaikan',
            'totalTidakTersedia'
        ));
    }

    public function getMaintenanceData(Request $request)
    {
        $category = $request->input('category');
        $month = $request->input('month');
        $year = $request->input('year');

        // Determine the category ID based on the input category
        $categoryId = null;
        switch (strtoupper($category)) {
            case 'MEDIS':
                $categoryId = 2;
                break;
            case 'NON MEDIS':
                $categoryId = 1;
                break;
            case 'IT':
                $categoryId = 3;
                break;
            default:
                return response()->json(['error' => 'Invalid category'], 400);
        }

        // Fetch maintenance data based on category, month, and year
        $maintenanceData = DB::table('maintenance_barang')
            ->join('barang', 'maintenance_barang.barang_id', '=', 'barang.id')
            ->leftJoin('template_barang', 'barang.template_barang_id', '=', 'template_barang.id')
            ->where('barang.category_barang_id', $categoryId) // Directly use the category ID
            ->whereYear('maintenance_barang.created_at', $year)
            ->whereMonth('maintenance_barang.created_at', $month)
            ->select(
                'maintenance_barang.id',
                DB::raw('COALESCE(barang.custom_name, template_barang.name) as nama_barang'),
                'maintenance_barang.kondisi',
                'maintenance_barang.hasil',
                'maintenance_barang.tanggal',
                'maintenance_barang.estimasi',
                'maintenance_barang.keterangan',
                'maintenance_barang.rtl',
                'maintenance_barang.foto',
                'maintenance_barang.status',
                'maintenance_barang.created_at'
            )
            ->get();

        return response()->json($maintenanceData);
    }
}
