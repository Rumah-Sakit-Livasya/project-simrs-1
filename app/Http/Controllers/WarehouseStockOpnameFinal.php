<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseStockOpnameGudang;
use App\Models\WarehouseStockOpnameItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockOpnameFinal extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.index", [
            "ogs" => WarehouseStockOpnameGudang::whereNull("finish")->get(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
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
            $item->type = "f";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $item->type = "nf";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->toArray(), $items_nf->toArray());

        // loop $items as $item
        // remove from $items if $item->opname == null
        // final only shows draft / final, no uncounted
        foreach ($items as $key => $item) {
            if ($item['opname'] == null) {
                unset($items[$key]);
            }
        }

        usort($items, function ($a, $b) {
            return strcmp($a['pbi']['nama_barang'], $b['pbi']['nama_barang']); // Accessing 'pbi' as an index in the array
        });

        return response()->json($items);
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
            $item->type = "f";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $item->type = "nf";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->all(), $items_nf->all());

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.partials.so-print-selisih", [
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
            $item->type = "f";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // do the same for $items_nf
        foreach ($items_nf as $id => $item) {
            $item->type = "nf";
            $item->opname = null;
            $item_opname = WarehouseStockOpnameItems::where("sog_id", $opname->id)->where("si_" . $item->type . "_id", $item->id)->first();
            $audits = $item->audits()->where("created_at", ">", $opname->start);

            if (isset($item_opname)) {
                $item->opname = $item_opname;

                if ($item->opname->status == "final") {
                    $audits = $audits->where("created_at", "<", $item->opname->updated_at);
                    $last_audits = $item->audits()
                        ->where("created_at", ">", $opname->start)
                        ->where("created_at", "<=", $item->opname->updated_at)
                        ->orderBy("created_at", "desc")
                        ->get();

                    foreach ($last_audits as $id => $audit) {
                        $old = $audit->old_values;
                        $new = $audit->new_values;

                        if (isset($old["qty"]) && isset($new["qty"])) {
                            $discount_qty = $new["qty"] - $old["qty"];
                            break;
                        }
                    }
                }
            }

            $audits = $audits->get();
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

            if (isset($discount_qty)) {
                $item->frozen -= $discount_qty;
            }
        }

        // fourth, combine both items into an array
        // and sort by $item->pbi->nama_barang
        $items = array_merge($items_f->all(), $items_nf->all());

        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.final.partials.so-print-so", [
            "items" => $items,
            "sog" => $opname
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return dd($request->all());
        $validatedData = $request->validate([
            "sog_id" => "required|exists:warehouse_stock_opname_gudang,id",
            "user_id" => "required|exists:users,id",
            "sio_id.*" => "required|exists:warehouse_stock_opname_item,id"
        ]);



        DB::beginTransaction();
        try {
            $opname = WarehouseStockOpnameGudang::findOrFail($validatedData["sog_id"]);

            if (isset($opname->finish)) {
                throw new \Exception("Opname sudah selesai");
            }

            foreach ($validatedData["sio_id"] as $key => $sio_id) {
                $sio = WarehouseStockOpnameItems::findOrFail($sio_id);
                if ($sio->status == "final") continue;

                $item = $sio->stored;
                $audits = $item->audits()->where("created_at", ">", $opname->start)->get();
                $movement = 0;
                foreach ($audits as $id => $audit) {
                    $old = $audit->old_values;
                    $new = $audit->new_values;

                    if (isset($old["qty"]) && isset($new["qty"])) {
                        $movement += $new["qty"] - $old["qty"];
                    }
                }

                if ($sio->qty + $movement < 0) {
                    throw new \Exception("Stock tidak bisa kurang dari 0"); // throw exception if qty is not enough
                }

                $sio->status = "final";
                $sio->save();

                // apply change to stored item
                $item->qty = $sio->qty + $movement;
                $item->save();
            }

            DB::commit(); // commit transaction if no error
            return response()->json(["success" => true, "message" => "Data berhasil disimpan"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 500);
        }
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
