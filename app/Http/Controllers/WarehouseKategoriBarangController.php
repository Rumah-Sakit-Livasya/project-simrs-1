<?php

namespace App\Http\Controllers;

use App\Models\WarehouseKategoriBarang;
use Illuminate\Http\Request;

class WarehouseKategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseKategoriBarang::query();
        $filters = [
            'nama',
            'coa_inventory',
            'coa_sales_outpatient',
            'coa_cogs_outpatient',
            'coa_sales_inpatient',
            'coa_cogs_inpatient',
            'coa_adjustment_daily',
            'coa_adjustment_so',
            'konsinsyasi',
            'aktif',
            'kode',
        ];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $kategoris = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $kategoris = WarehouseKategoriBarang::all();
        }

        return view("pages.simrs.warehouse.master-data.kategori-barang", [
            "kategoris" => $kategoris
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'coa_inventory' => 'nullable|string|max:255',
            'coa_sales_outpatient' => 'nullable|string|max:255',
            'coa_cogs_outpatient' => 'nullable|string|max:255',
            'coa_sales_inpatient' => 'nullable|string|max:255',
            'coa_cogs_inpatient' => 'nullable|string|max:255',
            'coa_adjustment_daily' => 'nullable|string|max:255',
            'coa_adjustment_so' => 'nullable|string|max:255',
            'konsinsyasi' => 'required|boolean',
            'aktif' => 'required|boolean',
            'kode' => 'nullable|string|max:255',
        ]);

        WarehouseKategoriBarang::create($validatedData);
        return redirect()->back()->with('success', 'Kategori Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseKategoriBarang $warehouseKategoriBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseKategoriBarang $warehouseKategoriBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseKategoriBarang $warehouseKategoriBarang)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'coa_inventory' => 'nullable|string|max:255',
            'coa_sales_outpatient' => 'nullable|string|max:255',
            'coa_cogs_outpatient' => 'nullable|string|max:255',
            'coa_sales_inpatient' => 'nullable|string|max:255',
            'coa_cogs_inpatient' => 'nullable|string|max:255',
            'coa_adjustment_daily' => 'nullable|string|max:255',
            'coa_adjustment_so' => 'nullable|string|max:255',
            'konsinsyasi' => 'required|boolean',
            'aktif' => 'required|boolean',
            'kode' => 'nullable|string|max:255',
        ]);

        $warehouseKategoriBarang
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Satuan Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseKategoriBarang $warehouseKategoriBarang, $id)
    {
        try {
            $warehouseKategoriBarang::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Kategori barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
