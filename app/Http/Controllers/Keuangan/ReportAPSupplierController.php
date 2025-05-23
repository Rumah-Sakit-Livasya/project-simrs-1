<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportAPSupplierController extends Controller
{
    public function belumTukarFaktur(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.report-ap-supplier.belum-tukar-faktur', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function agingApSupplier(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.report-ap-supplier.aging-ap-supplier', compact('tanggal_awal', 'tanggal_akhir'));
    }
    public function laporanJatuhTempo(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.report-ap-supplier.laporan-jatuh-tempo', compact('tanggal_awal', 'tanggal_akhir'));
    }
}
