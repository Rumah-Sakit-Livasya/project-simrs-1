<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Penjamin;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseMasterGudang;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FarmasiReportRekapPenjualan extends Controller
{
    public function index()
    {
        return view("pages.simrs.farmasi.laporan.rekap-penjualan.index", [
            "gudangs" => WarehouseMasterGudang::all(),
            "doctors" => Doctor::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "penjamins" => Penjamin::all()
        ]);
    }

    public function show($tipe, string $btoa)
    {
        $data = json_decode(base64_decode($btoa));

        $query = FarmasiResep::query()->with([
            "gudang",
            "doctor",
            "registration",
            "items",
            "items.stored",
            "items.stored.pbi",
            "items.stored.pbi.item",
            "items.stored.pbi.item.satuan"
        ]);

        $doctor_id = $data->doctor_id;
        $kelompok_id = $data->kelompok_id;
        $penjamin_id = $data->penjamin_id;
        $gudang_id = $data->gudang_id;
        $nama_obat = $data->nama_obat;

        // don't count racikan items
        $query->with('items', function ($q) {
            $q->where('tipe', 'obat');
        });

        if ($doctor_id != '-') {
            // if the row has doctor_id not null, then select that column
            // else, select whereHas from registration, $registration->doctor_id == $doctor_id
            $query->where(function ($q) use ($doctor_id) {
                $q->where('dokter_id', $doctor_id)
                    ->orWhereHas('registration', function ($q) use ($doctor_id) {
                        $q->where('doctor_id', $doctor_id);
                    });
            });
        }

        if ($kelompok_id != '-') {
            $query->whereHas('items.stored.pbi.item', function ($q) use ($kelompok_id) {
                $q->where('kelompok_id', $kelompok_id);
            });
        }

        if ($penjamin_id != '-') {
            $query->whereHas('registration', function ($q) use ($penjamin_id) {
                $q->where('penjamin_id', $penjamin_id);
            });
        }

        if ($gudang_id != '-') {
            $query->whereHas('items.stored', function ($q) use ($gudang_id) {
                $q->where('gudang_id', $gudang_id);
            });
        }

        if ($nama_obat != '-') {
            $query->whereHas('items.stored.pbi', function ($q) use ($nama_obat) {
                $q->where('nama_barang', 'like', '%' . $nama_obat . '%');
            });
        }


        $periode = "";

        switch ($tipe) {
            case 'tanggal':
                $dateRange = explode(' - ', $data->tanggal_order);
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $reseps = $query->get();

                $periode = "Periode: " . tgl_waktu($startDate) . " - " . tgl_waktu($endDate);
                return view(
                    "pages.simrs.farmasi.laporan.rekap-penjualan.show-tanggal",
                    compact("reseps", "periode")
                );

            case 'bulan':
                $tahun = $data->tahun;
                $bulan = $data->bulan;
                $nama_bulan = $data->nama_bulan;

                $query->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $bulan);
                $reseps = $query->get();

                $dictionary = $this->aggregateMonthlySale($reseps);
                $periode = "Peride: {$nama_bulan} {$tahun}";
                return view(
                    "pages.simrs.farmasi.laporan.rekap-penjualan.show-bulan",
                    compact("dictionary", "periode", "tahun", "bulan")
                );

            case 'tahun':
                $tahun = $data->tahun;

                $query->whereYear('created_at', $tahun);
                $reseps = $query->get();

                $periode = "Periode: Tahun {$tahun}";
                return view(
                    "pages.simrs.farmasi.laporan.rekap-penjualan.show-tahun",
                    compact("reseps", "periode")
                );
        }

        return abort(404);
    }

    private function aggregateMonthlySale($reseps)
    {
        $dictionary = [];
        foreach ($reseps as $resep) {
            foreach ($resep->items as $item) {

                if (!isset($dictionary[$item->stored->pbi->barang_id])) {
                    $barang = $item->stored->pbi->item;
                    // Create an object with barang property instead of array
                    $dictionary[$item->stored->pbi->barang_id] = (object)[
                        'barang' => $barang,
                        'total' => 0,
                        'dates' => []
                    ];

                    for ($i = 1; $i <= 31; $i++) {
                        $dictionary[$item->stored->pbi->barang_id]->dates[$i] = 0;
                    }
                }

                $dictionary[$item->stored->pbi->barang_id]->dates[$resep->created_at->day] += $item->qty;
                $dictionary[$item->stored->pbi->barang_id]->total += $item->qty;
            }
        }

        return $dictionary;
    }
}
