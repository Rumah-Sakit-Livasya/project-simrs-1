<?php

namespace App\Http\Controllers;

use App\Models\WarehousePabrik;
use Illuminate\Http\Request;

class WarehousePabrikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehousePabrik::query();
        $filters = ['nama', 'alamat', 'telp', 'contact_person', 'contact_person_phone', 'aktif'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $pabriks = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $pabriks = WarehousePabrik::all();
        }

        return view("pages.simrs.warehouse.master-data.pabrik", [
            "pabriks" => $pabriks
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
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        WarehousePabrik::create($validatedData);
        return redirect()->back()->with('success', 'Pabrik berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehousePabrik $warehousePabrik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehousePabrik $warehousePabrik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehousePabrik $warehousePabrik)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $warehousePabrik
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Pabrik berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehousePabrik $warehousePabrik, $id)
    {
        try {
            $warehousePabrik::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Pabrik berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
