<?php

namespace App\Http\Controllers;

use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;

class WarehouseMasterGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseMasterGudang::query();
        $filters = ['nama', 'cost_center', 'apotek', 'warehouse', 'aktif'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%'.$request->$filter.'%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $master_gudangs = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $master_gudangs = WarehouseMasterGudang::all();
        }

        return view('pages.simrs.warehouse.master-data.master-gudang', [
            'master_gudangs' => $master_gudangs,
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
        $request->merge([
            'apotek' => $request->input('apotek', 0),
            'rajal_default' => $request->input('rajal_default', 0),
            'ranap_default' => $request->input('ranap_default', 0),
            'warehouse' => $request->input('warehouse', 0),
            'aktif' => $request->input('aktif', 0),
        ]);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'cost_center' => 'required|string|max:255',
            'apotek' => 'boolean',
            'rajal_default' => 'boolean',
            'ranap_default' => 'boolean',
            'warehouse' => 'boolean',
            'aktif' => 'boolean',
        ]);

        if ($validatedData['rajal_default'] == true && $validatedData['apotek'] == true) {
            WarehouseMasterGudang::where('rajal_default', true)->update(['rajal_default' => false]);
        } elseif ($validatedData['ranap_default'] == true && $validatedData['apotek'] == true) {
            WarehouseMasterGudang::where('ranap_default', true)->update(['ranap_default' => false]);
        } else {
            $validatedData['rajal_default'] = false;
            $validatedData['ranap_default'] = false;
        }

        WarehouseMasterGudang::create($validatedData);

        return redirect()->back()->with('success', 'Master Gudang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseMasterGudang $warehouseMasterGudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseMasterGudang $warehouseMasterGudang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseMasterGudang $warehouseMasterGudang)
    {
        $request->merge([
            'apotek' => $request->input('apotek', 0),
            'rajal_default' => $request->input('rajal_default', 0),
            'ranap_default' => $request->input('ranap_default', 0),
            'warehouse' => $request->input('warehouse', 0),
            'aktif' => $request->input('aktif', 0),
        ]);

        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'cost_center' => 'required|string|max:255',
            'apotek' => 'boolean',
            'rajal_default' => 'boolean',
            'ranap_default' => 'boolean',
            'warehouse' => 'boolean',
            'aktif' => 'boolean',
        ]);

        if ($validatedData['rajal_default'] == true && $validatedData['apotek'] == true) {
            WarehouseMasterGudang::where('rajal_default', true)->update(['rajal_default' => false]);
        } elseif ($validatedData['ranap_default'] == true && $validatedData['apotek'] == true) {
            WarehouseMasterGudang::where('ranap_default', true)->update(['ranap_default' => false]);
        } else {
            $validatedData['rajal_default'] = false;
            $validatedData['ranap_default'] = false;
        }

        $warehouseMasterGudang
            ->where('id', $validatedData['id'])
            ->update($validatedData);

        return redirect()->back()->with('success', 'Master Gudang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseMasterGudang $warehouseMasterGudang, $id)
    {
        try {
            $warehouseMasterGudang::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Master Gudang berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
