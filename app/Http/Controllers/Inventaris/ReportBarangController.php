<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\ReportBarang;
use App\Models\Inventaris\RoomMaintenance;
use App\Models\Inventaris\TemplateBarang;
use App\Models\User;
use Illuminate\Http\Request;

class ReportBarangController extends Controller
{
    public function index()
    {
        return view('pages.inventaris.report-barang.index', [
            'rooms' => RoomMaintenance::all(),
            'category' => CategoryBarang::all(),
            'template' => TemplateBarang::all(),
            'barang' => Barang::all(),
            'users' => User::all(),
            'reports' => ReportBarang::orderBy('created_at', 'desc')->get()
        ]);
    }
}
