<?php

namespace App\Http\Controllers;

use App\Models\WarehouseGolonganBarang;
use Illuminate\Http\Request;

class WarehouseGolonganBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.master-data.golongan-barang", [
            "golongans" => WarehouseGolonganBarang::all()
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

        WarehouseGolonganBarang::create($validatedData);
        return redirect()->back()->with('success', 'Golongan Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseGolonganBarang $warehouseGolonganBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseGolonganBarang $warehouseGolonganBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseGolonganBarang $warehouseGolonganBarang)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        $warehouseGolonganBarang
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Golongan Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseGolonganBarang $warehouseGolonganBarang, $id)
    {
        try {
            $warehouseGolonganBarang::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Golongan barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
