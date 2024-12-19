<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        $menu = request()->menu;
        $noRegist = request()->registration;

        if ($menu && $noRegist) {
            $menuResponse = $this->poliklinikMenu($noRegist, $menu);
            if ($menuResponse) {
                return $menuResponse;
            }
        }
    }

    private function poliklinikMenu($noRegist, $menu)
    {
        Carbon::setLocale('id');
        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        $registration = Registration::where('registration_number', $noRegist)->first();

        // $doctors = Doctor::with('employee', 'departement')->get();
        // $groupedDoctors = [];
        // foreach ($doctors as $doctor) {
        //     $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
        // }

        if ($menu == 'pengkajian_perawat') {
            return view('pages.simrs.poliklinik.index', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'cppt_perawat') {
            return view('pages.simrs.poliklinik.perawat.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'transfer_pasien_perawat') {
            return view('pages.simrs.poliklinik.perawat.transfer_pasien_perawat', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_dokter') {
            return view('pages.simrs.poliklinik.dokter.pengkajian', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'cppt_dokter') {
            return view('pages.simrs.poliklinik.dokter.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_gizi') {
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.pengkajian_lanjutan', compact('registration', 'departements', 'jadwal_dokter'));   
        }elseif ($menu == 'cppt_farmasi') {
            return view('pages.simrs.poliklinik.farmasi.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        }elseif ($menu == 'pengkajian_resep') {
            return view('pages.simrs.poliklinik.farmasi.pengkajian_resep', compact('registration', 'departements', 'jadwal_dokter'));
            
        }elseif ($menu == 'rekonsiliasi_obat') {
            return view('pages.simrs.poliklinik.farmasi.rekonsiliasi_obat', compact('registration', 'departements', 'jadwal_dokter'));
        }elseif ($menu == 'pengkajian_lanjutan') {
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.pengkajian_lanjutan', compact('registration', 'departements', 'jadwal_dokter'));   
            
        }else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
        }

        return null; // Jika menu tidak cocok
    }
}
