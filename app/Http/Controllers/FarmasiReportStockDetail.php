<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;

class FarmasiReportStockDetail extends Controller
{
    public function index()
    {
        return view("pages.simrs.farmasi.report.stock-detail", [
            "gudangs" => WarehouseMasterGudang::all(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "golongans" => WarehouseGolonganBarang::all()
        ]);
    }

    public function get_items(Request $request)
    {
        $data = $request->validate([
            "tanggal" => "required|string",
            "nama" => "nullable|string",
            "kategori" => "nullable|exists:farmasi_kategori_barang,id",
            "golongan" => "nullable|exists:farmasi_golongan_barang,id",
            "gudang" => "nullable|exists:farmasi_master_gudang,id",
            "jenis" => "nullable|in:f,nf",
        ]);

        $dateRange = explode(' - ', $data['tanggal']);
        if (count($dateRange) !== 2) {
            return response()->json(['message' => 'Invalid date range'], 400);
        }

        $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
        $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));

        $queries = [
            'f' => WarehouseBarangFarmasi::query(),
            'nf' => WarehouseBarangNonFarmasi::query(),
        ];

        foreach ($queries as $type => $qry) {
            $qry
                ->whereHas('stored_items', function ($q) use ($endDate, $data) {
                    $table = $q->getModel()->getTable();
                    $q->where("{$table}.created_at", '<=', $endDate)
                        ->when(
                            isset($data['gudang']),
                            fn($q2) => $q2->where("{$table}.gudang_id", $data['gudang'])
                        );
                })
                ->when(
                    isset($data['nama']),
                    fn($q) => $q->where('nama', 'like', "%{$data['nama']}%")
                )
                ->when(
                    isset($data['kategori']),
                    fn($q) => $q->where('kategori_id', $data['kategori'])
                )
                ->when(
                    isset($data['golongan']),
                    fn($q) => $q->where('golongan_id', $data['golongan'])
                )
                ->with([
                    'stored_items' => function ($q) use ($endDate, $data) {
                        $table = $q->getModel()->getTable();
                        $q->where("{$table}.created_at", '<=', $endDate)
                            ->when(
                                isset($data['gudang']),
                                fn($q2) => $q2->where("{$table}.gudang_id", $data['gudang'])
                            );
                    },
                    'stored_items.pbi',
                    'stored_items.transaction_log',
                    'stored_items.transaction_log.source',
                    'stored_items.transaction_log.user',
                    'stored_items.transaction_log.before_gudang',
                    'stored_items.transaction_log.after_gudang',
                    "satuan",
                    "kategori",
                    "golongan"
                ]);
        }


        // pick which to run
        if (isset($data['jenis']) && ($data['jenis'] == 'f' || $data['jenis'] == 'nf')) {
            $result = $queries[$data['jenis']]->get();
        } else {
            // both
            $f = $queries['f']->get()->all();
            $nf = $queries['nf']->get()->all();
            $result = collect(array_merge($f, $nf));
        }

        // loop result
        foreach ($result as $barang) {
            $start = 0;
            $finish = 0;
            $adjustment = 0;
            $expired = 0;
            $in = 0;
            $out = 0;
            $logs = collect();

            // loop stored_items
            foreach ($barang->stored_items as $stored_item) {
                $movement_start = 0;
                $movement_end = 0;

                // loop transaction_log
                foreach ($stored_item->transaction_log as $log) {
                    $after = $log->after_qty;
                    $before = ($log->before_qty ? $log->before_qty : 0);
                    $delta = $after - $before;

                    if ($log->transaction_type == 'in') $in += $delta;
                    else $out += $delta;

                    if ($log->created_at >= $startDate) {
                        $movement_start += $delta;
                    }
                    if ($log->created_at >= $endDate) {
                        $movement_end += $delta;
                    }

                    if ($log->created_at >= $startDate && $log->created_at <= $endDate) {
                        $adjustment += $delta;
                    }
                }
                // append $stored_item->transaction_log to $logs
                $logs = $logs->merge($stored_item->transaction_log);

                // remove $stored_item->transaction_log from $stored_item
                unset($stored_item->transaction_log);

                $start += $stored_item->qty - $movement_start;
                $finish += $stored_item->qty - $movement_end;
                if ($stored_item->pbi->tanggal_exp !== null && $stored_item->pbi->tanggal_exp <= $endDate) {
                    $expired += $stored_item->qty - $movement_end;
                }
            }

            // sort $logs based on updated_at 
            $logs = $logs->sortByDesc('updated_at')->values();

            $barang->logs = $logs;
            $barang->qty_start = $start;
            $barang->qty_finish = $finish;
            $barang->qty_in = $in;
            $barang->qty_out = $out;
            $barang->adjustment = $adjustment;
            $barang->qty_expired = $expired;
        }

        return response()->json($result);
    }

    public function get_print_template()
    {
        return view("pages.simrs.farmasi.report.partials.stock-detail-print-template");
    }
}
