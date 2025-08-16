<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DailyWasteInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DailyWasteInputController extends Controller
{
    /**
     * Get the summary of today's waste volume.
     */
    public function getTodaySummary(Request $request)
    {
        $totalVolume = DailyWasteInput::whereDate('date', now()->toDateString())
            ->sum('volume');

        return response()->json([
            'total_today' => (float) $totalVolume
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DailyWasteInput::with(['wasteCategory', 'employee'])->latest();

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('pic_name', fn($row) => $row->employee ? $row->employee->fullname : 'N/A')
                ->addColumn('action', function ($row) {
                    $picName = $row->employee ? e($row->employee->fullname) : '';

                    // --- PERBAIKAN UTAMA DI SINI ---
                    // Mengubah snake_case (data-category_id) menjadi kebab-case (data-category-id)
                    return '<a href="javascript:void(0)"
                               data-id="' . $row->id . '"
                               data-date="' . $row->date . '"
                               data-volume="' . $row->volume . '"
                               data-category-id="' . $row->waste_category_id . '"
                               data-pic-id="' . $row->pic . '"
                               data-pic-name="' . $picName . '"
                               class="btn btn-primary btn-sm editDaily" title="Edit">
                                   <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteDaily" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.date' => 'required|date',
            'items.*.waste_category_id' => 'required|exists:waste_categories,id',
            'items.*.volume' => 'required|numeric|min:0',
            // Validasi 'pic' sebagai ID yang ada di tabel employees
            'items.*.pic' => 'required|exists:employees,id',
            'items.*.id' => 'nullable|exists:daily_waste_inputs,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                DailyWasteInput::updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'date' => $item['date'],
                        'waste_category_id' => $item['waste_category_id'],
                        'volume' => $item['volume'],
                        // Simpan ID employee ke kolom 'pic'
                        'pic' => $item['pic'],
                    ]
                );
            }
            DB::commit();
            return response()->json(['success' => 'All items saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred.', 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     * Fungsi ini tidak lagi diperlukan untuk edit, karena data sudah dikirim melalui data-attributes
     * Namun, biarkan saja jika ada keperluan lain.
     */
    public function show($id)
    {
        $dailyInput = DailyWasteInput::with('wasteCategory')->find($id);
        return response()->json($dailyInput);
    }

    // ... (Fungsi destroy tetap sama) ...
    public function destroy($id)
    {
        DailyWasteInput::find($id)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }

    public function getChartData()
    {
        $data = DailyWasteInput::select(
            DB::raw('DATE(date) as day'),
            DB::raw('SUM(volume) as total_volume')
        )
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->get();

        return response()->json($data);
    }
}
