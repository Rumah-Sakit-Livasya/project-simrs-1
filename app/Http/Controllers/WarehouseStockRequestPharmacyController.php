<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseStockRequestPharmacy;
use App\Models\WarehouseStockRequestPharmacyItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockRequestPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseStockRequestPharmacy::query()->with(['items']);
        $filters = ['kode_sr', 'status'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%'.$request->$filter.'%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_sr')) {
            $dateRange = explode(' - ', $request->tanggal_sr);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_sr', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%'.$request->nama_barang.'%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $sr = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $sr = WarehouseStockRequestPharmacy::all();
        }

        return view('pages.simrs.warehouse.stock-request.pharmacy', [
            'srs' => $sr,
        ]);
    }

    public function get_item_gudang($asal_gudang_id, $tujuan_gudang_id)
    {
        $gudang_asal = WarehouseMasterGudang::findOrFail($asal_gudang_id);
        $gudang_tujuan = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);

        return view('pages.simrs.warehouse.stock-request.partials.table-items-pharmacy', [
            'items' => WarehouseBarangFarmasi::all(),
            'sis_asal' => StoredBarangFarmasi::where('gudang_id', $asal_gudang_id)->where('qty', '>', 0)->get(),
            'sis_tujuan' => StoredBarangFarmasi::where('gudang_id', $tujuan_gudang_id)->where('qty', '>', 0)->get(),
            'gudang_asal' => $gudang_asal,
            'gudang_tujuan' => $gudang_tujuan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.simrs.warehouse.stock-request.partials.popup-add-sr-farmasi', [
            'gudangs' => WarehouseMasterGudang::all(),
            'gudang_asals' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
        ]);
    }

    private function generate_sr_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehouseStockRequestPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count.'/SRF/'.$year.$month;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'asal_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tujuan_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tanggal_sr' => 'required|date',
            'tipe' => 'required|in:normal,urgent',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer',
            'keterangan_item.*' => 'nullable|string',
        ]);

        $validatedData1['kode_sr'] = $this->generate_sr_code();
        DB::beginTransaction();

        try {
            $sr = WarehouseStockRequestPharmacy::create($validatedData1);
            foreach ($validatedData2['barang_id'] as $index => $barangId) {
                WarehouseStockRequestPharmacyItems::create([
                    'sr_id' => $sr->id,
                    'barang_id' => $barangId,
                    'satuan_id' => $validatedData2['satuan_id'][$index],
                    'qty' => $validatedData2['qty'][$index],
                    'keterangan' => $validatedData2['keterangan_item'][$index] ?? null,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
    {
        return view('pages.simrs.warehouse.stock-request.partials.popup-edit-sr-farmasi', [
            'sr' => $warehouseStockRequestPharmacy::findorfail($id),
            'gudangs' => WarehouseMasterGudang::all(),
            'gudang_asals' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 1)->where('warehouse', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
    {
        $validatedData1 = $request->validate([
            'id' => 'required|exists:warehouse_stock_request_pharmacy,id',
            'user_id' => 'required|exists:users,id',
            'asal_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tujuan_gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tanggal_sr' => 'required|date',
            'tipe' => 'required|in:normal,urgent',
            'status' => 'required|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'barang_id.*' => 'required|exists:warehouse_barang_farmasi,id',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'qty.*' => 'required|integer',
            'keterangan_item.*' => 'nullable|string',
            'item_id.*' => 'nullable|exists:warehouse_stock_request_pharmacy_item,id',
        ]);

        DB::beginTransaction();

        try {
            $sr = $warehouseStockRequestPharmacy->findOrFail($id);
            $sr->update($validatedData1);

            // $validatedData["item_id"] is a key => pair array
            // delete everything from WarehouseStockRequestPharmacyItems
            // where sr_id == $pr->id
            // and id IS NOT IN $validatedData["item_id"]
            // because if it is not in $validatedData["item_id"]
            // it means it has been deleted
            if (isset($validatedData2['item_id']) && count($validatedData2['item_id']) > 0) {
                WarehouseStockRequestPharmacyItems::where('sr_id', $sr->id)
                    ->whereNotIn('id', $validatedData2['item_id'])
                    ->delete(); // don't force delete to retain history
            } else {
                WarehouseStockRequestPharmacyItems::where('sr_id', $sr->id)->delete();
            }

            foreach ($validatedData2['barang_id'] as $index => $barangId) {
                $attributes = [
                    'sr_id' => $sr->id,
                    'barang_id' => $barangId,
                    'satuan_id' => $validatedData2['satuan_id'][$index],
                    'qty' => $validatedData2['qty'][$index],
                    'keterangan' => $validatedData2['keterangan_item'][$index] ?? null,
                ];

                if ($request->has('item_id') && isset($validatedData2['item_id'][$index])) {
                    $sri = WarehouseStockRequestPharmacyItems::findorfail($validatedData2['item_id'][$index]);
                    $sri->update($attributes);
                } else {
                    $sri = new WarehouseStockRequestPharmacyItems($attributes);
                }

                $sri->save(); // save or update
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        return view('pages.simrs.warehouse.stock-request.partials.sr-print-pharmacy', [
            'sr' => WarehouseStockRequestPharmacy::findorfail($id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseStockRequestPharmacy $warehouseStockRequestPharmacy, $id)
    {
        $pr = $warehouseStockRequestPharmacy->findorfail($id);
        if ($pr->status == 'final') {
            return response()->json([
                'success' => false,
                'message' => 'SR sudah final, tidak bisa dihapus!',
            ]);
        }

        try {
            $pr->delete();

            return response()->json([
                'success' => true,
                'message' => 'SR berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
