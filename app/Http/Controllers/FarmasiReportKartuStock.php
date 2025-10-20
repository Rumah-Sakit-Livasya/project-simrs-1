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

class FarmasiReportKartuStock extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get') && $request->has("tanggal")) {
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

            $satuanBarangType = explode('_', $data["satuan_barang_type"]);
            if (count($satuanBarangType) !== 3) {
                return back()->with("error", "Format satuan barang tidak valid");
            }
            $satuan_id = $satuanBarangType[0];
            $barang_id = $satuanBarangType[1];
            $type = $satuanBarangType[2];

            if ($type == "f") {
                $barang = WarehouseBarangFarmasi::findOrFail($barang_id);
            } elseif ($type == "nf") {
                $barang = WarehouseBarangNonFarmasi::findOrFail($barang_id);
            } else {
                return back()->with("error", "Tipe satuan barang tidak valid");
            }

            // *** PERBAIKAN: Kueri untuk menghitung Saldo Awal ***
            $saldo_awal = StockTransaction::query()
                ->where('created_at', '<', $startDate)
                ->whereHasMorph('stock', [StoredBarangFarmasi::class, StoredBarangNonFarmasi::class], function ($q) use ($satuan_id, $barang_id) {
                    $q->whereHas('pbi', function ($q2) use ($satuan_id, $barang_id) {
                        $q2->where('satuan_id', $satuan_id)
                            ->where('barang_id', $barang_id);
                    });
                });

            if (isset($data["gudang_id"]) && $data["gudang_id"] !== "") {
                // Adjustment untuk saldo awal di gudang spesifik
                $saldo_awal->select(DB::raw(
                    "SUM(CASE
                        WHEN after_gudang_id = {$data['gudang_id']} THEN after_qty - before_qty
                        WHEN before_gudang_id = {$data['gudang_id']} THEN before_qty * -1
                        ELSE 0
                    END) as total_adjustment"
                ));
            } else {
                // Adjustment untuk saldo awal di semua gudang
                $saldo_awal->select(DB::raw("SUM(after_qty - before_qty) as total_adjustment"));
            }

            $saldo_awal = $saldo_awal->value('total_adjustment') ?? 0;

            // *** PERBAIKAN: Kueri utama ***
            $query = StockTransaction::query()->with(["stock.pbi", "before_gudang", "after_gudang", "source", "user"]);
            $query->whereBetween("created_at", [$startDate, $endDate]);

            if (isset($data["gudang_id"]) && $data["gudang_id"] !== "") {
                $query->where(function ($q) use ($data) {
                    $q->orWhere("before_gudang_id", $data["gudang_id"])
                        ->orWhere("after_gudang_id", $data["gudang_id"]);
                });
            }

            $query->whereHasMorph('stock', [StoredBarangFarmasi::class, StoredBarangNonFarmasi::class], function ($q) use ($satuan_id, $barang_id) {
                $q->whereHas('pbi', function ($q2) use ($satuan_id, $barang_id) {
                    $q2->where('satuan_id', $satuan_id)
                        ->where('barang_id', $barang_id);
                });
            });

            // *** PERBAIKAN: Urutkan dari yang terlama ke terbaru (ASC) ***
            $logs = $query->orderBy('created_at', 'asc')->get();

            return view("pages.simrs.farmasi.report.kartu-stock", [
                "gudangs" => WarehouseMasterGudang::all(),
                "barangs" => $this->getAllBarang(),
                "logs" => $logs,
                "saldo_awal" => $saldo_awal, // <-- Kirim saldo awal ke view
                "barang" => $barang,
                "satuan" => WarehouseSatuanBarang::findOrFail($satuan_id),
            ]);
        } else {
            return view("pages.simrs.farmasi.report.kartu-stock", [
                "gudangs" => WarehouseMasterGudang::all(),
                "barangs" => $this->getAllBarang()
            ]);
        }
    }

    // Helper function untuk merapikan pengambilan data barang
    private function getAllBarang()
    {
        $farmasi = WarehouseBarangFarmasi::with(["satuan", "satuan_tambahan"])
            ->get()
            ->map(function ($item) {
                $item->type = 'f';
                return $item;
            });

        $nonFarmasi = WarehouseBarangNonFarmasi::with(["satuan", "satuan_tambahan"])
            ->get()
            ->map(function ($item) {
                $item->type = 'nf';
                return $item;
            });

        return collect(array_merge($farmasi->all(), $nonFarmasi->all()));
    }
}
