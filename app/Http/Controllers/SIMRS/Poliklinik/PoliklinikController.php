<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\ERM\TindakanMedisRajal;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;

class PoliklinikController extends Controller
{
    public function index()
    {
        $menu = request()->menu;
        $noRegist = request()->registration;

        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        $registration = Registration::where('registration_number', $noRegist)->first();

        if ($menu && $noRegist) {
            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
        }
    }

    private function poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration)
    {
        Carbon::setLocale('id');

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
        } elseif ($menu == 'cppt_farmasi') {
            return view('pages.simrs.poliklinik.farmasi.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_resep') {
            return view('pages.simrs.poliklinik.farmasi.pengkajian_resep', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'rekonsiliasi_obat') {
            return view('pages.simrs.poliklinik.farmasi.rekonsiliasi_obat', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_lanjutan') {
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.pengkajian_lanjutan', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'tindakan_medis') {
            $list_tindakan_medis = TindakanMedis::all();
            $tindakan_medis_yang_dipakai = TindakanMedisRajal::where('registration_id', $registration->id)->get();
            return view('pages.simrs.poliklinik.layanan.tindakan_medis', compact('registration', 'departements', 'jadwal_dokter', 'list_tindakan_medis', 'tindakan_medis_yang_dipakai'));
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
        }

        return null; // Jika menu tidak cocok
    }

    public function filterPasien(Request $request)
    {
        try {
            $query = Registration::where('date', now()->format('Y-m-d'));
        
            $query->when($request->departement_id, function ($q) use ($request) {
                return $q->where('departement_id', $request->departement_id);
            });
        
            $query->when($request->doctor_id, function ($q) use ($request) {
                return $q->where('doctor_id', $request->doctor_id);
            });
        
            $registrations = $query->get();
        
            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $registrations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }        
    }
}
