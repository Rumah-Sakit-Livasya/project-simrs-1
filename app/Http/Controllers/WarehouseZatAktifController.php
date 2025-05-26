<?php

namespace App\Http\Controllers;

use App\Models\WarehouseZatAktif;
use Illuminate\Http\Request;

class WarehouseZatAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseZatAktif::query();
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
            $zats = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $zats = WarehouseZatAktif::all();
        }

        return view("pages.simrs.warehouse.master-data.zat-aktif", [
            "zats" => $zats
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

        WarehouseZatAktif::create($validatedData);
        return redirect()->back()->with('success', 'Zat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseZatAktif $warehouseZatAktif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseZatAktif $warehouseZatAktif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseZatAktif $warehouseZatAktif)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        $warehouseZatAktif
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Zat berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseZatAktif $warehouseZatAktif, $id)
    {
        try {
            $warehouseZatAktif::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Zat berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
