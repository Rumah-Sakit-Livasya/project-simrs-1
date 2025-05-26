<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PattyCashController extends Controller
{
    public function transaksiPengeluaran(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.petty-cash.transaksi-pengeluaran', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function transaksiCreate(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.petty-cash.transaksi-create', compact('tanggal_awal', 'tanggal_akhir'));
    }
}
