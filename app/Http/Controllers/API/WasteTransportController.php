<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WasteTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WasteTransportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mulai query builder
            $query = WasteTransport::with(['wasteCategory', 'vehicle'])->latest();

            // Tambahkan filter tanggal jika ada
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Tambahkan filter kategori limbah jika ada
            if ($request->filled('waste_category_id')) {
                $query->where('waste_category_id', $request->waste_category_id);
            }

            // Tambahkan filter kendaraan jika ada
            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }

            // Tambahkan filter PIC jika ada
            if ($request->filled('pic')) {
                $query->where('pic', $request->pic);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm editTransport" title="Edit"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteTransport" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        WasteTransport::updateOrCreate(
            ['id' => $request->id],
            [
                'date' => $request->date,
                'waste_category_id' => $request->waste_category_id,
                'vehicle_id' => $request->vehicle_id,
                'volume' => $request->volume,
                'pic' => $request->pic,
            ]
        );
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function storeOrUpdateBatch(Request $request)
    {
        // 1. Validasi data yang masuk
        //    Kita memvalidasi bahwa 'items' adalah sebuah array dan setiap elemen di dalamnya memiliki field yang diperlukan.
        $validator = Validator::make($request->all(), [
            'items'                 => 'required|array',
            'items.*.date'          => 'required|date_format:Y-m-d',
            'items.*.waste_category_id' => 'required|integer|exists:waste_categories,id',
            'items.*.vehicle_id'    => 'required|integer|exists:vehicles,id',
            'items.*.volume'        => 'required|numeric|min:0',
            'items.*.pic'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $items = $request->input('items', []);

            // 2. Loop melalui setiap item dan simpan ke database
            foreach ($items as $itemData) {
                WasteTransport::updateOrCreate(
                    // Kunci untuk mencari data yang ada (berdasarkan 'id' jika ada)
                    ['id' => $itemData['id'] ?? null],
                    // Data yang akan disimpan atau diperbarui
                    [
                        'date' => $itemData['date'],
                        'waste_category_id' => $itemData['waste_category_id'],
                        'vehicle_id' => $itemData['vehicle_id'],
                        'volume' => $itemData['volume'],
                        'pic' => $itemData['pic'],
                    ]
                );
            }

            // 3. Kirim respons sukses jika semua berhasil
            return response()->json(['success' => 'Semua data pengangkutan berhasil disimpan!']);
        } catch (\Exception $e) {
            // Tangani jika terjadi error pada database
            return response()->json(['error' => 'Terjadi kesalahan pada server: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $transport = WasteTransport::find($id);
        return response()->json($transport);
    }

    public function destroy($id)
    {
        WasteTransport::find($id)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }
}
