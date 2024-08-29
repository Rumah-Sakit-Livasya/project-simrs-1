<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\ReportBarang;
use Illuminate\Http\Request;

class ReportBarangController extends Controller
{
    public function index()
    {
        return view('pages.inventaris.report-barang.index', [
            'reports' => ReportBarang::orderBy('created_at', 'desc')->get(),
        ]);
    }
}
