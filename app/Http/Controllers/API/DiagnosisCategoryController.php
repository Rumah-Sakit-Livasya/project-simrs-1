<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DiagnosisCategoryController extends Controller
{
    // Menampilkan data untuk Datatables
    public function index()
    {
        $data = DiagnosisCategory::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn">Edit</a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn">Hapus</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DiagnosisCategory::create($request->all());
        return response()->json(['success' => 'Kategori berhasil dibuat.']);
    }

    // Mengambil data untuk diedit
    public function edit($id)
    {
        $category = DiagnosisCategory::findOrFail($id);
        return response()->json($category);
    }

    // Memperbarui data
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = DiagnosisCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json(['success' => 'Kategori berhasil diperbarui.']);
    }

    // Menghapus data
    public function destroy($id)
    {
        DiagnosisCategory::findOrFail($id)->delete();
        return response()->json(['success' => 'Kategori berhasil dihapus.']);
    }

    // Endpoint untuk Select2
    public function selectAll()
    {
        $categories = DiagnosisCategory::select('id', 'name as text')->get();
        return response()->json($categories);
    }
}
