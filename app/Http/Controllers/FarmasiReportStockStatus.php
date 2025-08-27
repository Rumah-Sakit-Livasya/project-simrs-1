<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;

class FarmasiReportStockStatus extends Controller
{
    public function index(Request $request)
    {
        $query1 = WarehouseBarangFarmasi::query()->with(["stored_items", "stored_items.pbi", "stored_items.gudang", "golongan", "kategori", "satuan"]);
        $query2 = WarehouseBarangNonFarmasi::query()->with(["stored_items", "stored_items.pbi", "stored_items.gudang", "golongan", "kategori", "satuan"]);
        $filters = ["kategori_id", "golongan_id", "nama"];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query1->where($filter, 'like', '%' . $request->$filter . '%');
                $query2->where($filter, 'like', '%' . $request->$filter . '%');
            }
        }

        if ($request->has("tanggal_end") && $request->get("tanggal_end") !== null) {
            // compare date with column created_at
            $query1->whereDate('created_at', '<=', $request->tanggal_end);
            $query2->whereDate('created_at', '<=', $request->tanggal_end);
        }

        if ($request->has("jenis") && $request->get("jenis") !== null) {
            $jenis = $request->get("jenis");
            if ($jenis == "f") {
                $items = $query1->get();
            } else if ($jenis == "nf") {
                $items = $query2->get();
            } else {
                $items1 = $query1->get();
                $items2 = $query2->get();
                $items = collect($items1)->merge($items2);
            }
        } else {
            $items1 = $query1->get();
            $items2 = $query2->get();
            $items = collect($items1)->merge($items2);
        }

        if ($request->filled('gudang_id')) {
            $gudangId = $request->gudang_id;

            $items = $items->filter(function ($item) use ($gudangId) {
                // returns true if the relationship has that gudang
                return $item->gudangs->contains('id', $gudangId);
            });

            // If you need a fresh Collection with reset keys:
            $items = $items->values();
        }
        return view("pages.simrs.farmasi.report.stock-status", [
            "gudangs" => WarehouseMasterGudang::all(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "items" => $items
        ]);
    }
}
