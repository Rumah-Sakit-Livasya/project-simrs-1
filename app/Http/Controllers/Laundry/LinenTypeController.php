<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\LinenType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LinenTypeController extends Controller
{
    /**
     * Menampilkan halaman utama dan menangani request DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LinenType::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" data-name="' . e($row->name) . '" class="btn btn-primary btn-sm editLinenType" title="Edit"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteLinenType" title="Hapus"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.laundry.master.linen-type.index');
    }

    /**
     * Menyimpan data baru atau memperbarui data yang ada.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            // unique:table,column,except,idColumn
            'name' => 'required|string|max:255|unique:linen_types,name,' . $request->id,
        ]);

        // Gunakan updateOrCreate untuk handle Create dan Update sekaligus
        $linenType = LinenType::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name]
        );

        return response()->json(['success' => 'Data Jenis Linen berhasil disimpan.', 'data' => $linenType]);
    }

    /**
     * Menghapus data.
     * Kita menggunakan route-model binding, jadi $linenType sudah merupakan instance model.
     */
    public function destroy(LinenType $linenType)
    {
        try {
            $linenType->delete();
            return response()->json(['success' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            // Menangani error jika jenis linen masih digunakan di tabel lain (foreign key constraint)
            return response()->json(['error' => 'Data tidak dapat dihapus karena masih digunakan.'], 422);
        }
    }
}
