<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseSatuanTambahanBarangNonFarmasi;
use Illuminate\Http\Request;

class WarehouseBarangNonFarmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseBarangNonFarmasi::query();
        $filters = ['nama', 'kode', 'keterangan', 'hna', 'ppn', 'aktif', 'jual_pasien', 'kategori_id', 'kelompok_id', 'satuan_id'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if (in_array($filter, ['hna', 'ppn', 'kategori_id', 'kelompok_id', 'satuan_id'])) {
                    $query->where($filter, $request->$filter);
                } else {
                    $query->where($filter, 'like', '%' . $request->$filter . '%');
                }
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $barangs = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $barangs = WarehouseBarangNonFarmasi::all();
        }

        return view("pages.simrs.warehouse.master-data.barang-non-farmasi", [
            "barangs" => $barangs,
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.simrs.warehouse.master-data.partials.popup-add-barang-non-farmasi", [
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'hna' => 'required|integer',
            'ppn' => 'required|integer',
            'aktif' => 'required|boolean',
            'jual_pasien' => 'required|boolean',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'golongan_id' => 'nullable|exists:warehouse_golongan_barang,id',
            'kelompok_id' => 'nullable|exists:warehouse_kelompok_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "integer",
            "satuans_status" => "nullable|array",
            "satuans_status.*" => "boolean"
        ]);

        $barang = WarehouseBarangNonFarmasi::create($validatedData);

        if ($validatedData["satuans_id"]) {
            foreach ($validatedData['satuans_id'] as $index => $satuanId) {
                WarehouseSatuanTambahanBarangNonFarmasi::create([
                    "barang_id" => $barang->id,
                    "satuan_id" => $satuanId,
                    "isi" => $validatedData['satuans_jumlah'][$index],
                    "aktif" => isset($validatedData['satuans_status'][$index]) ? $validatedData['satuans_status'][$index] : false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Barang Non Farmasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseBarangNonFarmasi $warehouseBarangNonFarmasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseBarangNonFarmasi $warehouseBarangNonFarmasi, $id)
    {
        return view("pages.simrs.warehouse.master-data.partials.popup-edit-barang-non-farmasi", [
            "barang" => $warehouseBarangNonFarmasi->where("id", $id)->first(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseBarangNonFarmasi $warehouseBarangNonFarmasi)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'hna' => 'required|integer',
            'ppn' => 'required|integer',
            'aktif' => 'required|boolean',
            'jual_pasien' => 'required|boolean',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'golongan_id' => 'nullable|exists:warehouse_golongan_barang,id',
            'kelompok_id' => 'nullable|exists:warehouse_kelompok_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id'
        ]);

        $warehouseBarangNonFarmasi
            ->where("id", $validatedData['id'])
            ->update($validatedData);

        $validatedData = $request->validate([
            'id' => 'required|integer',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "integer",
            "satuans_status" => "nullable|array",
            "satuans_status.*" => "boolean"
        ]);

        // delete all data from WarehouseSatuanTambahanBarangNonFarmasi
        // where "barang_id" == $validatedData['id']
        WarehouseSatuanTambahanBarangNonFarmasi::where('barang_id', $validatedData['id'])->forceDelete();

        if ($request->has('satuans_id')) {
            foreach ($validatedData['satuans_id'] as $index => $satuanId) {
                WarehouseSatuanTambahanBarangNonFarmasi::create([
                    "barang_id" => $validatedData['id'],
                    "satuan_id" => $satuanId,
                    "isi" => $validatedData['satuans_jumlah'][$index],
                    "aktif" => isset($validatedData['satuans_status'][$index]) ? $validatedData['satuans_status'][$index] : false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Barang Non Farmasi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseBarangNonFarmasi $warehouseBarangNonFarmasi, $id)
    {
        try {
            $warehouseBarangNonFarmasi::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Barang Non Farmasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
