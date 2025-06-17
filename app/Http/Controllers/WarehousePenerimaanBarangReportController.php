<?php

namespace App\Http\Controllers;

use App\Models\WarehouseKategoriBarang;
use App\Models\WarehousePabrik;
use App\Models\WarehousePenerimaanBarangFarmasi;
use App\Models\WarehousePenerimaanBarangNonFarmasi;
use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;

class WarehousePenerimaanBarangReportController extends Controller
{
    public function index()
    {
        return view("pages.simrs.warehouse.penerimaan-barang.report.index", [
            "suppliers" => WarehouseSupplier::all(),
            "pabriks" => WarehousePabrik::all(),
            "kategoris" => WarehouseKategoriBarang::all()
        ]);
    }

    public function show($type, $json)
    {
        $json = json_decode($json, true);
        // replace all empty string with null
        array_walk_recursive($json, function (&$value) {
            if ($value === "") {
                $value = null;
            }
        });
        // dd($type, $json);

        // array:8 [▼ // app\Http\Controllers\WarehousePenerimaanBarangReportController.php:32
        // "tanggal_terima" => "2025-06-01 - 2025-06-17"
        // "tanggal_faktur" => "2025-06-01 - 2025-06-17"
        // "supplier_id" => null
        // "kategori_id" => null
        // "tipe_terima" => null
        // "jenis" => null
        // "kode_po" => null
        // "nama_barang" => null
        // ]

        $query1 = WarehousePenerimaanBarangFarmasi::query()->with("items");
        $query2 = WarehousePenerimaanBarangNonFarmasi::query()->with("items");
        $startDate = "";
        $endDate = "";

        if (isset($json["tanggal_terima"])) {
            $dateRange = explode(' - ', $json["tanggal_terima"]);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query1->whereBetween('tanggal_terima', [$startDate, $endDate]);
                $query2->whereBetween('tanggal_terima', [$startDate, $endDate]);
            }
        } else {
            // return an error as view
            return abort(400, "Tanggal terima harus diisi"); // Bad Request
        }

        if (isset($json["tanggal_faktur"])) {
            $dateRange = explode(' - ', $json["tanggal_faktur"]);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query1->whereBetween('tanggal_faktur', [$startDate, $endDate]);
                $query2->whereBetween('tanggal_faktur', [$startDate, $endDate]);
            }
        }

        if (isset($json["supplier_id"])) {
            $query1->where("supplier_id", $json["supplier_id"]);
            $query2->where("supplier_id", $json["supplier_id"]);
        }

        if (isset($json["kategori_id"])) {
            $query1->where("kategori_id", $json["kategori_id"]);
            $query2->where("kategori_id", $json["kategori_id"]);
        }

        if (isset($json["tipe_terima"])) {
            $query1->where("tipe_terima", $json["tipe_terima"]);
            $query2->where("tipe_terima", $json["tipe_terima"]);
        }

        if (isset($json["kode_po"])) {
            $query1->where("kode_po", $json["kode_po"]);
            $query2->where("kode_po", $json["kode_po"]);
        }

        if (isset($json["nama_barang"])) {
            // with items
            $query1->whereHas("items", function ($q) use ($json) {
                $q->where("nama_barang", "like", "%" . $json["nama_barang"] . "%");
            });
            $query2->whereHas("items", function ($q) use ($json) {
                $q->where("nama_barang", "like", "%" . $json["nama_barang"] . "%");
            });
        }

        if (isset($json["jenis"])) {
            if ($json["jenis"] == "f") {
                $pbs = $query1->get();
            } else {
                $pbs = $query2->get();
            }
        } else {
            $pbs1 = $query1->get()->all();
            $pbs2 = $query2->get()->all();
            $pbs = collect(array_merge($pbs1, $pbs2));
        }

        return view("pages.simrs.warehouse.penerimaan-barang.report." . $type, compact("pbs", "startDate", "endDate"));
    }
}
