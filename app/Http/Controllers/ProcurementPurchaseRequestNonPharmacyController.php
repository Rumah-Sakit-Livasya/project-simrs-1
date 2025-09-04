<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseRequestNonPharmacy;
use App\Models\ProcurementPurchaseRequestNonPharmacyItems;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementPurchaseRequestNonPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProcurementPurchaseRequestNonPharmacy::query()->with(['items']);
        $filters = ['kode_pr', 'approval'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%'.$request->$filter.'%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_pr')) {
            $query->where('tanggal_pr', $request->tanggal_pr);
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
            $pr = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $pr = ProcurementPurchaseRequestNonPharmacy::all();
        }

        return view('pages.simrs.procurement.purchase-request.non-pharmacy', [
            'prs' => $pr,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.simrs.procurement.purchase-request.partials.popup-add-pr-non-farmasi', [
            'satuans' => WarehouseSatuanBarang::all(),
            'gudangs' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 0)->where('warehouse', 1)->get(),
            'barangs' => WarehouseBarangNonFarmasi::all(),
        ]);
    }

    public function print($id)
    {
        return view('pages.simrs.procurement.purchase-request.partials.pr-print-non-pharmacy', [
            'pr' => ProcurementPurchaseRequestNonPharmacy::findorfail($id),
        ]);
    }

    public function get_item_gudang($gudang_id)
    {
        // logic coming soon
        return view('pages.simrs.procurement.purchase-request.partials.table-items-non-pharmacy', [
            'items' => WarehouseBarangNonFarmasi::all(),
        ]);
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
            'nominal' => 'required|integer',
            'status' => 'required|in:draft,final,reviewed',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|string',
            'nama_barang' => 'required|array',
            'nama_barang.*' => 'required|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:warehouse_barang_non_farmasi,id',
            'unit_barang' => 'required|array',
            'unit_barang.*' => 'required|string',
            'satuan_id' => 'required|array',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'keterangan_item' => 'array',
            'keterangan_item.*' => 'nullable|string',
            'qty' => 'required|array',
            'qty.*' => 'required|integer',
            'hna' => 'required|array',
            'hna.*' => 'required|integer',
        ]);

        $validatedData1['kode_pr'] = $this->generate_pr_code();

        DB::beginTransaction();
        try {
            $pr = ProcurementPurchaseRequestNonPharmacy::create($validatedData1);

            foreach ($validatedData2['barang_id'] as $key => $barang_id) {
                ProcurementPurchaseRequestNonPharmacyItems::create([
                    'pr_id' => $pr->id,
                    'barang_id' => $validatedData2['barang_id'][$key],
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'harga_barang' => $validatedData2['hna'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'subtotal' => $validatedData2['hna'][$key] * $validatedData2['qty'][$key],
                    'status' => 'unprocessed',
                    'approved_qty' => null,
                    'keterangan' => $validatedData2['keterangan_item'][$key] ?? null,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    private function generate_pr_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = ProcurementPurchaseRequestNonPharmacy::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count.'/PRU/'.$year.$month;
    }

    /**
     * Display the specified resource.
     */
    public function show(ProcurementPurchaseRequestNonPharmacy $procurementPurchaseRequestNonPharmacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcurementPurchaseRequestNonPharmacy $procurementPurchaseRequestNonPharmacy, $id)
    {
        return view('pages.simrs.procurement.purchase-request.partials.popup-edit-pr-non-farmasi', [
            'pr' => $procurementPurchaseRequestNonPharmacy::findorfail($id),
            'satuans' => WarehouseSatuanBarang::all(),
            'gudangs' => WarehouseMasterGudang::where('aktif', 1)->where('apotek', 0)->where('warehouse', 1)->get(),
            'barangs' => WarehouseBarangNonFarmasi::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProcurementPurchaseRequestNonPharmacy $procurementPurchaseRequestNonPharmacy, $id)
    {
        $validatedData1 = $request->validate([
            'id' => 'required|exists:procurement_purchase_request_non_pharmacy,id',
            'kode_pr' => 'required|string',
            'tanggal_pr' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tipe' => 'required|in:normal,urgent',
            'nominal' => 'required|integer',
            'status' => 'required|in:draft,final,reviewed',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData2 = $request->validate([
            'kode_barang' => 'required|array',
            'kode_barang.*' => 'required|string',
            'nama_barang' => 'required|array',
            'nama_barang.*' => 'required|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:warehouse_barang_non_farmasi,id',
            'unit_barang' => 'required|array',
            'unit_barang.*' => 'required|string',
            'satuan_id' => 'required|array',
            'satuan_id.*' => 'required|exists:warehouse_satuan_barang,id',
            'keterangan_item' => 'array',
            'keterangan_item.*' => 'nullable|string',
            'qty' => 'required|array',
            'qty.*' => 'required|integer',
            'hna' => 'required|array',
            'hna.*' => 'required|integer',
            'item_id' => 'nullable|array',
            'item_id.*' => 'integer',
        ]);

        DB::beginTransaction();
        try {
            $pr = $procurementPurchaseRequestNonPharmacy->findOrFail($id);
            $pr->update($validatedData1);

            // $validatedData["item_id"] is a key => pair array
            // delete everything from ProcurementPurchaseRequestNonPharmacyItems
            // where pr_id == $pr->id
            // and id IS NOT IN $validatedData["item_id"]
            // because if it is not in $validatedData["item_id"]
            // it means it has been deleted
            if (count($validatedData2['item_id']) > 0) {
                ProcurementPurchaseRequestNonPharmacyItems::where('pr_id', $pr->id)
                    ->whereNotIn('id', $validatedData2['item_id'])
                    ->delete(); // don't force delete to retain history
            }

            foreach ($validatedData2['barang_id'] as $key => $item_id) {
                $attributes = [
                    'pr_id' => $id,
                    'barang_id' => $validatedData2['barang_id'][$key],
                    'satuan_id' => $validatedData2['satuan_id'][$key],
                    'kode_barang' => $validatedData2['kode_barang'][$key],
                    'nama_barang' => $validatedData2['nama_barang'][$key],
                    'unit_barang' => $validatedData2['unit_barang'][$key],
                    'harga_barang' => $validatedData2['hna'][$key],
                    'qty' => $validatedData2['qty'][$key],
                    'subtotal' => $validatedData2['hna'][$key] * $validatedData2['qty'][$key],
                    'status' => 'unprocessed',
                    'approved_qty' => null,
                    'keterangan' => $validatedData2['keterangan_item'][$key] ?? null,
                ];

                if ($request->has('item_id') && isset($validatedData2['item_id'][$key])) {
                    $pri = ProcurementPurchaseRequestNonPharmacyItems::findorfail($validatedData2['item_id'][$key]);
                    $pri->update($attributes);
                } else {
                    $pri = new ProcurementPurchaseRequestNonPharmacyItems($attributes);
                }

                $pri->save();
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcurementPurchaseRequestNonPharmacy $procurementPurchaseRequestNonPharmacy, $id)
    {
        $pr = $procurementPurchaseRequestNonPharmacy->findorfail($id);
        if ($pr->status == 'final') {
            return response()->json([
                'success' => false,
                'message' => 'PR sudah final, tidak bisa dihapus!',
            ]);
        }

        try {
            $pr->delete();

            return response()->json([
                'success' => true,
                'message' => 'PR berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
