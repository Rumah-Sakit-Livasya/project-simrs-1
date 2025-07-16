<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SIMRS\ERMController;
use App\Models\Employee;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\ERM\TindakanMedisRajal;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;

class PoliklinikController extends Controller
{
    public function index()
    {
        $uriSegments = explode('/', request()->path());
        $path = $uriSegments[1];

        $menu = request()->menu;
        $noRegist = request()->registration;

        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        $registration = Registration::where('registration_number', $noRegist)->first();

        $query = Registration::whereDate('registration_date', Carbon::today());

        if ($registration) {
            $query->when($registration->departement_id, function ($q) use ($registration) {
                return $q->where('departement_id', $registration->departement_id);
            });

            $query->when($registration->doctor_id, function ($q) use ($registration) {
                return $q->where('doctor_id', $registration->doctor_id);
            });
        }

        $registrations = $query->get();

        if ($menu && $noRegist) {
            $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = ERMController::poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
<<<<<<< HEAD
=======
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations', 'path'));
>>>>>>> 841717927d57ff76a595e6f030bf800256003f35
        }

        return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations'));
    }

<<<<<<< HEAD

    private function poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration)
    {
        Carbon::setLocale('id');
=======
    // public function filterPasien(Request $request)
    // {
    //     try {
    //         $routePath = parse_url($request['route'], PHP_URL_PATH);
>>>>>>> 841717927d57ff76a595e6f030bf800256003f35

    //         if ($routePath === '/simrs/igd/catatan-medis') {
    //             $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', 'igd');
    //         } else {
    //             $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', '!=',  'igd');
    //         }

    //         $query->when($request->departement_id, function ($q) use ($request) {
    //             return $q->where('departement_id', $request->departement_id);
    //         });

<<<<<<< HEAD
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.pengkajian_lanjutan', compact('registration', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian'));
        } elseif ($menu == 'cppt_farmasi') {
            return view('pages.simrs.poliklinik.farmasi.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_resep') {
            return view('pages.simrs.poliklinik.farmasi.pengkajian_resep', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'rekonsiliasi_obat') {
            return view('pages.simrs.poliklinik.farmasi.rekonsiliasi_obat', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_lanjutan') {
            $form = FormKategori::all();
            $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();
=======
    //         $query->when($request->doctor_id, function ($q) use ($request) {
    //             return $q->where('doctor_id', $request->doctor_id);
    //         });
>>>>>>> 841717927d57ff76a595e6f030bf800256003f35

    //         $registrations = $query->get();

    //         // Render partial view sebagai HTML
    //         $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data retrieved successfully',
    //             'html' => $html
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to retrieve data',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function showForm(Request $request, $registrationId, $encryptedID)
    {
        try {
            // Dekripsi ID
            $id = base64_decode($encryptedID);

            // Ambil data berdasarkan ID
            $formTemplate = FormTemplate::findOrFail($id)->form_source;
            $registration = Registration::findOrFail($registrationId);

            // Data pasien
            $data = [
                'no_rm' => $registration->patient->medical_record_number ?? '',
                'nama_pasien' => $registration->patient->name ?? '',
                'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('Y-m-d') ?? '',
                'umur_pasien' => Carbon::parse($registration->patient->date_of_birth)->diffInYears(Carbon::now()) ?? '',
                'kelamin_pasien' => $registration->patient->gender ?? '',
                'alamat_pasien' => $registration->patient->address ?? '',
                'dpjp' => $registration->doctor->employee->fullname ?? '',
                'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
                'tgl_sekarang' => Carbon::now()->format('Y-m-d') ?? '',
            ];

            // Replace placeholder di formTemplate dengan data pasien
            foreach ($data as $key => $value) {
                $formTemplate = str_replace("{{$key}}", $value, $formTemplate);
            }
            $formTemplateId = $id;

            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show_form', compact('formTemplate', 'formTemplateId', 'registrationId'));
        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan.');
        }
    }
}
