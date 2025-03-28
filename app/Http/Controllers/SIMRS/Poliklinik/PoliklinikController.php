<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
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
        $menu = request()->menu;
        $noRegist = request()->registration;

        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        $registration = Registration::where('registration_number', $noRegist)->first();

        $query = Registration::whereDate('registration_date', Carbon::today());

        $query->when(isset($registration->departement_id), function ($q) use ($registration) {
            return $q->where('departement_id', $registration->departement_id);
        });

        $query->when(isset($registration->doctor_id), function ($q) use ($registration) {
            return $q->where('doctor_id', $registration->doctor_id);
        });

        $registrations = $query->get();
        
        if ($menu && $noRegist) {
            // Render partial view sebagai HTML
            $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations'));
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
            $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
            
            return view('pages.simrs.poliklinik.index', compact('registration', 'departements', 'jadwal_dokter', 'pengkajian'));
        } elseif ($menu == 'cppt_perawat') {
            $perawat = Employee::whereHas('organization', function ($query) {
                $query->where('name', 'Rawat Jalan');
            })->get();
            return view('pages.simrs.poliklinik.perawat.cppt', compact('registration', 'departements', 'jadwal_dokter', 'perawat'));
        } elseif ($menu == 'transfer_pasien_perawat') {
            return view('pages.simrs.poliklinik.perawat.transfer_pasien_perawat', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_dokter') {
            return view('pages.simrs.poliklinik.dokter.pengkajian', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'cppt_dokter') {
            return view('pages.simrs.poliklinik.dokter.cppt', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'resume_medis_rajal') {
            return view('pages.simrs.poliklinik.dokter.resume_medis', compact('registration', 'departements', 'jadwal_dokter'));
        } else if ($menu == 'profil_ringkas_rajal') {
            return view('pages.simrs.poliklinik.dokter.resume_medis', compact('registration', 'departements', 'jadwal_dokter'));
        } elseif ($menu == 'pengkajian_gizi') {
            $form = FormKategori::all();
            $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();

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

            return view('pages.simrs.poliklinik.pengkajian_lanjutan.pengkajian_lanjutan', compact('registration', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian'));
        } elseif ($menu == 'tindakan_medis') {
            $tindakan_medis = TindakanMedis::all();
            $doctors = Doctor::with('employee', 'departements')->get();
            // Group doctors by department
            $groupedDoctors = [];
            foreach ($doctors as $doctor) {
                $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
            }
            $tindakan_medis_yang_dipakai = OrderTindakanMedis::where('registration_id', $registration->id)->get();
            return view('pages.simrs.poliklinik.layanan.tindakan_medis', compact('groupedDoctors', 'registration', 'departements', 'jadwal_dokter', 'tindakan_medis', 'tindakan_medis_yang_dipakai'));
        } elseif ($menu == 'pemakaian_alat') {
            $list_peralatan = Peralatan::all();
            $alat_medis_yang_dipakai = OrderAlatMedis::where('registration_id', $registration->id)->get();
            $doctors = Doctor::with('employee')
                ->whereHas('employee')
                ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
                ->get();


            return view('pages.simrs.poliklinik.layanan.pemakaian_alat', compact('registration', 'departements', 'jadwal_dokter', 'list_peralatan', 'alat_medis_yang_dipakai', 'doctors'));
        } else if ($menu == 'patologi_klinik') {
            $order_lab = OrderLaboratorium::where('registration_id', $registration->id)->get();
            return view('pages.simrs.poliklinik.layanan.patologi_klinik', compact('order_lab', 'registration', 'departements', 'jadwal_dokter'));
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter'));
        }

        return null; // Jika menu tidak cocok
    }

    public function filterPasien(Request $request)
    {
        try {
            $query = Registration::whereDate('registration_date', Carbon::today());

            $query->when($request->departement_id, function ($q) use ($request) {
                return $q->where('departement_id', $request->departement_id);
            });

            $query->when($request->doctor_id, function ($q) use ($request) {
                return $q->where('doctor_id', $request->doctor_id);
            });

            $registrations = $query->get();

            // Render partial view sebagai HTML
            $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'html' => $html
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
