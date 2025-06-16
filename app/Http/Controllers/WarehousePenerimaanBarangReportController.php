<?php

namespace App\Http\Controllers;

use App\Models\WarehouseKategoriBarang;
use App\Models\WarehousePabrik;
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

    public function rekap(Request $request) {}
}
