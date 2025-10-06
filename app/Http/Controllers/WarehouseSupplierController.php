<?php

namespace App\Http\Controllers;

use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use DataTables;

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
        $this->validateRequest($request);

        try {
            WarehouseSupplier::create($request->all());
            return response()->json(['success' => true, 'message' => 'Supplier berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
        $this->validateRequest($request);

        try {
            $supplier = WarehouseSupplier::findOrFail($id);
            $supplier->update($request->all());
            return response()->json(['success' => true, 'message' => 'Supplier berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

    /**
     * Validate the request for store and update.
     */
    private function validateRequest(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:FARMASI,UMUM',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'no_rek' => 'nullable|string|max:50',
            'bank' => 'nullable|string|max:100',
            'top' => 'nullable|in:COD,7HARI,14HARI,21HARI,24HARI,30HARI,37HARI,40HARI,45HARI',
            'tipe_top' => 'nullable|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG',
            'ppn' => 'required|integer|min:0',
            'aktif' => 'required|boolean'
        ]);
    }
}
