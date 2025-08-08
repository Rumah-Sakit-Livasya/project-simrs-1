<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterBarangEditLog;
use Illuminate\Http\Request;

class WarehouseReportHistoriPerubahanMasterBarang extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseMasterBarangEditLog::query()->with(["user", "satuan", "golongan", "kelompok"]);
        $filters = ["nama_barang", "kode_barang"];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled("tanggal")) {
            $dateRange = explode(' - ', $request->get("tanggal"));
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $filterApplied = true;
            }
        }

        if ($request->filled("jenis")) {
            $jenis = $request->get("jenis");
            if ($jenis == 'f') {
                $query->where("goods_type", WarehouseBarangFarmasi::class);
                $filterApplied = true;
            } else if ($jenis == "nf") {
                $query->where("goods_type", WarehouseBarangNonFarmasi::class);
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $logs = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $logs = WarehouseMasterBarangEditLog::all();
        }

        return view("pages.simrs.warehouse.report.histori-perubahan-master-barang", compact("logs"));
    }
}
