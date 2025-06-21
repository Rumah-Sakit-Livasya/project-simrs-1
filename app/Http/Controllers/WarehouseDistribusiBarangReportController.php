<?php

namespace App\Http\Controllers;

use App\Models\WarehouseDistribusiBarangFarmasi;
use App\Models\WarehouseDistribusiBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;

class WarehouseDistribusiBarangReportController extends Controller
{
    public function index()
    {
        return view("pages.simrs.warehouse.distribusi-barang.report.index", [
            "gudang_asals" => WarehouseMasterGudang::where("warehouse", 1)->get(),
            "gudangs" => WarehouseMasterGudang::all()
        ]);
    }

    public function show($json)
    {
        $json = json_decode($json, true);
        // replace all empty string with null
        array_walk_recursive($json, function (&$value) {
            if ($value === "") {
                $value = null;
            }
        });
        // dd($type, $json);

        // array:8 [▼ // app\Http\Controllers\WarehouseDistribusiBarangReportController.php:32
        // "tanggal_db" => "2025-06-01 - 2025-06-17"
        // "tanggal_faktur" => "2025-06-01 - 2025-06-17"
        // "supplier_id" => null
        // "kategori_id" => null
        // "tipe_db" => null
        // "jenis" => null
        // "kode_db" => null
        // "nama_barang" => null
        // ]

        $query1 = WarehouseDistribusiBarangFarmasi::query()->with(["items", "items.barang"]);
        $query2 = WarehouseDistribusiBarangNonFarmasi::query()->with(["items", "items.barang"]);
        $startDate = "";
        $endDate = "";

        if (isset($json["tanggal_db"])) {
            $dateRange = explode(' - ', $json["tanggal_db"]);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query1->whereBetween('tanggal_db', [$startDate, $endDate]);
                $query2->whereBetween('tanggal_db', [$startDate, $endDate]);
            }
        } else {
            // return an error as view
            return abort(400, "Tanggal distribusi harus diisi"); // Bad Request
        }

        if (isset($json["tujuan_gudang_id"])) {
            $query1->where("tujuan_gudang_id", $json["tujuan_gudang_id"]);
            $query2->where("tujuan_gudang_id", $json["tujuan_gudang_id"]);
        }

        if (isset($json["asal_gudang_id"])) {
            $query1->where("asal_gudang_id", $json["asal_gudang_id"]);
            $query2->where("asal_gudang_id", $json["asal_gudang_id"]);
        }

        if (isset($json["kode_db"])) {
            $query1->where("kode_db", $json["kode_db"]);
            $query2->where("kode_db", $json["kode_db"]);
        }

        if (isset($json["nama_barang"])) {
            // with items
            $query1->whereHas("items.barang", function ($q) use ($json) {
                $q->where("nama_barang", "like", "%" . $json["nama_barang"] . "%");
            });
            $query2->whereHas("items.barang", function ($q) use ($json) {
                $q->where("nama_barang", "like", "%" . $json["nama_barang"] . "%");
            });
        }

        if (isset($json["jenis"])) {
            if ($json["jenis"] == "f") {
                $dbs = $query1->get();
            } else {
                $dbs = $query2->get();
            }
        } else {
            $dbs1 = $query1->get()->all();
            $dbs2 = $query2->get()->all();
            $dbs = collect(array_merge($dbs1, $dbs2));
        }

        return view("pages.simrs.warehouse.distribusi-barang.report.show", compact("dbs", "startDate", "endDate"));
    }
}
