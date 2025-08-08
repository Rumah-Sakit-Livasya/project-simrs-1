<?php

namespace App\Http\Controllers;

use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseStockOpnameGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockOpnameGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.revaluasi-stock.stock-opname.gudang-opname.index", [
            "gudangs" => WarehouseMasterGudang::all(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseStockOpnameGudang $warehouseStockOpnameGudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseStockOpnameGudang $warehouseStockOpnameGudang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseStockOpnameGudang $warehouseStockOpnameGudang)
    {
        $validatedData = $request->validate([
            "user_id" => "required|exists:users,id",
            "opname.*" => "nullable|exists:warehouse_master_gudang,id",
        ]);
        // dd($request->all());

        DB::beginTransaction();

        try {
            if (!isset($validatedData["opname"])) {
                // finish all ongoing opname
                // by inserting current datetime to column finish
                // and finish_user_id = $validatedData["user_id"]
                // to all column where finish == null
                $warehouseStockOpnameGudang
                    ->whereNull("finish")
                    ->update(["finish" => now(), "finish_user_id" => $validatedData["user_id"]]);

                // TODO: create logic to apply changes
                // TODO: create logic to apply changes
                // TODO: create logic to apply changes

                DB::commit();
                return back()->with('success', 'Data berhasil disimpan');
            }

            // finish all ongoing opname
            // that is not in the $validatedData["opname"]
            $warehouseStockOpnameGudang
                ->whereNull("finish")
                ->whereNotIn("gudang_id", array_keys($validatedData["opname"]))
                ->update(["finish" => now(), "finish_user_id" => $validatedData["user_id"]]);

            // TODO: create logic to apply changes
            // TODO: create logic to apply changes
            // TODO: create logic to apply changes

            // start new opname for each gudang_id in $validatedData["opname"] that is not ongoing opname
            foreach ($validatedData["opname"] as $gudang_id => $true) {
                // check if there is no ongoing opname with gudang_id == $gudang_id
                if (!$warehouseStockOpnameGudang->where("gudang_id", $gudang_id)->whereNull("finish")->exists()) {
                    // create new opname
                    $warehouseStockOpnameGudang->create([
                        "gudang_id" => $gudang_id,
                        "start" => now(),
                        "start_user_id" => $validatedData["user_id"],
                        "finish" => null,
                        "finish_user_id" => null,
                    ]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseStockOpnameGudang $warehouseStockOpnameGudang)
    {
        //
    }
}
