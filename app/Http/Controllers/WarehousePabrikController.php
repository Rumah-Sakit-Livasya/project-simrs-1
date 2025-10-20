<?php

namespace App\Http\Controllers;

use App\Exports\WarehousePabrikExport;
use App\Imports\WarehousePabrikImport;
use App\Models\WarehousePabrik;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class WarehousePabrikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Simply return the view. Data will be fetched by DataTables AJAX.
        return view("pages.simrs.warehouse.master-data.pabrik");
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $query = WarehousePabrik::query();
        return DataTables::of($query)
            ->addColumn('status_label', function ($row) {
                return $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-primary btn-sm btn-icon waves-effect waves-themed edit-btn" data-id="' . $row->id . '" title="Edit"><i class="fal fa-edit"></i></button>';
                $deleteBtn = '<button class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-id="' . $row->id . '" title="Delete"><i class="fal fa-trash"></i></button>';
                return '<div class="btn-group">' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['status_label', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        WarehousePabrik::create($validatedData);
        return response()->json(['success' => 'Pabrik berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pabrik = WarehousePabrik::find($id);

        if (! $pabrik) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pabrik->id,
                'nama' => $pabrik->nama,
                'alamat' => $pabrik->alamat,
                'telp' => $pabrik->telp,
                'contact_person' => $pabrik->contact_person,
                'contact_person_phone' => $pabrik->contact_person_phone,
                'aktif' => $pabrik->aktif,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pabrik = WarehousePabrik::findOrFail($id);
        return response()->json($pabrik);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $pabrik = WarehousePabrik::findOrFail($id);
        $pabrik->update($validatedData);

        return response()->json(['success' => 'Pabrik berhasil diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            WarehousePabrik::destroy($id);
            return response()->json(['success' => 'Pabrik berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Export data to Excel.
     */
    public function export()
    {
        return Excel::download(new WarehousePabrikExport, 'warehouse_pabrik.xlsx');
    }

    /**
     * Import data from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\WarehousePabrikImport, $request->file('file'));
            \Illuminate\Support\Facades\Log::info('Import WarehousePabrik berhasil', [
                'user_id' => auth()?->id(),
                'filename' => $request->file('file')->getClientOriginalName(),
            ]);
            return redirect()->back()->with('success', 'Data pabrik berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorString = '';
            foreach ($failures as $failure) {
                $errorString .= 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '. ';
            }
            \Illuminate\Support\Facades\Log::error('Gagal impor WarehousePabrik (validasi)', [
                'user_id' => auth()?->id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'errors' => $errorString,
            ]);
            return redirect()->back()->with('error', 'Gagal impor: ' . $errorString);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal impor WarehousePabrik (exception)', [
                'user_id' => auth()?->id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'exception' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
