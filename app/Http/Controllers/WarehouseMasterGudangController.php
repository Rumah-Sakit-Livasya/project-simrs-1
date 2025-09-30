<?php

namespace App\Http\Controllers;

use App\Models\Keuangan\RncCenter;
use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WarehouseMasterGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseMasterGudang::query();

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('cost_center')) {
            $query->where('cost_center', 'like', '%' . $request->cost_center . '%');
        }
        // Filter untuk boolean/integer
        if ($request->filled('apotek')) {
            $query->where('apotek', $request->apotek);
        }
        if ($request->filled('warehouse')) {
            $query->where('warehouse', $request->warehouse);
        }
        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        $master_gudangs = $query->orderBy('created_at', 'desc')->get();
        $costCenters = RncCenter::where('is_active', 1)->orderBy('nama_rnc')->get();

        return view('pages.simrs.warehouse.master-data.master-gudang', compact('master_gudangs', 'costCenters'));
    }

    /**
     * Mengembalikan data gudang spesifik dalam format JSON.
     * Endpoint ini akan dipanggil oleh AJAX saat tombol Edit diklik.
     */
    public function show($id)
    {
        $warehouseMasterGudang = WarehouseMasterGudang::findOrFail($id);
        // Pastikan field yang dikembalikan sesuai dengan struktur tabel dan kebutuhan frontend
        return response()->json([
            'id' => $warehouseMasterGudang->id,
            'nama' => $warehouseMasterGudang->nama,
            'cost_center' => $warehouseMasterGudang->cost_center,
            'apotek' => (int) $warehouseMasterGudang->apotek,
            'warehouse' => (int) $warehouseMasterGudang->warehouse,
            'aktif' => (int) $warehouseMasterGudang->aktif,
            'freeze' => (int) $warehouseMasterGudang->freeze,
            'rajal_default' => (int) $warehouseMasterGudang->rajal_default,
            'ranap_default' => (int) $warehouseMasterGudang->ranap_default,
            'created_at' => $warehouseMasterGudang->created_at ? $warehouseMasterGudang->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $warehouseMasterGudang->updated_at ? $warehouseMasterGudang->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at' => $warehouseMasterGudang->deleted_at,
        ]);
    }

    /**
     * Store a newly created resource in storage via AJAX.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:warehouse_master_gudang,nama',
            'cost_center' => 'nullable|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isApotek = $request->has('apotek');
        $isRajalDefault = $request->has('rajal_default');
        $isRanapDefault = $request->has('ranap_default');

        if ($isRajalDefault && $isApotek) {
            WarehouseMasterGudang::where('rajal_default', true)->update(['rajal_default' => false]);
        }
        if ($isRanapDefault && $isApotek) {
            WarehouseMasterGudang::where('ranap_default', true)->update(['ranap_default' => false]);
        }

        WarehouseMasterGudang::create([
            'nama' => $request->nama,
            'cost_center' => $request->cost_center,
            'apotek' => $isApotek,
            'warehouse' => $request->has('warehouse'),
            'rajal_default' => $isApotek && $isRajalDefault,
            'ranap_default' => $isApotek && $isRanapDefault,
            'aktif' => $request->aktif,
        ]);

        return response()->json(['success' => 'Master Gudang berhasil ditambahkan!']);
    }

    /**
     * Update the specified resource in storage via AJAX.
     */
    public function update(Request $request, $id)
    {
        $warehouseMasterGudang = WarehouseMasterGudang::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:warehouse_master_gudang,nama,' . $warehouseMasterGudang->id,
            'cost_center' => 'nullable|string|max:255',
            'aktif' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isApotek = $request->has('apotek');
        $isRajalDefault = $request->has('rajal_default');
        $isRanapDefault = $request->has('ranap_default');

        if ($isRajalDefault && $isApotek) {
            WarehouseMasterGudang::where('id', '!=', $warehouseMasterGudang->id)->where('rajal_default', true)->update(['rajal_default' => false]);
        }
        if ($isRanapDefault && $isApotek) {
            WarehouseMasterGudang::where('id', '!=', $warehouseMasterGudang->id)->where('ranap_default', true)->update(['ranap_default' => false]);
        }

        $warehouseMasterGudang->update([
            'nama' => $request->nama,
            'cost_center' => $request->cost_center,
            'apotek' => $isApotek,
            'warehouse' => $request->has('warehouse'),
            'rajal_default' => $isApotek && $isRajalDefault,
            'ranap_default' => $isApotek && $isRanapDefault,
            'aktif' => $request->aktif,
        ]);

        return response()->json(['success' => 'Master Gudang berhasil diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $warehouseMasterGudang = WarehouseMasterGudang::findOrFail($id);

        try {
            // Cek relasi sebelum menghapus jika diperlukan
            // if ($warehouseMasterGudang->smms()->exists() || ... ) {
            //     return response()->json(['success' => false, 'message' => 'Data tidak bisa dihapus karena memiliki relasi.'], 409);
            // }

            // Gunakan instance dari Route-Model Binding untuk delete
            $warehouseMasterGudang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Master Gudang berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting warehouse: ' . $e->getMessage()); // Logging error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
            ], 500);
        }
    }
}
