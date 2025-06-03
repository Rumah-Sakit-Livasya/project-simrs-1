<?php

namespace App\Http\Controllers;

use App\Models\ProcurementPurchaseOrderNonPharmacy;
use App\Models\ProcurementPurchaseOrderNonPharmacyItems;
use App\Models\ProcurementPurchaseRequestNonPharmacyItems;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseSupplier;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ProcurementPurchaseOrderNonPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProcurementPurchaseOrderNonPharmacy::query()->with(["items"]);
        $filters = ["kode_po", "approval", "is_auto"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_po')) {
            $query->where('tanggal_pr', $request->tanggal_pr);
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
            $po = $query->orderBy('created_at', 'asc')->get();
        } else {
            // Return all data if no filter is applied
            $po = ProcurementPurchaseOrderNonPharmacy::all();
        }

        return view("pages.simrs.procurement.purchase-order.non-pharmacy", [
            "pos" => $po
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.simrs.procurement.purchase-order.partials.popup-add-po-non-farmasi", [
            "suppliers" => WarehouseSupplier::all(),
            "barangs" => WarehouseBarangNonFarmasi::all()
        ]);
    }
    public function get_items(Request $request)
    {
        $validatedData = $request->validate([
            "sumber_item" => "required|in:npr,pr",
            "tipe_pr" => "required|in:all,normal,urgent"
        ]);

        if ($validatedData["sumber_item"] == "npr") {
            return view("pages.simrs.procurement.purchase-order.partials.table-items-non-pr-non-pharmacy", [
                "items" => WarehouseBarangNonFarmasi::all()
            ]);
        }

        $query = ProcurementPurchaseRequestNonPharmacyItems::query()->with(['pr']);

        // query where column "ordered_qty" is LESS THAN column "approved_qty" OR "ordered_qty" is NULL
        $query->where(function ($query) {
            $query->whereColumn('ordered_qty', '<', 'approved_qty')
                ->orWhereNull('ordered_qty');
        });

        // Only filter by tipe_pr if it's not "all"
        if ($validatedData["tipe_pr"] != "all") {
            $query->whereHas('pr', function ($q) use ($validatedData) {
                $q->where('tipe', $validatedData["tipe_pr"]);
            });
        }


        $pris = $query->get();

        return view("pages.simrs.procurement.purchase-order.partials.table-items-pr-non-pharmacy", [
            "pris" => $pris
        ]);
    }

    private function generate_po_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = ProcurementPurchaseOrderNonPharmacy::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . "/URPO/" . $year . $month;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData1 = $request->validate([
            "tanggal_po" => "required|date",
            "tanggal_kirim" => "nullable|date",
            "pic_terima" => "nullable|string",
            "tipe_top" => "required|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG",
            "top" => "required|in:COD,7HARI,14HARI,21HARI,24HARI,30HARI,37HARI,40HARI,45HARI",
            "user_id" => "required|exists:users,id",
            "ppn" => "required|integer",
            "supplier_id" => "required|exists:warehouse_supplier,id",
            "tipe" => "required|in:normal,urgent",
            "nominal" => "required|integer",
            "status" => "required|in:draft,final,reviewed",
            "keterangan" => "nullable|string",
        ]);

        $validatedData2 = $request->validate([
            "kode_barang" => "required|array",
            "kode_barang.*" => "required|string",
            "nama_barang" => "required|array",
            "nama_barang.*" => "required|string",
            "barang_id" => "required|array",
            "barang_id.*" => "required|exists:warehouse_barang_non_farmasi,id",
            "unit_barang" => "required|array",
            "unit_barang.*" => "required|string",
            "pri_id" => "required|array",
            "pri_id.*" => "nullable|exists:procurement_purchase_request_non_pharmacy_items,id",
            "qty" => "required|array",
            "qty.*" => "required|integer",
            "qty_bonus" => "required|array",
            "qty_bonus.*" => "required|integer",
            "hna" => "required|array",
            "hna.*" => "required|integer",
            "discount_nominal" => "required|array",
            "discount_nominal.*" => "required|integer",
        ]);

        $validatedData1["kode_po"] = $this->generate_po_code();

        DB::beginTransaction();
        try {
            $po = ProcurementPurchaseOrderNonPharmacy::create($validatedData1);

            foreach ($validatedData2["barang_id"] as $key => $barang_id) {
                ProcurementPurchaseOrderNonPharmacyItems::create([
                    "po_id" => $po->id,
                    "pri_id" => $validatedData2["pri_id"][$key],
                    "barang_id" => $validatedData2["barang_id"][$key],
                    "kode_barang" => $validatedData2["kode_barang"][$key],
                    "nama_barang" => $validatedData2["nama_barang"][$key],
                    "unit_barang" => $validatedData2["unit_barang"][$key],
                    "harga_barang" => $validatedData2["hna"][$key],
                    "qty" => $validatedData2["qty"][$key],
                    "qty_bonus" => $validatedData2["qty_bonus"][$key],
                    "discount_nominal" => $validatedData2["discount_nominal"][$key],
                    "subtotal" => ($validatedData2["hna"][$key] * $validatedData2["qty"][$key]) - $validatedData2["discount_nominal"][$key],
                ]);
            }

            if (isset($validatedData2["pri_id"][$key])) {
                $pri = ProcurementPurchaseRequestNonPharmacyItems::find($validatedData2["pri_id"][$key])->first();
                $pri->increment("ordered_qty", $validatedData2["qty"][$key]);
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
        return view("pages.simrs.procurement.purchase-order.partials.po-print-non-pharmacy", [
            "po" => ProcurementPurchaseOrderNonPharmacy::find($id)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProcurementPurchaseOrderNonPharmacy $poocurementPurchaseOrderNonPharmacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcurementPurchaseOrderNonPharmacy $poocurementPurchaseOrderNonPharmacy, $id)
    {
        return view("pages.simrs.procurement.purchase-order.partials.popup-edit-po-non-farmasi", [
            "po" => $poocurementPurchaseOrderNonPharmacy::find($id)->first(),
            "suppliers" => WarehouseSupplier::all(),
            "barangs" => WarehouseBarangNonFarmasi::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProcurementPurchaseOrderNonPharmacy $poocurementPurchaseOrderNonPharmacy, $id)
    {
        $validatedData1 = $request->validate([
            "tanggal_po" => "required|date",
            "tanggal_kirim" => "nullable|date",
            "kode_po" => "required|string",
            "pic_terima" => "nullable|string",
            "tipe_top" => "required|in:SETELAH_TUKAR_FAKTUR,SETELAH_TERIMA_BARANG",
            "top" => "required|in:COD,7HARI,14HARI,21HARI,24HARI,30HARI,37HARI,40HARI,45HARI",
            "user_id" => "required|exists:users,id",
            "ppn" => "required|integer",
            "supplier_id" => "required|exists:warehouse_supplier,id",
            "tipe" => "required|in:normal,urgent",
            "nominal" => "required|integer",
            "status" => "required|in:draft,final,reviewed",
            "keterangan" => "nullable|string",
        ]);

        $validatedData2 = $request->validate([
            "kode_barang" => "required|array",
            "kode_barang.*" => "required|string",
            "nama_barang" => "required|array",
            "nama_barang.*" => "required|string",
            "barang_id" => "required|array",
            "barang_id.*" => "required|exists:warehouse_barang_non_farmasi,id",
            "unit_barang" => "required|array",
            "unit_barang.*" => "required|string",
            "pri_id" => "required|array",
            "pri_id.*" => "nullable|exists:procurement_purchase_request_non_pharmacy_items,id",
            "qty" => "required|array",
            "qty.*" => "required|integer",
            "qty_bonus" => "required|array",
            "qty_bonus.*" => "required|integer",
            "hna" => "required|array",
            "hna.*" => "required|integer",
            "discount_nominal" => "required|array",
            "discount_nominal.*" => "required|integer",
            "item_id" => "nullable|array",
            "item_id.*" => "integer"
        ]);

        DB::beginTransaction();
        try {
            $po = $poocurementPurchaseOrderNonPharmacy->where("id", $id)->firstOrFail();
            $po->update($validatedData1);

            // $validatedData["item_id"] is a key => pair array
            // delete everything from ProcurementPurchaseOrderNonPharmacyItems
            // where po_id == $po->id
            // and id IS NOT IN $validatedData["item_id"]
            // because if it is not in $validatedData["item_id"]
            // it means it has been deleted
            if (count($validatedData2["item_id"]) > 0) {
                $pois = ProcurementPurchaseOrderNonPharmacyItems::where("po_id", $po->id)
                    ->whereNotIn("id", $validatedData2["item_id"])
                    ->get();

                $pois->each(function ($poi) {
                    if ($poi->pri_id) {
                        $pri = ProcurementPurchaseRequestNonPharmacyItems::find($poi->pri_id);
                        $pri = ProcurementPurchaseRequestNonPharmacyItems::find($poi->pri_id)->first();
                        $pri->decrement("ordered_qty", $poi->qty);
                        $pri->save();
                    }
                    $poi->delete(); // don't force delete to retain history
                });
            }

            foreach ($validatedData2["barang_id"] as $key => $barang_id) {
                $attributes = [
                    "po_id" => $po->id,
                    "pri_id" => $validatedData2["pri_id"][$key],
                    "barang_id" => $validatedData2["barang_id"][$key],
                    "kode_barang" => $validatedData2["kode_barang"][$key],
                    "nama_barang" => $validatedData2["nama_barang"][$key],
                    "unit_barang" => $validatedData2["unit_barang"][$key],
                    "harga_barang" => $validatedData2["hna"][$key],
                    "qty" => $validatedData2["qty"][$key],
                    "qty_bonus" => $validatedData2["qty_bonus"][$key],
                    "discount_nominal" => $validatedData2["discount_nominal"][$key],
                    "subtotal" => ($validatedData2["hna"][$key] * $validatedData2["qty"][$key]) - $validatedData2["discount_nominal"][$key],
                ];

                if ($request->has("item_id") && isset($validatedData2["item_id"][$key])) {
                    $poi = ProcurementPurchaseOrderNonPharmacyItems::find($validatedData2["item_id"][$key]);

                    // check if there's difference in the new "qty" and the stored "qty"
                    if ($poi->qty != $validatedData2["qty"][$key]) {
                        $diff = $validatedData2["qty"][$key] - $poi->qty;
                        $pri = ProcurementPurchaseRequestNonPharmacyItems::find($validatedData2["pri_id"][$key])->first();
                        $pri->increment("ordered_qty", $diff);
                        $poi->update($attributes);
                    } else {
                        $poi->update($attributes);
                    }
                } else {
                    $poi = new ProcurementPurchaseOrderNonPharmacyItems($attributes);
                    if (isset($validatedData2["pri_id"][$key])) {
                        $pri = ProcurementPurchaseRequestNonPharmacyItems::find($validatedData2["pri_id"][$key])->first();
                        $pri->increment("ordered_qty", $validatedData2["qty"][$key]);
                    }
                }

                $poi->save();
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
    public function destroy(ProcurementPurchaseOrderNonPharmacy $procurementPurchaseOrderNonPharmacy, $id)
    {
        $po = $procurementPurchaseOrderNonPharmacy->find($id)->first();
        if ($po->status != 'draft') {
            return response()->json([
                'success' => false,
                'message' => "PO sudah final, tidak bisa dihapus!"
            ]);
        }

        try {
            $po->delete();

            return response()->json([
                'success' => true,
                'message' => 'PO berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
