<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseDistribusiBarangFarmasi;
use App\Models\WarehouseDistribusiBarangFarmasiItems;
use App\Models\WarehousePenerimaanBarangFarmasiItems;
use App\Models\WarehouseStockRequestPharmacy;
use App\Models\WarehouseStockRequestPharmacyItems;
use App\Services\CreateStockArguments;
use App\Services\GoodsStockService;
use App\Services\GoodsType;
use App\Services\IncreaseDecreaseStockArguments;
use App\Services\MoveStockArguments;
use App\Services\TransferStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseDistribusiBarangFarmasiController extends Controller
{
    protected GoodsStockService $goodsStockService;

    public function __construct(GoodsStockService $goodsStockService)
    {
        $this->goodsStockService = $goodsStockService;
        $this->goodsStockService->controller = $this::class;
    }

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
            $dbs = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $dbs = WarehouseDistribusiBarangFarmasi::all();
        }

        return view("pages.simrs.warehouse.distribusi-barang.pharmacy", [
            "gudangs" => WarehouseMasterGudang::all(),
            "gudang_asals" => WarehouseMasterGudang::where("aktif", 1)->where("apotek", 1)->where("warehouse", 1)->get(),
            "dbs" => $dbs
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

    private function generateDbCode()
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
        $db = $warehouseDistribusiBarangPharmacy->findOrFail($id);
        foreach ($db->items as $item) {
            $si = StoredBarangFarmasi::query()->with(["pbi"]);
            $si->where("gudang_id", $db->asal_gudang_id);
            $si->where("qty", ">", 0);
            $si->whereHas("pbi", function ($q) use ($item) {
                $q->where("barang_id", $item->barang_id);
                $q->where("satuan_id", $item->satuan_id);
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
        $validatedData1 = $request->validate([
            "user_id" => "required|exists:users,id",
            "asal_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tujuan_gudang_id" => "required|exists:warehouse_master_gudang,id",
            "tanggal_db" => "required|date",
            "sr_id" => "nullable|exists:warehouse_stock_request_pharmacy,id",
            "status" => "required|in:draft,final",
            "keterangan" => "nullable|string"
        ]);

        $validatedData2 = $request->validate([
            "barang_id.*" => "required|exists:warehouse_barang_farmasi,id",
            "satuan_id.*" => "required|exists:warehouse_satuan_barang,id",
            "qty.*" => "required|integer",
            "keterangan_item.*" => "nullable|string",
            "sri_id.*" => "nullable|exists:warehouse_stock_request_pharmacy_item,id"
        ]);

        $validatedData1["kode_db"] = $this->generateDbCode();
        DB::beginTransaction();

        try {
            $db = WarehouseDistribusiBarangFarmasi::create($validatedData1);
            $asal_gudang_id = $validatedData1["asal_gudang_id"];
            $tujuan_gudang_id = $validatedData1["tujuan_gudang_id"];
            $user = User::findOrFail($validatedData1["user_id"]);

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
                    $this->processDistribution($user, $db, $asal_gudang_id, $tujuan_gudang_id, $barang_id, 
                        $satuan_id, $requested_qty, $sri_id);
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

        $validatedData2 = $request->validate([
            "barang_id.*" => "required|exists:warehouse_barang_farmasi,id",
            "satuan_id.*" => "required|exists:warehouse_satuan_barang,id",
            "qty.*" => "required|integer",
            "keterangan_item.*" => "nullable|string",
            "sri_id.*" => "nullable|exists:warehouse_stock_request_pharmacy_item,id",
            "item_id.*" => "nullable|exists:warehouse_distribusi_barang_farmasi_item,id",
        ]);

        DB::beginTransaction();

        try {
            $db = $warehouseDistribusiBarangPharmacy->findOrFail($id);
            $db->update($validatedData1);
            $asal_gudang_id = $validatedData1["asal_gudang_id"];
            $tujuan_gudang_id = $validatedData1["tujuan_gudang_id"];
            $user = User::findOrFail($validatedData1["user_id"]);

            // Delete items that are not in the request
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
                    $dbi = WarehouseDistribusiBarangFarmasiItems::findOrFail($validatedData2["item_id"][$index]);
                    $dbi->update($attributes);
                } else {
                    $dbi = new WarehouseDistribusiBarangFarmasiItems($attributes);
                }

                if ($validatedData1["status"] == "final") {
                    $this->processDistribution($user, $db, $asal_gudang_id, $tujuan_gudang_id, $barang_id, 
                        $satuan_id, $requested_qty, $sri_id);
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

    /**
     * Process the distribution logic for goods
     */
    private function processDistribution(User $user, WarehouseDistribusiBarangFarmasi $db, 
        int $asal_gudang_id, int $tujuan_gudang_id, int $barang_id, int $satuan_id, int $requested_qty, ?int $sri_id)
    {
        // update stock request item
        if (isset($sri_id)) {
            $sri = WarehouseStockRequestPharmacyItems::findOrFail($sri_id);
            $sri->qty_fulfilled += $requested_qty;
            if ($sri->qty < $sri->qty_fulfilled) $sri->qty_fulfilled = $sri->qty;
            $sri->save();
        }

        // Get origin stock items
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
            throw new \Exception("Stock tidak cukup untuk barang dengan id " . $barang_id);
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

                // use the GoodsStockService
                $args = new TransferStockArguments($user, $db, $si, $si_tujuan, $qty);
                $this->goodsStockService->transferStock($args);
                $transfered += $qty;

                continue;
            }

            // option 2: move the whole $si
            // if $si->qty + $transfered is still less than $requested_qty
            // then update the $si->gudang_id to $tujuan_gudang_id
            if (($si->qty + $transfered) <= $requested_qty) {
                // use the GoodsStockService
                $warehouse = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);
                $args = new MoveStockArguments($user, $db, $si, $warehouse);
                $this->goodsStockService->moveStock($args);
                $transfered += $si->qty;

                continue;
            }

            // option 3: split
            // if $si->qty + $transfered is more than $requested_qty
            // then create a new $si with the remaining qty
            // and update the existing $si->qty
            if (($si->qty + $transfered) > $requested_qty) {
                $remaining_qty = $requested_qty - $transfered;
                $transfered = $requested_qty;

                // use the GoodsStockService
                // first, decrease the current stock
                $decrease_args = new IncreaseDecreaseStockArguments($user, $db, $si, $remaining_qty);
                $this->goodsStockService->decreaseStock($decrease_args);

                // then, create a new stock
                $type = GoodsType::Pharmacy;
                $warehouse = WarehouseMasterGudang::findOrFail($tujuan_gudang_id);
                $pbi = WarehousePenerimaanBarangFarmasiItems::findOrFail($si->pbi_id);
                $new_stock_args = new CreateStockArguments($user, $db, $type, $warehouse, $pbi, $remaining_qty);
                $this->goodsStockService->createStock($new_stock_args);

                continue;
            }
        }
    }

    public function print($id)
    {
        return view("pages.simrs.warehouse.distribusi-barang.partials.db-print-pharmacy", [
            "db" => WarehouseDistribusiBarangFarmasi::findOrFail($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseDistribusiBarangFarmasi $warehouseDistribusiBarangPharmacy, $id)
    {
        $db = $warehouseDistribusiBarangPharmacy->findOrFail($id);
        if ($db->status == 'final') {
            return response()->json([
                'success' => false,
                'message' => "DB sudah final, tidak bisa dihapus!"
            ]);
        }

        try {
            $db->delete();

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
