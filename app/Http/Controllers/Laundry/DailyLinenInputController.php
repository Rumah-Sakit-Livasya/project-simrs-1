<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\DailyLinenInput;
use App\Models\Employee;
use App\Models\LinenCategory;
use App\Models\LinenType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DailyLinenInputController extends Controller
{
    /**
     * Menampilkan halaman dan menangani request DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DailyLinenInput::with(['linenType', 'linenCategory', 'picEmployee'])->latest();

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $picName = $row->picEmployee ? e($row->picEmployee->fullname) : '';
                    return '<a href="javascript:void(0)"
                            data-id="' . $row->id . '"
                            data-date="' . $row->date . '"
                            data-volume="' . $row->volume . '"
                            data-linen-type-id="' . $row->linen_type_id . '"
                            data-linen-category-id="' . $row->linen_category_id . '"
                            data-pic-id="' . $row->pic_id . '"
                            data-pic-name="' . $picName . '"
                            class="btn btn-primary btn-sm editLinen" title="Edit">
                            <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteLinen" title="Hapus">
                            <i class="fas fa-trash"></i>
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Ambil data master untuk form modal
        $linenTypes = LinenType::orderBy('name')->get();
        $linenCategories = LinenCategory::orderBy('name')->get();
        $keslingOrgId = \App\Models\Organization::where('name', 'like', '%sanitasi%')->value('id');

        return view('pages.laundry.index', compact('linenTypes', 'linenCategories', 'keslingOrgId'));
    }

    /**
     * Menyimpan atau memperbarui data batch.
     */
    public function storeOrUpdateBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.date' => 'required|date_format:Y-m-d',
            'items.*.linen_type_id' => 'required|exists:linen_types,id',
            'items.*.linen_category_id' => 'required|exists:linen_categories,id',
            'items.*.volume' => 'required|numeric|min:0',
            'items.*.pic_id' => 'required|exists:employees,id',
            'items.*.id' => 'nullable|exists:daily_linen_inputs,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                DailyLinenInput::updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    $item
                );
            }
            DB::commit();
            return response()->json(['success' => 'Data linen berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil satu data untuk form edit.
     */
    public function edit($id)
    {
        // Pastikan untuk mengambil relasi juga agar nama PIC bisa ditampilkan
        $data = DailyLinenInput::with('picEmployee')->findOrFail($id);
        return response()->json($data);
    }

    /**
     * Menghapus data.
     */
    public function destroy($id)
    {
        DailyLinenInput::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
