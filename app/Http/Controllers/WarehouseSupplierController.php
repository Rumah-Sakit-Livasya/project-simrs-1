<?php

namespace App\Http\Controllers;

use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WarehouseSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.master-data.supplier");
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $data = WarehouseSupplier::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse.master-data.supplier.show', $row->id);
                $deleteUrl = route('warehouse.master-data.supplier.destroy', $row->id);
                $actionBtn = '<div class="d-flex justify-content-center">';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit-btn" data-url="' . $editUrl . '"><i class="fal fa-pencil"></i> Edit</a> ';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-url="' . $deleteUrl . '"><i class="fal fa-trash"></i> Hapus</a>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255|unique:warehouse_supplier,nama',
            'kategori' => 'required|in:FARMASI,UMUM',
            'alamat' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:50',
            'contact_person_email' => 'nullable|email|max:255',
            'no_rek' => 'nullable|string|max:100',
            'bank' => 'nullable|string|max:100',
            'top' => 'nullable|string|max:20',
            'tipe_top' => 'nullable|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG',
            'ppn' => 'required|numeric|min:0',
            'aktif' => 'required|boolean', // Field ini bernama 'aktif' dari form
        ];

        $messages = [
            'nama.required' => 'Nama supplier wajib diisi.',
            'nama.unique' => 'Nama supplier sudah terdaftar, silakan gunakan nama lain.',
            'kategori.required' => 'Kategori supplier wajib dipilih.',
            'email.email' => 'Format email perusahaan tidak valid.',
            'contact_person_email.email' => 'Format email contact person tidak valid.',
            'ppn.required' => 'Nilai PPN wajib diisi.',
            'ppn.numeric' => 'PPN harus berupa angka.',
            'tipe_top.in' => 'Tipe TOP tidak valid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            WarehouseSupplier::create($validator->validated());
            return response()->json(['success' => true, 'message' => 'Supplier berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Error storing supplier: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supplier = WarehouseSupplier::find($id);
        if (!$supplier) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required|string|max:255|unique:warehouse_supplier,nama,' . $id,
            'kategori' => 'required|in:FARMASI,UMUM',
            'alamat' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:50',
            'contact_person_email' => 'nullable|email|max:255',
            'no_rek' => 'nullable|string|max:100',
            'bank' => 'nullable|string|max:100',
            'top' => 'nullable|string|max:20',
            'tipe_top' => 'nullable|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG',
            'ppn' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
        ];

        $messages = [
            'nama.required' => 'Nama supplier wajib diisi.',
            'nama.unique' => 'Nama supplier sudah terdaftar, silakan gunakan nama lain.',
            'kategori.required' => 'Kategori supplier wajib dipilih.',
            'email.email' => 'Format email perusahaan tidak valid.',
            'contact_person_email.email' => 'Format email contact person tidak valid.',
            'ppn.required' => 'Nilai PPN wajib diisi.',
            'ppn.numeric' => 'PPN harus berupa angka.',
            'tipe_top.in' => 'Tipe TOP tidak valid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $supplier = WarehouseSupplier::findOrFail($id);
            $supplier->update($validator->validated());
            return response()->json(['success' => true, 'message' => 'Supplier berhasil diperbarui.']);
        } catch (\Exception $e) {
            \Log::error('Error updating supplier: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            WarehouseSupplier::destroy($id);
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
