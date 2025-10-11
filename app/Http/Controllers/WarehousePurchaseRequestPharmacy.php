<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseRequestPharmacy;
use App\Models\ProcurementPurchaseRequestPharmacyItems;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePurchaseRequestPharmacy extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProcurementPurchaseRequestPharmacy::with(['gudang', 'user.employee']);

        // --- Blok Pencarian ---
        if ($request->filled('kode_pr')) {
            $query->where('kode_pr', 'like', '%' . $request->kode_pr . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_pr')) {
            $dateRange = explode(' to ', $request->tanggal_pr);
            if (count($dateRange) === 2) {
                $startDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->endOfDay();
                $query->whereBetween('tanggal_pr', [$startDate, $endDate]);
            }
        }
        if ($request->filled('nama_barang')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }
        // --- Akhir Blok Pencarian ---

        $prs = $query->orderBy('created_at', 'desc')->get();

        return view('pages.simrs.warehouse.purchase-request.pharmacy', [
            'prs' => $prs,
        ]);
    }

    /**
     * Menyediakan data detail item untuk DataTables child row.
     */
    public function details($id)
    {
        $items = ProcurementPurchaseRequestPharmacyItems::where('pr_id', $id)->get();
        return response()->json(['data' => $items]);
    }

    /**
     * Show the form for creating a new resource in a popup window.
     */
    public function create()
    {
        $gudangs = WarehouseMasterGudang::where('aktif', 1)
            ->where('apotek', 1)
            ->where('warehouse', 1)
            ->get();

        return view('pages.simrs.warehouse.purchase-request.partials.form-popup', [
            'gudangs' => $gudangs,
            'action' => route('warehouse.purchase-request.pharmacy.store'),
            'method' => 'POST',
            'pr' => null,
        ]);
    }

    /**
     * Show the form for editing the specified resource in a popup window.
     */
    public function edit($id)
    {
        $pr = ProcurementPurchaseRequestPharmacy::with('items.barang.satuan')->findOrFail($id);
        $gudangs = WarehouseMasterGudang::where('aktif', 1)
            ->where('apotek', 1)
            ->where('warehouse', 1)
            ->get();

        return view('pages.simrs.warehouse.purchase-request.partials.form-popup', [
            'pr' => $pr,
            'gudangs' => $gudangs,
            'action' => route('warehouse.purchase-request.pharmacy.update', $id),
            'method' => 'PUT',
        ]);
    }

    public function popupItems()
    {
        $barangs = WarehouseBarangFarmasi::with('satuan')->where('aktif', 1)->get();
        return view('pages.simrs.warehouse.purchase-request.partials.popup-items-pharmacy', [
            'barangs' => $barangs
        ]);
    }

    private function generate_pr_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = ProcurementPurchaseRequestPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . '/PRF/' . $year . $month;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData1 = $request->validate([
            'tanggal_pr' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tipe' => 'required|in:normal,urgent',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer|min:1',
            'hna.*' => 'required|numeric|min:0',
            'keterangan_item.*' => 'nullable|string',
        ]);

        $validatedData1['kode_pr'] = $this->generate_pr_code();

        DB::beginTransaction();
        try {
            $pr = ProcurementPurchaseRequestPharmacy::create($validatedData1);

            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                $barang = WarehouseBarangFarmasi::find($barang_id);
                $satuan = WarehouseSatuanBarang::find($validatedData2['satuan_id'][$key]);

                ProcurementPurchaseRequestPharmacyItems::create([
                    'pr_id' => $pr->id,
                    'barang_id' => $barang_id,
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'kode_barang' => $barang->kode,
                    'nama_barang' => $barang->nama,
                    'unit_barang' => $satuan->nama,
                    'harga_barang' => $validatedData2['hna'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'subtotal' => $validatedData2['hna'][$key] * $validatedData2['qty'][$key],
                    'status' => 'unprocessed',
                    'keterangan' => $validatedData2['keterangan_item'][$key] ?? null,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data PR berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData1 = $request->validate([
            'tanggal_pr' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tipe' => 'required|in:normal,urgent',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'item_id.*' => 'nullable|integer|exists:procurement_purchase_request_pharmacy_items,id',
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer|min:1',
            'hna.*' => 'required|numeric|min:0',
            'keterangan_item.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pr = ProcurementPurchaseRequestPharmacy::findOrFail($id);

            if ($pr->status !== 'draft') {
                return response()->json(['success' => false, 'message' => 'Hanya PR dengan status DRAFT yang bisa diubah.'], 403);
            }
            $pr->update($validatedData1);

            $existingItemIds = array_filter($validatedData2['item_id'] ?? []);
            $pr->items()->whereNotIn('id', $existingItemIds)->delete();

            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                $barang = WarehouseBarangFarmasi::find($barang_id);
                $satuan = WarehouseSatuanBarang::find($validatedData2['satuan_id'][$key]);

                $attributes = [
                    'barang_id' => $barang_id,
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'kode_barang' => $barang->kode,
                    'nama_barang' => $barang->nama,
                    'unit_barang' => $satuan->nama,
                    'harga_barang' => $validatedData2['hna'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'subtotal' => $validatedData2['hna'][$key] * $validatedData2['qty'][$key],
                    'status' => 'unprocessed',
                    'keterangan' => $validatedData2['keterangan_item'][$key] ?? null,
                ];

                $pr->items()->updateOrCreate(
                    ['id' => $validatedData2['item_id'][$key] ?? null],
                    $attributes
                );
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data PR berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pr = ProcurementPurchaseRequestPharmacy::findOrFail($id);
        if ($pr->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya PR dengan status DRAFT yang bisa dihapus!',
            ], 403);
        }

        try {
            $pr->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data PR berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function print($id)
    {
        $pr = ProcurementPurchaseRequestPharmacy::findOrFail($id);
        return view('pages.simrs.warehouse.purchase-request.partials.pr-print-pharmacy', compact('pr'));
    }
}
