<?php

namespace App\Http\Controllers\SIMRS\JadwalDokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        return view('pages.simrs.master-data.jadwal-dokter.index');
    }
}
