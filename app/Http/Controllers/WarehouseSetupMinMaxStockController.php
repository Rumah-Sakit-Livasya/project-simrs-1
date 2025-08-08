<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseSetupMinMaxStock;
use Illuminate\Http\Request;

class WarehouseSetupMinMaxStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseSetupMinMaxStock::query();
        $filters = ["gudang_id", "nama_barang"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $smmss = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $smmss = WarehouseSetupMinMaxStock::all();
        }

        return view("pages.simrs.warehouse.master-data.setup-min-max-stock", [
            "smmss" => $smmss,
            "gudangs" => WarehouseMasterGudang::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang_farmasi = WarehouseBarangFarmasi::all();
        $barang_non_farmasi = WarehouseBarangNonFarmasi::all();

        // combine both into one variable
        $barangs = $barang_farmasi->concat($barang_non_farmasi);
        return view("pages.simrs.warehouse.master-data.setup-min-max-stock-create", [
            "gudangs" => WarehouseMasterGudang::all(),
            "satuans" => WarehouseSatuanBarang::all(),
            "barangs" => $barangs,
            "barang_farmasi" => $barang_farmasi,
            "barang_non_farmasi" => $barang_non_farmasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            "gudang_id" => "required|integer|exists:warehouse_master_gudang,id",
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|integer',
            'barang_type' => 'required|array',
            'barang_type.*' => 'required|in:Farmasi,NonFarmasi',
            'min' => 'required|array',
            'min.*' => 'required|integer',
            'max' => 'required|array',
            'max.*' => 'required|integer'
        ]);

        if ($request->has("mms_id")) {
            $validatedData["mms_id"] = $request->mms_id;

            // $validatedData["mms_id"] is a key => pair array
            // delete everything from WarehouseSetupMinMaxStock
            // where gudang_id == $validatedData["gudang_id"]
            // and id IS NOT IN $validatedData["mms_id"]
            // because if it is not in $validatedData["mms_id"]
            // it means it has been deleted
            if (count($validatedData["mms_id"]) > 0) {
                WarehouseSetupMinMaxStock::where('gudang_id', $validatedData["gudang_id"])
                    ->whereNotIn('id', $validatedData["mms_id"])
                    ->forceDelete(); // force delete to avoid data inflation
            }
        }

        foreach ($validatedData["barang_id"] as $index => $barangId) {
            if ($request->has("mms_id") && isset($validatedData["mms_id"][$index])) {
                $mms = WarehouseSetupMinMaxStock::find($validatedData["mms_id"][$index]);
            } else {
                $mms = new WarehouseSetupMinMaxStock();
            }

            $mms["gudang_id"] = $validatedData["gudang_id"];
            $mms["min"] = $validatedData["min"][$index];
            $mms["max"] = $validatedData["max"][$index];

            if ($validatedData["barang_type"][$index] == "Farmasi") {
                $mms["barang_f_id"] = $barangId;
            } else {
                $mms["barang_nf_id"] = $barangId;
            }

            $mms->save();
        }

        return redirect()->back()->with('success', 'Min Max Stock berhasil disimpan!');
    }

    public function get_gudang($id)
    {
        // from WarehouseSetupMinMaxStock
        // get all data with gudang_id == $id
        $gudang = WarehouseSetupMinMaxStock::where('gudang_id', $id)->get();
        return response()->json($gudang);
    }

}
