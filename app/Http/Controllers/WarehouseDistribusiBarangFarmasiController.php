<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseDistribusiBarangFarmasi;
use App\Models\WarehouseDistribusiBarangFarmasiItems;
use App\Models\WarehouseStockRequestPharmacy;
use App\Models\WarehouseStockRequestPharmacyItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseDistribusiBarangFarmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseDistribusiBarangFarmasi::query()->with(["items"]);
        $filters = ["kode_db", "status", "asal_gudang_id", "tujuan_gudang_id"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_db')) {
            $dateRange = explode(' - ', $request->tanggal_db);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_db', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $db = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $db = WarehouseDistribusiBarangFarmasi::all();
        }

        return view("pages.simrs.warehouse.distribusi-barang.pharmacy", [
            "gudangs" => WarehouseMasterGudang::all(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
            "dbs" => $db
        ]);
    }

    public function get_item_gudang($asal_gudang_id, $tujuan_gudang_id)
    {
        $gudang_asal = WarehouseMasterGudang::findOrFail($asal_gudang_id);
        $gudang_tujuan = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);
        return view("pages.simrs.warehouse.distribusi-barang.partials.table-items-pharmacy", [
            "sis_asal" => StoredBarangFarmasi::where("gudang_id", $asal_gudang_id)->where('qty', '>', 0)->get(),
            "sis_tujuan" => StoredBarangFarmasi::where("gudang_id", $tujuan_gudang_id)->where('qty', '>', 0)->get(),
            "gudang_asal" => $gudang_asal,
            "gudang_tujuan" => $gudang_tujuan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $srs = WarehouseStockRequestPharmacy::query()->with(["items", "items.barang", "items.satuan"]);
        $srs->where("status", "final");
        $srs->whereHas("items", function ($q) {
            $q->whereColumn("qty_fulfilled", "<", "qty");
        });

        return view("pages.simrs.warehouse.distribusi-barang.partials.popup-add-db-farmasi", [
            "srs" => $srs->get(),
            "gudangs" => WarehouseMasterGudang::all(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
        ]);
    }

    private function generate_db_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehouseDistribusiBarangFarmasi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . "/DBF/" . $year . $month;
    }

    public function get_stock($gudang_id, $barang_id, $satuan_id)
    {
        $si = StoredBarangFarmasi::query()->with(["pbi"]);
        $si->where("gudang_id", $gudang_id);
        $si->where("qty", ">", 0);
        $si->whereHas("pbi", function ($q) use ($barang_id, $satuan_id) {
            $q->where("barang_id", $barang_id);
            $q->where("satuan_id", $satuan_id);
            $q->whereDate("tanggal_exp", ">=", now())->orWhereNull("tanggal_exp");
        });

        $sis = $si->get();


        $stock = 0;
        foreach ($sis as $si) {
            $stock += $si->qty;
        }

        return response()->json([
            "gudang" => WarehouseMasterGudang::findOrFail($gudang_id),
            "sis" => $sis,
            "qty" => $stock,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseDistribusiBarangFarmasi $warehouseDistribusiBarangPharmacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseDistribusiBarangFarmasi $warehouseDistribusiBarangPharmacy, $id)
    {
        $db =  $warehouseDistribusiBarangPharmacy::findorfail($id);
        foreach ($db->items as $item) {
            $si = StoredBarangFarmasi::query()->with(["pbi"]);
            $si->where("gudang_id", $db->asal_gudang_id);
            $si->where("qty", ">", 0);
            $si->whereHas("pbi", function ($q) use ($db) {
                $q->where("barang_id", $db->barang_id);
                $q->where("satuan_id", $db->satuan_id);
                $q->whereDate("tanggal_exp", ">=", now())->orWhereNull("tanggal_exp");
            });

            $sis = $si->get();

            $item->stock = 0;
            foreach ($sis as $si) {
                $item->stock += $si->qty;
            }
        }

        $srs = WarehouseStockRequestPharmacy::query()->with(["items", "items.barang", "items.satuan"]);
        $srs->where("status", "final");
        $srs->whereHas("items", function ($q) {
            $q->whereColumn("qty_fulfilled", "<", "qty");
        });

        return view("pages.simrs.warehouse.distribusi-barang.partials.popup-edit-db-farmasi", [
            "srs" => $srs->get(),
            "db" => $db,
            "gudangs" => WarehouseMasterGudang::all(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validatedData1 = $request->validate([
            "user_id" => "required|exists:users,id",
            "asal_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tujuan_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tanggal_db" => "required|date",
            "sr_id" => "nullable|exists:warehouse_stock_request_pharmacy,id",
            "status" => "required|in:draft,final",
            "keterangan" => "nullable|string"
        ]);

        // dd($validatedData1);

        $validatedData2 = $request->validate([
            "barang_id.*" => "required|exists:warehouse_barang_farmasi,id",
            "satuan_id.*" => "required|exists:warehouse_satuan_barang,id",
            "qty.*" => "required|integer",
            "keterangan_item.*" => "nullable|string",
            "sri_id.*" => "nullable|exists:warehouse_stock_request_pharmacy_item,id"
        ]);

        // dd($validatedData2);

        $validatedData1["kode_db"] = $this->generate_db_code();
        DB::beginTransaction();

        try {
            $db = WarehouseDistribusiBarangFarmasi::create($validatedData1);
            $asal_gudang_id = $validatedData1["asal_gudang_id"];
            $tujuan_gudang_id = $validatedData1["tujuan_gudang_id"];


            foreach ($validatedData2["barang_id"] as $index => $barang_id) {
                $satuan_id = $validatedData2["satuan_id"][$index];
                $sri_id = $validatedData2["sri_id"][$index] ?? null;
                $requested_qty = $validatedData2["qty"][$index];
                $keterangan_item = $validatedData2["keterangan_item"][$index] ?? null;
                WarehouseDistribusiBarangFarmasiItems::create([
                    "db_id" => $db->id,
                    "barang_id" => $barang_id,
                    "satuan_id" => $satuan_id,
                    "qty" => $requested_qty,
                    "keterangan" => $keterangan_item,
                    "sri_id" => $sri_id
                ]);

                if ($validatedData1["status"] == "final") {
                    // update stock request item
                    if (isset($sri_id)) {
                        $sri = WarehouseStockRequestPharmacyItems::findOrFail($sri_id);
                        $sri->qty_fulfilled += $requested_qty;
                        if ($sri->qty < $sri->qty_fulfilled) $sri->qty_fulfilled = $sri->qty;
                        $sri->save();
                    }

                    // TODO: LOGIC FOR DISTRIBUTE GOODS
                    $origin_sis = StoredBarangFarmasi::query()->with(["pbi"]);
                    $origin_sis->where("gudang_id", $asal_gudang_id);
                    $origin_sis->whereHas("pbi", function ($q) use ($barang_id, $satuan_id) {
                        // barang and satuan
                        $q->where("barang_id", $barang_id);
                        $q->where("satuan_id", $satuan_id);
                        // ensure tanggal_exp is not in the past
                        $q->whereDate("tanggal_exp", ">=", now())->orWhereNull("tanggal_exp");
                    });
                    $origin_sis = $origin_sis->oldest()->get();

                    // ensure there are enough goods in origin warehouse for requested quantity
                    $available_stock = $origin_sis->sum("qty");
                    if ($available_stock < $requested_qty) {
                        // throw exception
                        throw new \Exception("Stock tidak cukup untuk barang dengan id" . $barang_id);
                    }

                    $transfered = 0;

                    foreach ($origin_sis as $si) {
                        if ($transfered >= $requested_qty) break;

                        // option 1: update existing
                        // check if there is another $si with gudang_id == tujuan_gudang_id
                        // and pbi the same as $si
                        $si_tujuan = StoredBarangFarmasi::query()->where("gudang_id", $tujuan_gudang_id)
                            ->where("pbi_id", $si->pbi_id)
                            ->first();
                        if ($si_tujuan) {
                            $qty = $si->qty;
                            if (($qty + $transfered) > $requested_qty) {
                                $qty = $requested_qty - $transfered;
                            }

                            // update existing
                            $si_tujuan->qty += $qty;
                            $transfered += $qty;
                            $si_tujuan->save();

                            $si->qty -= $qty;
                            if ($si->qty == 0) { // delete if qty is 0
                                $si->forceDelete(); // force delete
                            } else { // update if qty is not 0
                                $si->save();
                            }

                            continue;
                        }

                        // option 2: move the whole $si
                        // if $si->qty + $transfered is still less than $requested_qty
                        // then update the $si->gudang_id to $tujuan_gudang_id
                        if (($si->qty + $transfered) <= $requested_qty) {
                            $si->gudang_id = $tujuan_gudang_id;
                            $si->save();
                            $transfered += $si->qty;
                            continue;
                        }

                        // option 3: split
                        // if $si->qty + $transfered is more than $requested_qty
                        // then create a new $si with the remaining qty
                        // and update the existing $si->qty
                        if (($si->qty + $transfered) > $requested_qty) {
                            $remaining_qty = $requested_qty - $transfered;
                            $si->qty -= $remaining_qty;
                            $si->save();

                            StoredBarangFarmasi::create([
                                "pbi_id" => $si->pbi_id,
                                "gudang_id" => $tujuan_gudang_id,
                                "qty" => $remaining_qty
                            ]);

                            $transfered = $requested_qty;
                            continue;
                        }
                    }
                }
            }



            DB::commit();
            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseDistribusiBarangFarmasi $warehouseDistribusiBarangPharmacy, $id)
    {
        // dd($request->all());
        $validatedData1 = $request->validate([
            "id" => "required|exists:warehouse_distribusi_barang_farmasi,id",
            "user_id" => "required|exists:users,id",
            "asal_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tujuan_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tanggal_db" => "required|date",
            "sr_id" => "nullable|exists:warehouse_stock_request_pharmacy,id",
            "status" => "required|in:draft,final",
            "keterangan" => "nullable|string"
        ]);
        // dd($validatedData1);

        $validatedData2 = $request->validate([
            "barang_id.*" => "required|exists:warehouse_barang_farmasi,id",
            "satuan_id.*" => "required|exists:warehouse_satuan_barang,id",
            "qty.*" => "required|integer",
            "keterangan_item.*" => "nullable|string",
            "sri_id.*" => "nullable|exists:warehouse_stock_request_pharmacy_item,id",
            "item_id.*" => "nullable|exists:warehouse_distribusi_barang_farmasi_item,id",
        ]);
        // dd($validatedData2);

        DB::beginTransaction();

        try {
            $db = $warehouseDistribusiBarangPharmacy->findOrFail($id);
            $db->update($validatedData1);
            $asal_gudang_id = $validatedData1["asal_gudang_id"];
            $tujuan_gudang_id = $validatedData1["tujuan_gudang_id"];

            // $validatedData["item_id"] is a key => pair array
            // delete everything from WarehouseDistribusiBarangFarmasiItems
            // where db_id == $pr->id
            // and id IS NOT IN $validatedData["item_id"]
            // because if it is not in $validatedData["item_id"]
            // it means it has been deleted
            if (isset($validatedData2["item_id"]) && count($validatedData2["item_id"]) > 0) {
                WarehouseDistribusiBarangFarmasiItems::where("db_id", $db->id)
                    ->whereNotIn("id", $validatedData2["item_id"])
                    ->delete(); // don't force delete to retain history
            } else {
                WarehouseDistribusiBarangFarmasiItems::where("db_id", $db->id)->delete();
            }

            foreach ($validatedData2["barang_id"] as $index => $barang_id) {
                $satuan_id = $validatedData2["satuan_id"][$index];
                $sri_id = $validatedData2["sri_id"][$index] ?? null;
                $requested_qty = $validatedData2["qty"][$index];
                $keterangan_item = $validatedData2["keterangan_item"][$index] ?? null;
                $attributes = [
                    "db_id" => $db->id,
                    "barang_id" => $barang_id,
                    "satuan_id" => $satuan_id,
                    "qty" => $requested_qty,
                    "keterangan" => $keterangan_item,
                    "sri_id" => $sri_id
                ];

                if ($request->has("item_id") && isset($validatedData2["item_id"][$index])) {
                    $dbi = WarehouseDistribusiBarangFarmasiItems::findorfail($validatedData2["item_id"][$index]);
                    $dbi->update($attributes);
                } else {
                    $dbi = new WarehouseDistribusiBarangFarmasiItems($attributes);
                }

                if ($validatedData1["status"] == "final") {
                    // update stock request item
                    if (isset($sri_id)) {
                        $sri = WarehouseStockRequestPharmacyItems::findOrFail($sri_id);
                        $sri->qty_fulfilled += $requested_qty;
                        if ($sri->qty < $sri->qty_fulfilled) $sri->qty_fulfilled = $sri->qty;
                        $sri->save();
                    }

                    // TODO: LOGIC FOR DISTRIBUTE GOODS
                    $origin_sis = StoredBarangFarmasi::query()->with(["pbi"]);
                    $origin_sis->where("gudang_id", $asal_gudang_id);
                    $origin_sis->whereHas("pbi", function ($q) use ($barang_id, $satuan_id) {
                        // barang and satuan
                        $q->where("barang_id", $barang_id);
                        $q->where("satuan_id", $satuan_id);
                        // ensure tanggal_exp is not in the past
                        $q->whereDate("tanggal_exp", ">=", now())->orWhereNull("tanggal_exp");
                    });
                    $origin_sis = $origin_sis->oldest()->get();

                    // ensure there are enough goods in origin warehouse for requested quantity
                    $available_stock = $origin_sis->sum("qty");
                    if ($available_stock < $requested_qty) {
                        // throw exception
                        throw new \Exception("Stock tidak cukup untuk barang dengan id" . $barang_id);
                    }

                    $transfered = 0;

                    foreach ($origin_sis as $si) {
                        if ($transfered >= $requested_qty) break;

                        // option 1: update existing
                        // check if there is another $si with gudang_id == tujuan_gudang_id
                        // and pbi the same as $si
                        $si_tujuan = StoredBarangFarmasi::query()->where("gudang_id", $tujuan_gudang_id)
                            ->where("pbi_id", $si->pbi_id)
                            ->first();
                        if ($si_tujuan) {
                            $qty = $si->qty;
                            if (($qty + $transfered) > $requested_qty) {
                                $qty = $requested_qty - $transfered;
                            }

                            // update existing
                            $si_tujuan->qty += $qty;
                            $transfered += $qty;
                            $si_tujuan->save();

                            $si->qty -= $qty;
                            if ($si->qty == 0) { // delete if qty is 0
                                $si->forceDelete(); // force delete
                            } else { // update if qty is not 0
                                $si->save();
                            }

                            continue;
                        }

                        // option 2: move the whole $si
                        // if $si->qty + $transfered is still less than $requested_qty
                        // then update the $si->gudang_id to $tujuan_gudang_id
                        if (($si->qty + $transfered) <= $requested_qty) {
                            $si->gudang_id = $tujuan_gudang_id;
                            $si->save();
                            $transfered += $si->qty;
                            continue;
                        }

                        // option 3: split
                        // if $si->qty + $transfered is more than $requested_qty
                        // then create a new $si with the remaining qty
                        // and update the existing $si->qty
                        if (($si->qty + $transfered) > $requested_qty) {
                            $remaining_qty = $requested_qty - $transfered;
                            $si->qty -= $remaining_qty;
                            $si->save();

                            StoredBarangFarmasi::create([
                                "pbi_id" => $si->pbi_id,
                                "gudang_id" => $tujuan_gudang_id,
                                "qty" => $remaining_qty
                            ]);

                            $transfered = $requested_qty;
                            continue;
                        }
                    }
                }

                $dbi->save(); // save or update
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
        return view("pages.simrs.warehouse.distribusi-barang.partials.db-print-pharmacy", [
            "db" => WarehouseDistribusiBarangFarmasi::findorfail($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseDistribusiBarangFarmasi $warehouseDistribusiBarangPharmacy, $id)
    {
        $pr = $warehouseDistribusiBarangPharmacy->findorfail($id);
        if ($pr->status == 'final') {
            return response()->json([
                'success' => false,
                'message' => "DB sudah final, tidak bisa dihapus!"
            ]);
        }

        try {
            $pr->delete();

            return response()->json([
                'success' => true,
                'message' => 'DB berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
