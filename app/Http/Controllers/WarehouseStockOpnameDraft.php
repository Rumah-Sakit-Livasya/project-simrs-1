<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseStockOpnameGudang;
use App\Models\WarehouseStockOpnameItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockOpnameDraft extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.index", [
            "ogs" => WarehouseStockOpnameGudang::whereNull("finish")->get(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    public function get_opname_item_movement($type, $opname_id, $si_id)
    {
        $query = StoredBarangFarmasi::query();
        if ($type == "nf") {
            $query = StoredBarangNonFarmasi::query();
        }

        $item = $query->findOrFail($si_id);
        $opname = WarehouseStockOpnameGudang::findOrFail($opname_id);
        $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
        $movement = 0;
        foreach ($audits as $id => $audit) {
            $old = $audit->old_values;
            $new = $audit->new_values;

            if (isset($old["qty"]) && isset($new["qty"])) {
                $movement += $new["qty"] - $old["qty"];
            }
        }

        return response()->json(["movement" => $movement]);
    }

    public function get_opname_items($id)
    {
        // first, get the opname
        $opname = WarehouseStockOpnameGudang::findOrFail($id);

        // second, gather all items where the gudang_id == $opname->gudang->id
        $items_f = StoredBarangFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();
        $items_nf = StoredBarangNonFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();

        // third, from audits table, track movements
        // where the time is above $opname->start
        // and assign attributes named "frozen", "movement", and "type"
        foreach ($items_f as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty + $movement;
            $item->movement = $movement;
            $item->type = "f";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty - $movement;
            $item->movement = $movement;
            $item->type = "nf";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->toArray(), $items_nf->toArray());

        usort($items, function ($a, $b) {
            return strcmp($a['pbi']['nama_barang'], $b['pbi']['nama_barang']); // Accessing 'pbi' as an index in the array
        });

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has("drafts")) {
            $request["drafts"] = json_decode($request["drafts"]);
        } else {
            // return bad request
            return response()->json(["message" => "Draft is required"], 400);
        }

        $request->validate([
            "drafts" => "required|array",
            "sog_id" => "required|exists:warehouse_stock_opname_gudang,id",
            "column" => "required|string",
            "user_id" => "required|exists:users,id"
        ]);

        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($request["drafts"]); $i++) {
                $draft = $request["drafts"][$i];

                // check if there's WarehouseStockOpnameItems
                // with sog_id == $request["sog_id"]
                // and $request["column"] == $draft["si_id"]
                $warehouseStockOpnameItems = WarehouseStockOpnameItems::where("sog_id", $request["sog_id"])
                    ->where($request["column"], $draft->si_id)
                    ->first();

                if ($warehouseStockOpnameItems) {
                    // check if status is final or draft
                    if ($warehouseStockOpnameItems->status == "final") {
                        continue; // ignore updating, and don't create new
                    }

                    // update the warehouseStockOpnameItems
                    $warehouseStockOpnameItems->update([
                        "qty" => $draft->qty,
                        "keterangan" => $draft->keterangan,
                        "user_id" => $request["user_id"]
                    ]);
                } else {
                    // create new warehouseStockOpnameItems
                    WarehouseStockOpnameItems::create([
                        "sog_id" => $request["sog_id"],
                        $request["column"] => $draft->si_id,
                        "qty" => $draft->qty,
                        "keterangan" => $draft->keterangan,
                        "user_id" => $request["user_id"]
                    ]);
                }
            }

            DB::commit();
            return response()->json(["message" => "Data berhasil disimpan"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function print_selisih($sog_id)
    {
        // first, get the opname
        $opname = WarehouseStockOpnameGudang::findOrFail($sog_id);

        // second, gather all items where the gudang_id == $opname->gudang->id
        $items_f = StoredBarangFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();
        $items_nf = StoredBarangNonFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();

        // third, from audits table, track movements
        // where the time is above $opname->start
        // and assign attributes named "frozen", "movement", and "type"
        foreach ($items_f as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty + $movement;
            $item->movement = $movement;
            $item->type = "f";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty - $movement;
            $item->movement = $movement;
            $item->type = "nf";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->all(), $items_nf->all());

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.partials.so-print-selisih", [
            "items" => $items,
            "sog" => $opname
        ]);
    }

    public function print_so($sog_id)
    {
        // first, get the opname
        $opname = WarehouseStockOpnameGudang::findOrFail($sog_id);

        // second, gather all items where the gudang_id == $opname->gudang->id
        $items_f = StoredBarangFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();
        $items_nf = StoredBarangNonFarmasi::with(["pbi", "pbi.item", "pbi.satuan", "pbi.pb"])->where("gudang_id", $opname->gudang->id)->get();

        // third, from audits table, track movements
        // where the time is above $opname->start
        // and assign attributes named "frozen", "movement", and "type"
        foreach ($items_f as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty + $movement;
            $item->movement = $movement;
            $item->type = "f";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
            $movement = 0;
            foreach ($audits as $id => $audit) {
                $old = $audit->old_values;
                $new = $audit->new_values;

                if (isset($old["qty"]) && isset($new["qty"])) {
                    $movement += $new["qty"] - $old["qty"];
                }
            }
            $item->frozen = $item->qty - $movement;
            $item->movement = $movement;
            $item->type = "nf";
            $item->opname = null;

            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            if (isset($item_opname)) {
                $item->opname = $item_opname;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->all(), $items_nf->all());

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.draft.partials.so-print-so", [
            "items" => $items,
            "sog" => $opname
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
