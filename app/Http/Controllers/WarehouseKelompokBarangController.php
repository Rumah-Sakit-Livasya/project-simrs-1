<?php

namespace App\Http\Controllers;

use App\Models\WarehouseKelompokBarang;
use Illuminate\Http\Request;

class WarehouseKelompokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseKelompokBarang::query();
        $filters = ['nama', 'aktif'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $kelompoks = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $kelompoks = WarehouseKelompokBarang::all();
        }

        return view("pages.simrs.warehouse.master-data.kelompok-barang", [
            "kelompoks" => $kelompoks
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
            'aktif' => 'required|boolean'
        ]);

        WarehouseKelompokBarang::create($validatedData);
        return redirect()->back()->with('success', 'Kelompok Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseKelompokBarang $warehouseKelompokBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseKelompokBarang $warehouseKelompokBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseKelompokBarang $warehouseKelompokBarang)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        $warehouseKelompokBarang
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Kelompok Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseKelompokBarang $warehouseKelompokBarang, $id)
    {
        try {
            $warehouseKelompokBarang::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Kelompok barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
