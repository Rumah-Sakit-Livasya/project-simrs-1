<?php

namespace App\Http\Controllers;

use App\Models\Keuangan\ChartOfAccount;
use App\Models\WarehouseKategoriBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseKategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coas = ChartOfAccount::get();
        return view("pages.simrs.warehouse.master-data.kategori-barang", compact('coas'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $data = WarehouseKategoriBarang::with([
            '_coa_inventory',
            '_coa_sales_outpatient',
            '_coa_cogs_outpatient',
            '_coa_sales_inpatient',
            '_coa_cogs_inpatient',
            '_coa_adjustment_daily',
            '_coa_adjustment_so'
        ])->select('warehouse_kategori_barang.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>';
            })
            ->addColumn('konsinyasi_status', function ($row) {
                return $row->konsinyasi ? '<span class="badge badge-info">Ya</span>' : '<span class="badge badge-secondary">Tidak</span>';
            })
            ->addColumn('coa_inventory_name', fn($row) => $row->_coa_inventory->name ?? '-')
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse.master-data.kategori-barang.show', $row->id);
                $deleteUrl = route('warehouse.master-data.kategori-barang.destroy', $row->id);
                $actionBtn = '<div class="d-flex justify-content-center">';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit-btn" data-url="' . $editUrl . '"><i class="fal fa-pencil"></i> Edit</a> ';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-url="' . $deleteUrl . '"><i class="fal fa-trash"></i> Hapus</a>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'status', 'konsinyasi_status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:warehouse_kategori_barang,nama',
            'kode' => 'required|string|max:255|unique:warehouse_kategori_barang,kode',
            'aktif' => 'required|boolean',
            'konsinyasi' => 'required|boolean',
            'coa_inventory' => 'nullable|exists:chart_of_account,id',
            'coa_sales_outpatient' => 'nullable|exists:chart_of_account,id',
            'coa_cogs_outpatient' => 'nullable|exists:chart_of_account,id',
            'coa_sales_inpatient' => 'nullable|exists:chart_of_account,id',
            'coa_cogs_inpatient' => 'nullable|exists:chart_of_account,id',
            'coa_adjustment_daily' => 'nullable|exists:chart_of_account,id',
            'coa_adjustment_so' => 'nullable|exists:chart_of_account,id',
        ]);

        try {
            WarehouseKategoriBarang::create($request->all());
            return response()->json(['success' => true, 'message' => 'Kategori Barang berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kategoriBarang = WarehouseKategoriBarang::find($id);
        if (!$kategoriBarang) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $kategoriBarang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:warehouse_kategori_barang,nama,' . $id,
            'kode' => 'required|string|max:255|unique:warehouse_kategori_barang,kode,' . $id,
            'aktif' => 'required|boolean',
            'konsinyasi' => 'required|boolean',
            'coa_inventory' => 'nullable|exists:chart_of_account,id',
            'coa_sales_outpatient' => 'nullable|exists:chart_of_account,id',
            'coa_cogs_outpatient' => 'nullable|exists:chart_of_account,id',
            'coa_sales_inpatient' => 'nullable|exists:chart_of_account,id',
            'coa_cogs_inpatient' => 'nullable|exists:chart_of_account,id',
            'coa_adjustment_daily' => 'nullable|exists:chart_of_account,id',
            'coa_adjustment_so' => 'nullable|exists:chart_of_account,id',
        ]);

        try {
            $kategoriBarang = WarehouseKategoriBarang::findOrFail($id);
            $kategoriBarang->update($request->all());
            return response()->json(['success' => true, 'message' => 'Kategori Barang berhasil diperbarui.']);
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
            WarehouseKategoriBarang::destroy($id);
            return response()->json(['success' => true, 'message' => 'Kategori Barang berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
