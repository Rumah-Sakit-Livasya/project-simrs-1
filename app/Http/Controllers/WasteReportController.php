<?php

namespace App\Http\Controllers;

use App\Models\DailyLinenInput;
use App\Models\DailyWasteInput;
use App\Models\Employee;
use App\Models\LinenCategory;
use App\Models\LinenType;
use App\Models\WasteCategory;
use App\Models\WasteTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WasteReportController extends Controller
{
    /**
     * Menampilkan halaman utama laporan pengelolaan limbah.
     */
    public function index()
    {
        // Ambil hanya kategori "Domestik" dan "Infeksius" untuk filter dropdown
        $wasteCategories = WasteCategory::whereIn('name', ['Domestik', 'Infeksius'])->orderBy('name')->get();
        return view('pages.waste-transport.report', compact('wasteCategories'));
    }

    /**
     * Mengambil dan mengagregasi data pengangkutan untuk laporan via AJAX.
     */
    public function getWasteData(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
            'category_id' => 'nullable|integer|exists:waste_categories,id',
        ]);

        // Mulai membangun query
        $query = WasteTransport::query()
            ->join('waste_categories', 'waste_transports.waste_category_id', '=', 'waste_categories.id')
            ->select(
                'waste_categories.name as category_name',
                DB::raw('SUM(waste_transports.volume) as total_volume'),
                DB::raw('COUNT(waste_transports.id) as transport_count')
            );

        // Terapkan filter tanggal jika ada
        if ($request->filled('start_date')) {
            $query->where('waste_transports.date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('waste_transports.date', '<=', $request->end_date);
        }

        // Terapkan filter kategori jika ada
        if ($request->filled('category_id')) {
            $query->where('waste_transports.waste_category_id', $request->category_id);
        }

        // Kelompokkan hasil berdasarkan kategori
        $data = $query->groupBy('waste_categories.id', 'waste_categories.name')
            ->orderBy('waste_categories.name')
            ->get();

        $dataWithRowIndex = $data->map(function ($item, $key) {
            $item['DT_RowIndex'] = $key + 1; // Tambahkan properti DT_RowIndex
            return $item;
        });

        return response()->json(['data' => $dataWithRowIndex]);
    }

    public function dailyIndex()
    {
        $wasteCategories = WasteCategory::orderBy('name')->get();
        // Anda mungkin ingin mengambil daftar karyawan (PIC) juga untuk filter
        // Jika terlalu banyak, biarkan filter PIC menggunakan AJAX search
        $pics = Employee::where('organization_id', 22)->orderBy('fullname')->get(); // Ganti 22 dengan ID organisasi yang relevan

        return view('pages.daily-waste.report', compact('wasteCategories', 'pics'));
    }

    // ==========================================================
    // === METHOD BARU UNTUK MENGAMBIL DATA LAPORAN VIA AJAX ===
    // ==========================================================
    public function getDailyWasteData(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
            'category_id' => 'nullable|integer|exists:waste_categories,id',
            'pic_id'     => 'nullable|integer|exists:employees,id',
        ]);

        // Mulai membangun query
        $query = DailyWasteInput::query()
            ->join('waste_categories', 'daily_waste_inputs.waste_category_id', '=', 'waste_categories.id')
            ->join('employees', 'daily_waste_inputs.pic', '=', 'employees.id')
            ->select(
                'waste_categories.name as category_name',
                'employees.fullname as pic_name',
                DB::raw('SUM(daily_waste_inputs.volume) as total_volume'),
                DB::raw('COUNT(daily_waste_inputs.id) as input_count')
            );

        // Terapkan filter
        if ($request->filled('start_date')) {
            $query->where('daily_waste_inputs.date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('daily_waste_inputs.date', '<=', $request->end_date);
        }
        if ($request->filled('category_id')) {
            $query->where('daily_waste_inputs.waste_category_id', $request->category_id);
        }
        if ($request->filled('pic_id')) {
            $query->where('daily_waste_inputs.pic', $request->pic_id);
        }

        // Kelompokkan hasil berdasarkan kategori dan PIC
        $data = $query->groupBy('waste_categories.id', 'waste_categories.name', 'employees.id', 'employees.fullname')
            ->orderBy('waste_categories.name')
            ->orderBy('employees.fullname')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function laundryIndex()
    {
        $linenTypes = LinenType::orderBy('name')->get();
        $linenCategories = LinenCategory::orderBy('name')->get();
        // Ganti 22 dengan ID organisasi Kesling yang relevan
        $pics = Employee::where('organization_id', 22)->orderBy('fullname')->get();

        return view('pages.laundry.report', compact('linenTypes', 'linenCategories', 'pics'));
    }

    public function getLaundryData(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
            'linen_type_id' => 'nullable|integer|exists:linen_types,id',
            'linen_category_id' => 'nullable|integer|exists:linen_categories,id',
            'pic_id'     => 'nullable|integer|exists:employees,id',
        ]);

        $query = DailyLinenInput::query()
            ->join('linen_types', 'daily_linen_inputs.linen_type_id', '=', 'linen_types.id')
            ->join('linen_categories', 'daily_linen_inputs.linen_category_id', '=', 'linen_categories.id')
            ->join('employees', 'daily_linen_inputs.pic_id', '=', 'employees.id')
            ->select(
                'linen_types.name as linen_type_name',
                'linen_categories.name as linen_category_name',
                'employees.fullname as pic_name',
                DB::raw('SUM(daily_linen_inputs.volume) as total_volume'),
                DB::raw('COUNT(daily_linen_inputs.id) as input_count')
            );

        // Terapkan filter
        if ($request->filled('start_date')) $query->where('daily_linen_inputs.date', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->where('daily_linen_inputs.date', '<=', $request->end_date);
        if ($request->filled('linen_type_id')) $query->where('daily_linen_inputs.linen_type_id', $request->linen_type_id);
        if ($request->filled('linen_category_id')) $query->where('daily_linen_inputs.linen_category_id', $request->linen_category_id);
        if ($request->filled('pic_id')) $query->where('daily_linen_inputs.pic_id', $request->pic_id);

        $data = $query->groupBy('linen_type_name', 'linen_category_name', 'pic_name')
            ->orderBy('linen_type_name')
            ->get();

        return response()->json(['data' => $data]);
    }
}
