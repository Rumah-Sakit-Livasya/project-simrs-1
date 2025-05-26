<?php

namespace App\Http\Controllers;

use App\Models\WarehouseSatuanBarang;
use Illuminate\Http\Request;

class WarehouseSatuanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseSatuanBarang::query();
        $filters = ['nama', 'kode', 'aktif'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $satuans = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $satuans = WarehouseSatuanBarang::all();
        }

        return view("pages.simrs.warehouse.master-data.satuan-barang", [
            "satuans" => $satuans
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
            'aktif' => 'required|boolean',
            'kode' => 'required|string|max:255'
        ]);

        WarehouseSatuanBarang::create($validatedData);
        return redirect()->back()->with('success', 'Satuan Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseSatuanBarang $warehouseSatuanBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseSatuanBarang $warehouseSatuanBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseSatuanBarang $warehouseSatuanBarang)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        $warehouseSatuanBarang
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Satuan Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseSatuanBarang $warehouseSatuanBarang, $id)
    {
        try {
            $warehouseSatuanBarang::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Satuan barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
