<?php

namespace App\Http\Controllers;

use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;

class WarehouseSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseSupplier::query();
        $filters = ['nama', 'alamat', 'phone', 'fax', 'email', 'contact_person', 'contact_person_phone', 'contact_person_email', 'no_rek', 'bank', 'tipe_top', 'ppn', 'aktif', 'top'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $suppliers = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $suppliers = WarehouseSupplier::all();
        }

        return view("pages.simrs.warehouse.master-data.supplier", [
            "suppliers" => $suppliers
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

        WarehouseSupplier::create($validatedData);
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseSupplier $warehouseSupplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseSupplier $warehouseSupplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseSupplier $warehouseSupplier)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
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

        $warehouseSupplier
            ->where("id", $validatedData['id'])
            ->update($validatedData);
        return redirect()->back()->with('success', 'Supplier berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseSupplier $warehouseSupplier, $id)
    {
        try {
            $warehouseSupplier::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
