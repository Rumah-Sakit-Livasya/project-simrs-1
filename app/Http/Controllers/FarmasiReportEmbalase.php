<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\WarehouseMasterGudang;

class FarmasiReportEmbalase extends Controller
{
    public function index()
    {
        return view('pages.simrs.farmasi.laporan.embalase.index', [
            'gudangs' => WarehouseMasterGudang::all(),
        ]);
    }

    public function show($startDate, $endDate, $gudang_id, $tipe)
    {
        $query = FarmasiResep::query();

        // only where group_penjamin is not LIKE standar and not LIKE bpjs
        $query->whereHas('registration.penjamin.group_penjamin', function ($q) {
            $q->where('name', 'not like', '%standar%')
                ->where('name', 'not like', '%bpjs%');
        });

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $query->whereNot('embalase', 'tidak');

        if ($gudang_id != '-') {
            $query->where('gudang_id', $gudang_id);
        }

        if ($tipe != '-') {
            if ($tipe == 'rajal') {
                $query->whereHas('registration', function ($q) {
                    return $q->where('registration_type', 'rawat-jalan');
                });
            } elseif ($tipe == 'ranap') {
                $query->whereHas('registration', function ($q) {
                    return $q->where('registration_type', 'rawat-inap');
                });
            } elseif ($tipe == 'otc') {
                $query->whereNotNull('otc_id');
            }
        }

        $reseps = $query->get();

        return view(
            'pages.simrs.farmasi.laporan.embalase.show',
            compact('reseps', 'startDate', 'endDate')
        );
    }
}
