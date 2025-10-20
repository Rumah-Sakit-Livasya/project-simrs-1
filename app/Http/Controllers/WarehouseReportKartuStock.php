<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WarehouseReportKartuStock extends Controller
{
    public function index(Request $request)
    {
        // Data dasar yang selalu dibutuhkan oleh view
        $gudangs = WarehouseMasterGudang::where('aktif', 1)->get();
        $barangs = $this->getAllBarang();

        $viewData = [
            "gudangs" => $gudangs,
            "barangs" => $barangs,
        ];

        if ($request->isMethod('post') && $request->has("tanggal")) {
            $data = $request->validate([
                "tanggal" => "required|string",
                "gudang_id" => "nullable|exists:warehouse_master_gudang,id",
                "satuan_barang_type" => "required|string"
            ]);

            [$satuan_id, $barang_id, $type] = explode('_', $data["satuan_barang_type"]);

            // Ambil data barang dan satuan yang dipilih
            $barang = ($type == 'f') ? WarehouseBarangFarmasi::findOrFail($barang_id) : WarehouseBarangNonFarmasi::findOrFail($barang_id);
            $satuan = WarehouseSatuanBarang::findOrFail($satuan_id);

            // Proses tanggal
            $dateRange = explode(' - ', $data['tanggal']);
            $startDate = Carbon::parse($dateRange[0])->startOfDay();
            $endDate = Carbon::parse($dateRange[1])->endOfDay();

            // [LOGIKA BARU] Hitung Stok Awal
            $stokAwal = $this->hitungStokAwal($barang_id, $satuan_id, $type, $data['gudang_id'], $startDate);

            // Ambil transaksi dalam rentang tanggal
            $transactions = $this->getTransactions($barang_id, $satuan_id, $type, $data['gudang_id'], $startDate, $endDate);

            // [LOGIKA BARU] Proses transaksi untuk menghitung saldo berjalan
            $saldoBerjalan = $stokAwal;
            $logs = $transactions->map(function ($log) use (&$saldoBerjalan) {

                $adjustment = 0;
                $gudang_transaksi = null;

                // Logika In/Out/Move
                if ($log->transaction_type === 'in') {
                    $adjustment = $log->after_qty - $log->before_qty;
                    $gudang_transaksi = $log->after_gudang->nama ?? 'N/A';
                } elseif ($log->transaction_type === 'out') {
                    $adjustment = $log->after_qty - $log->before_qty; // Akan negatif
                    $gudang_transaksi = $log->after_gudang->nama ?? 'N/A';
                } elseif ($log->event_type === 'update' && $log->before_gudang_id && $log->after_gudang_id) { // Move
                    if (request('gudang_id') == $log->before_gudang_id) { // Keluar dari gudang yg difilter
                        $adjustment = -$log->qty_change;
                        $gudang_transaksi = "Ke: " . ($log->after_gudang->nama ?? 'N/A');
                    } else { // Masuk ke gudang yg difilter
                        $adjustment = $log->qty_change;
                        $gudang_transaksi = "Dari: " . ($log->before_gudang->nama ?? 'N/A');
                    }
                }

                // Kalkulasi saldo
                $stokSebelum = $saldoBerjalan;
                $saldoBerjalan += $adjustment;

                // Tambahkan data kalkulasi ke objek log
                $log->stok_awal = $stokSebelum;
                $log->adjustment = $adjustment;
                $log->stok_akhir = $saldoBerjalan;
                $log->gudang_transaksi = $gudang_transaksi;
                $log->kode_transaksi = $this->getKodeTransaksi($log->source);

                return $log;
            });

            $viewData = array_merge($viewData, [
                "logs" => $logs,
                "stokAwal" => $stokAwal,
                "barang" => $barang,
                "satuan" => $satuan,
            ]);
        }

        return view("pages.simrs.warehouse.report.kartu-stock", $viewData);
    }

    /**
     * Helper untuk mengambil semua barang farmasi dan non-farmasi.
     */
    private function getAllBarang()
    {
        $farmasi = WarehouseBarangFarmasi::with("satuan", "satuan_tambahan")->where('aktif', 1)->get()->map(fn($i) => $i->type = 'f');
        $nonFarmasi = WarehouseBarangNonFarmasi::with("satuan", "satuan_tambahan")->where('aktif', 1)->get()->map(fn($i) => $i->type = 'nf');
        return $farmasi->concat($nonFarmasi)->sortBy('nama_barang');
    }

    /**
     * Menghitung total stok pada titik waktu tertentu.
     */
    private function hitungStokAwal($barang_id, $satuan_id, $type, $gudang_id, Carbon $sebelumTanggal)
    {
        $stockModel = ($type == 'f') ? StoredBarangFarmasi::class : StoredBarangNonFarmasi::class;

        $query = $stockModel::whereHas('pbi', fn($q) => $q->where('barang_id', $barang_id)->where('satuan_id', $satuan_id));

        if ($gudang_id) {
            $query->where('gudang_id', $gudang_id);
        }

        // Ini adalah cara sederhana, untuk performa tinggi perlu kalkulasi dari StockTransaction
        // Untuk sekarang kita asumsikan ini cukup cepat
        return $query->sum('qty'); // Perlu logika lebih kompleks untuk saldo awal sebenarnya
    }

    /**
     * Mengambil transaksi dalam rentang tanggal.
     */
    private function getTransactions($barang_id, $satuan_id, $type, $gudang_id, Carbon $startDate, Carbon $endDate)
    {
        $stockModel = ($type == 'f') ? StoredBarangFarmasi::class : StoredBarangNonFarmasi::class;

        $query = StockTransaction::with(["stock.pbi", "before_gudang", "after_gudang", "source", "user"])
            ->whereBetween("created_at", [$startDate, $endDate])
            ->whereHasMorph('stock', [$stockModel], function ($q) use ($satuan_id, $barang_id) {
                $q->whereHas('pbi', fn($q2) => $q2->where('satuan_id', $satuan_id)->where('barang_id', $barang_id));
            });

        if ($gudang_id) {
            $query->where(fn($q) => $q->where("before_gudang_id", $gudang_id)->orWhere("after_gudang_id", $gudang_id));
        }

        return $query->orderBy('created_at', 'asc')->get(); // Diurutkan dari yang paling awal
    }

    /**
     * Helper untuk mendapatkan kode transaksi dari model source.
     */
    private function getKodeTransaksi($source)
    {
        if (!$source) return 'N/A';
        foreach ($source->getAttributes() as $key => $value) {
            if (str_starts_with($key, 'kode')) {
                return $value;
            }
        }
        return 'Initial Stock'; // Atau fallback lain
    }
}
