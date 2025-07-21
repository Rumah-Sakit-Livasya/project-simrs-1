<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Http\Request;

class WarehouseReportKartuStock extends Controller
{
    public function index(Request $request)
    {
        if ($request && $request->has("tanggal")) {
            $data = $request->validate([
                "tanggal" => "required|string",
                "gudang_id" => "nullable|exists:warehouse_master_gudang,id",
                "satuan_barang_type" => "required|string"
            ]);

            $dateRange = explode(' - ', $data['tanggal']);
            if (count($dateRange) !== 2) {
                return back()->with("error", "Format tanggal tidak valid");
            }

            $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
            $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));

            // split $data["satuan_barang_type"] by "_"
            // and store each to satuan_id, barang_id, and type
            $satuanBarangType = explode('_', $data["satuan_barang_type"]);
            if (count($satuanBarangType) !== 3) {
                return back()->with("error", "Format satuan barang tidak valid");
            }
            $satuan_id = $satuanBarangType[0];
            $barang_id = $satuanBarangType[1];
            $type = $satuanBarangType[2]; // type can be "f" or "nf"

            if ($type == "f") {
                $barang = WarehouseBarangFarmasi::findOrFail($barang_id);
            } elseif ($type == "nf") {
                $barang = WarehouseBarangNonFarmasi::findOrFail($barang_id);
            } else {
                return back()->with("error", "Tipe satuan barang tidak valid");
            }

            $query = StockTransaction::query()->with(["stock", "stock.pbi", "before_gudang", "after_gudang", "source", "user"]);
            $query->whereBetween("created_at", [$startDate, $endDate]);

            if (isset($data["gudang_id"]) && $data["gudang_id"] !== "") {
                $query->where(function ($q) use ($data) {
                    $q->orWhere("before_gudang_id", $data["gudang_id"])
                        ->orWhere("after_gudang_id", $data["gudang_id"]);
                });
            }

            $query->where(function ($query) use ($satuan_id, $barang_id) {
                $query->whereHasMorph('stock', [StoredBarangFarmasi::class, StoredBarangNonFarmasi::class], function ($q) use ($satuan_id, $barang_id) {
                    $q->whereHas('pbi', function ($q2) use ($satuan_id, $barang_id) {
                        $q2->where('satuan_id', $satuan_id)
                            ->where('barang_id', $barang_id);
                    });
                });
            });

            return view("pages.simrs.warehouse.report.kartu-stock", [
                "gudangs" => WarehouseMasterGudang::all(),
                "barangs" => collect(array_merge(
                    WarehouseBarangFarmasi::with(["satuan", "satuan_tambahan"])
                        ->get()
                        ->map(function ($item) {
                            $item->type = 'f';
                            return $item;
                        })->all(),
                    WarehouseBarangNonFarmasi::with(["satuan", "satuan_tambahan"])
                        ->get()
                        ->map(function ($item) {
                            $item->type = 'nf';
                            return $item;
                        })->all(),
                )),
                "logs" => $query->get()->sortByDesc('created_at'),
                "barang" => $barang_id ? $barang : null,
                "satuan" => $satuan_id ? WarehouseSatuanBarang::findOrFail($satuan_id) : null,
            ]);
        } else {
            return view("pages.simrs.warehouse.report.kartu-stock", [
                "gudangs" => WarehouseMasterGudang::all(),
                "barangs" => collect(array_merge(
                    WarehouseBarangFarmasi::with(["satuan", "satuan_tambahan"])
                        ->get()
                        ->map(function ($item) {
                            $item->type = 'f';
                            return $item;
                        })->all(),
                    WarehouseBarangNonFarmasi::with(["satuan", "satuan_tambahan"])
                        ->get()
                        ->map(function ($item) {
                            $item->type = 'nf';
                            return $item;
                        })->all(),
                ))
            ]);
        }
    }
}
