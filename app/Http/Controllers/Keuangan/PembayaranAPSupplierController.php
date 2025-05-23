<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembayaranAPSupplierController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.pembayaran-ap-supplier.index', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function create()
    {
        return view('app-type.keuangan.pembayaran-ap-supplier.create');
    }

    public function details()
    {
        return view('app-type.keuangan.pembayaran-ap-supplier.details');
    }

    public function show()
    {
        return view('app-type.keuangan.pembayaran-ap-supplier.show');
    }
}
