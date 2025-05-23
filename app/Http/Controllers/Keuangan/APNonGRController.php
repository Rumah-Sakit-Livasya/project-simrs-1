<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class APNonGRController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.ap-non-gr.index', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function edit()
    {
        return view('app-type.keuangan.ap-non-gr.edit');
    }
}
