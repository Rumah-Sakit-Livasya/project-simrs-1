<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\FarmasiResepItems;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Penjamin;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;

class FarmasiReportRekapPenjualan extends Controller
{
    public function index()
    {
        return view('pages.simrs.farmasi.laporan.rekap-penjualan.index', [
            'gudangs' => WarehouseMasterGudang::all(),
            'doctors' => Doctor::all(),
            'kelompoks' => WarehouseKelompokBarang::all(),
            'penjamins' => Penjamin::all(),
        ]);
    }

    public function showDetailMonth($barang_id, $month, $year, $doctor_id)
    {
        $barang = WarehouseBarangFarmasi::findOrFail($barang_id);
        $query = FarmasiResepItems::query()->with([
            'resep',
            'resep.doctor',
            'resep.doctor.employee',
            'resep.registration',
            'resep.registration.patient',
            'stored',
            'stored.pbi',
            'stored.pbi.item',
            'stored.pbi.item.satuan',
        ]);

        // can't be racikan
        $query->where('tipe', 'obat');

        // query resep where created_at is in $year year and $month month
        $query->whereHas('resep', function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        });

        $query->whereHas('stored.pbi', function ($q) use ($barang_id) {
            $q->where('barang_id', $barang_id);
        });

        $monthName = Carbon::create()->month((int) $month)->monthName;
        if ($doctor_id != 0) {
            // can't be OTC
            $query->whereHas('resep', function ($q) {
                $q->whereNull('otc_id');
            });

            // query from resep.registration
            $query->whereHas('resep', function ($q) use ($doctor_id) {
                $q->where('dokter_id', $doctor_id)
                    ->orWhereHas('registration', function ($q) use ($doctor_id) {
                        $q->where('doctor_id', $doctor_id);
                    });
            });
            $doctor = Doctor::findOrFail($doctor_id);
            $periode = "Periode: {$monthName} {$year} ({$doctor->employee->fullname})";
        } else {
            $periode = "Periode: {$monthName} {$year}";
        }

        $items = $query->get();

        return view(
            'pages.simrs.farmasi.laporan.rekap-penjualan.show-detail',
            compact('items', 'barang', 'periode')
        );
    }

    public function showDetailDate($barang_id, $date, $doctor_id)
    {
        $barang = WarehouseBarangFarmasi::findOrFail($barang_id);
        $query = FarmasiResepItems::query()->with([
            'resep',
            'resep.doctor',
            'resep.doctor.employee',
            'resep.registration',
            'resep.registration.patient',
            'stored',
            'stored.pbi',
            'stored.pbi.item',
            'stored.pbi.item.satuan',
        ]);

        // can't be racikan
        $query->where('tipe', 'obat');

        // date is in format like this:
        // 29-11-2025
        // query from resep creation date
        $date = Carbon::createFromFormat('d-m-Y', $date)->toDateString();
        $query->whereHas('resep', function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        });

        $query->whereHas('stored.pbi', function ($q) use ($barang_id) {
            $q->where('barang_id', $barang_id);
        });

        if ($doctor_id != 0) {
            // can't be OTC
            $query->whereHas('resep', function ($q) {
                $q->whereNull('otc_id');
            });

            // query from resep.registration
            $query->whereHas('resep', function ($q) use ($doctor_id) {
                $q->where('dokter_id', $doctor_id)
                    ->orWhereHas('registration', function ($q) use ($doctor_id) {
                        $q->where('doctor_id', $doctor_id);
                    });
            });
            $doctor = Doctor::findOrFail($doctor_id);
            $periode = 'Tanggal '.tgl($date)." ({$doctor->employee->fullname})";
        } else {
            $periode = 'Tanggal '.tgl($date);
        }

        $items = $query->get();

        return view(
            'pages.simrs.farmasi.laporan.rekap-penjualan.show-detail',
            compact('items', 'barang', 'periode')
        );
    }

    public function show($tipe, string $btoa)
    {
        $data = json_decode(base64_decode($btoa));

        $query = FarmasiResep::query()->with([
            'gudang',
            'doctor',
            'registration',
            'items',
            'items.stored',
            'items.stored.pbi',
            'items.stored.pbi.item',
            'items.stored.pbi.item.satuan',
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
            // can't be OTC
            $query->whereNull('otc_id');

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
                $q->where('nama_barang', 'like', '%'.$nama_obat.'%');
            });
        }

        $periode = '';

        switch ($tipe) {
            case 'tanggal':
                $dateRange = explode(' - ', $data->tanggal_order);
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $reseps = $query->get();

                $dictionary = $this->aggregatePeriodSale($reseps);
                $doctor = null;
                if ($doctor_id != '-') {
                    $doctor = Doctor::findOrFail($doctor_id);
                }
                $periode = 'Periode: '.tgl_waktu($startDate).' - '.tgl_waktu($endDate);

                return view(
                    'pages.simrs.farmasi.laporan.rekap-penjualan.show-tanggal',
                    compact('reseps', 'periode', 'dictionary', 'doctor')
                );

            case 'bulan':
                $tahun = $data->tahun;
                $bulan = $data->bulan;
                $nama_bulan = $data->nama_bulan;

                $query->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $bulan);
                $reseps = $query->get();

                $dictionary = $this->aggregateMonthlySale($reseps);
                $doctor = null;
                if ($doctor_id != '-') {
                    $doctor = Doctor::findOrFail($doctor_id);
                }

                $periode = "Peride: {$nama_bulan} {$tahun}";

                $doctor_id = $doctor_id == '-' ? 0 : $doctor_id;

                return view(
                    'pages.simrs.farmasi.laporan.rekap-penjualan.show-bulan',
                    compact('dictionary', 'periode', 'tahun', 'bulan', 'doctor_id', 'doctor')
                );

            case 'tahun':
                $tahun = $data->tahun;

                $query->whereYear('created_at', $tahun);
                $reseps = $query->get();

                $dictionary = $this->aggregateYearlySale($reseps);
                $doctor = null;
                if ($doctor_id != '-') {
                    $doctor = Doctor::findOrFail($doctor_id);
                }

                $periode = "Peride: {$tahun}";
                $doctor_id = $doctor_id == '-' ? 0 : $doctor_id;

                return view(
                    'pages.simrs.farmasi.laporan.rekap-penjualan.show-tahun',
                    compact('periode', 'doctor', 'tahun', 'dictionary', 'doctor_id')
                );
        }

        return abort(404);
    }

    private function aggregateYearlySale($reseps)
    {
        $dictionary = [];
        foreach ($reseps as $resep) {
            foreach ($resep->items as $item) {

                if (! isset($dictionary[$item->stored->pbi->barang_id])) {
                    $barang = $item->stored->pbi->item;
                    // Create an object with barang property instead of array
                    $dictionary[$item->stored->pbi->barang_id] = (object) [
                        'barang' => $barang,
                        'total' => 0,
                        'months' => [],
                    ];

                    for ($i = 1; $i <= 12; $i++) {
                        $dictionary[$item->stored->pbi->barang_id]->months[$i] = 0;
                    }
                }

                $dictionary[$item->stored->pbi->barang_id]->months[$resep->created_at->month] += $item->qty;
                $dictionary[$item->stored->pbi->barang_id]->total += $item->qty;
            }
        }

        return $dictionary;
    }

    private function aggregatePeriodSale($reseps)
    {
        $dictionary = [];
        foreach ($reseps as $resep) {
            foreach ($resep->items as $item) {

                if (! isset($dictionary[$item->stored->pbi->barang_id])) {
                    $barang = $item->stored->pbi->item;
                    // Create an object with barang property instead of array
                    $dictionary[$item->stored->pbi->barang_id] = (object) [
                        'barang' => $barang,
                        'total' => 0,
                    ];
                }

                $dictionary[$item->stored->pbi->barang_id]->total += $item->qty;
            }
        }

        return $dictionary;
    }

    private function aggregateMonthlySale($reseps)
    {
        $dictionary = [];
        foreach ($reseps as $resep) {
            foreach ($resep->items as $item) {

                if (! isset($dictionary[$item->stored->pbi->barang_id])) {
                    $barang = $item->stored->pbi->item;
                    // Create an object with barang property instead of array
                    $dictionary[$item->stored->pbi->barang_id] = (object) [
                        'barang' => $barang,
                        'total' => 0,
                        'dates' => [],
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
