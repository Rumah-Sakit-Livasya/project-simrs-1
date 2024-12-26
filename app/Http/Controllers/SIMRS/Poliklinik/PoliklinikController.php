<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\JadwalDokter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        
        $menu = request()->menu;

        return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
    }
    
    function poliklinikMenu($menu) {
        if($menu == 'pengkajian_perawat') {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
        } elseif ($menu == 'cppt_perawat') {
            return view('pages.simrs.poliklinik.transfer_pasien_perawat');
        }
    }
}
