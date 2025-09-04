<?php

// app/Http/Controllers/Api/NursingDiagnosisController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NursingDiagnosis;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NursingDiagnosisController extends Controller
{
    // app/Http/Controllers/Api/NursingDiagnosisController.php

    public function index(Request $request) // Tambahkan Request $request
    {
        // Gunakan 'with' untuk eager loading relasi
        $data = NursingDiagnosis::with('category');

        // Tambahkan logika pencarian kustom
        if ($request->filled('search_query')) {
            $data->where('diagnosa', 'like', '%' . $request->search_query . '%');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            // Kita tidak lagi butuh kolom 'action' dari backend, karena dibuat di frontend
            // ->addColumn('action', function ($row) { ... })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:diagnosis_categories,id',
            'code' => 'required|string|unique:nursing_diagnoses,code',
            'diagnosa' => 'required|string',
        ]);
        NursingDiagnosis::create($request->all());
        return response()->json(['success' => 'Diagnosa berhasil dibuat.']);
    }

    public function edit($id)
    {
        // Kirim juga data relasi untuk Select2
        $diagnosis = NursingDiagnosis::with('category')->findOrFail($id);
        return response()->json($diagnosis);
    }

    public function update(Request $request, $id)
    {
        $diagnosis = NursingDiagnosis::findOrFail($id);
        $request->validate([
            'category_id' => 'required|exists:diagnosis_categories,id',
            'code' => 'required|string|unique:nursing_diagnoses,code,' . $id,
            'diagnosa' => 'required|string',
        ]);
        $diagnosis->update($request->all());
        return response()->json(['success' => 'Diagnosa berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        NursingDiagnosis::findOrFail($id)->delete();
        return response()->json(['success' => 'Diagnosa berhasil dihapus.']);
    }
}
