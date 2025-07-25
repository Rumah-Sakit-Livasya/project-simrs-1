<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\EWSAnak;
use App\Models\SIMRS\EWSDewasa;
use App\Models\SIMRS\EWSObstetri;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pelayanan\RujukAntarRS;
use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use App\Models\SIMRS\TindakanMedis;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ERMController extends Controller
{
    public function index(Request $request)
    {
        $uriSegments = explode('/', request()->path());
        $path = $uriSegments[1];

        $query = Registration::query();
        $menu = $request->query('menu');
        $noRegist = request()->registration;
        // dd($menu);

        $filters = ['medical_record_number', 'registration_number', 'registration_name'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
                $filterApplied = true;
            }
        }


        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status == 'aktif' ? 'aktif' : 'tutup_kunjungan');
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registration = $query->orderBy('date', 'asc')
                ->where('registration_type', 'igd')
                ->get();
        } else {
            // Return empty collection if no filters applied
            $registration = collect();
        }

        if ($menu && $noRegist) {
            $query = Registration::where('registration_type', 'igd');
            $registration = Registration::where('registration_number', $noRegist)->first();
            $departements = Departement::latest()->get();
            $hariIni = Carbon::now()->translatedFormat('l');
            $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();

            // dd($jadwal_dokter->first()->doctor);

            $query->when($registration->departement_id, function ($q) use ($registration) {
                return $q->where('departement_id', $registration->departement_id);
            });

            $query->when($registration->doctor_id, function ($q) use ($registration) {
                return $q->where('doctor_id', $registration->doctor_id);
            });

            $registrations = $query->get();

            // Render partial view sebagai HTML
            $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations', 'path'))->render();

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.igd.daftar-pasien', [
                'registrations' => $registration,
                'path' => $path
            ]);
        }
    }

    public function filterPasien(Request $request, $path)
    {
        try {
            $routePath = parse_url($request['route'], PHP_URL_PATH);

            if ($path === 'igd') {
                $query = Registration::where('registration_type', 'igd');
            } else {
                $query = Registration::where('registration_type', '!=', 'igd');
            }

            // Filter by department first
            $query->when($request->departement_id, function ($q) use ($request) {
                return $q->where('departement_id', $request->departement_id);
            });

            // Filter doctor based on selected department
            $query->when($request->doctor_id, function ($q) use ($request) {
                return $q->where('doctor_id', $request->doctor_id);
            });

            // Filter by patient name
            $query->when($request->patient, function ($q) use ($request) {
                return $q->whereHas('patient', function ($patient) use ($request) {
                    $patient->where('name', 'like', '%' . $request->patient . '%');
                });
            });

            $registrations = $query->get();

            // Render partial view as HTML
            $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations', 'path'))->render();

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

    public function catatanMedis(Request $request)
    {
        $uriSegments = explode('/', request()->path());
        $path = $uriSegments[1];

        $menu = $request->menu;
        $noRegist = $request->registration;

        $hariIni = Carbon::now()->translatedFormat('l');

        if ($path === 'igd') {
            $departements = Departement::where('name', 'like', 'ugd')->get();
            $registrations = Registration::where('registration_type', 'igd')->get();

            $jadwal_dokter = JadwalDokter::where('hari', $hariIni)
                ->whereHas('doctor', function ($q) {
                    $q->whereHas('department_from_doctors', function ($subQuery) {
                        $subQuery->whereRaw('LOWER(name) = ?', ['ugd']);
                    });
                })
                ->get();
        } elseif ($path === 'poliklinik') {
            $departements = Departement::where('name', '!=', 'ugd')->get();
            $registrations = Registration::where('registration_type', 'rawat-jalan')->get();

            $jadwal_dokter = JadwalDokter::where('hari', $hariIni)
                ->whereHas('doctor', function ($q) {
                    $q->whereHas('department_from_doctors', function ($subQuery) {
                        $subQuery->whereRaw('LOWER(name) != ?', ['ugd']);
                    });
                })
                ->get();
        } elseif ($path === 'rawat-inap') {
            $departements = Departement::where('name', '!=', 'ugd')->get();
            $registrations = Registration::where('registration_type', 'rawat-inap')->get();
            $jadwal_dokter = JadwalDokter::where('hari', $hariIni)
                ->whereHas('doctor', function ($q) {
                    $q->whereHas('department_from_doctors', function ($subQuery) {
                        $subQuery->whereRaw('LOWER(name) != ?', ['ugd']);
                    });
                })
                ->get();
        } else {
            $registrations = Registration::where('registration_type', 'rawat-jalan')->get();
            $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        }

        $registration = Registration::where('registration_number', $noRegist)->first();

        // Jika permintaan datang dari klik menu dan nomor registrasi tersedia
        $pengkajian = $registration;
        if ($menu && $noRegist) {
            // $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        }

        // dd($path);
        // Jika halaman awal dibuka (tanpa filter)
        return view('pages.simrs.erm.index', compact('departements', 'pengkajian', 'menu', 'jadwal_dokter', 'registration', 'registrations', 'path'));
    }


    public static function poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path)
    {
        Carbon::setLocale('id');

        switch ($menu) {
            case 'triage':
                $pengkajian = Triage::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.triage', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_perawat':
                $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.pengkajian-perawat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_dokter':
                $data = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                $pengkajian = PengkajianDokterRajal::where('registration_id', $registration->id)->first();
                $triage = Triage::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.dokter.pengkajian-dokter', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'triage', 'path', 'data'));

            case 'pengkajian_resep':
                $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.farmasi.pengkajian-resep', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'cppt_perawat':
                if ($path !== 'igd') {
                    $data = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                } else {
                    $data = Triage::where('registration_id', $registration->id)->first();
                }

                $perawat = Employee::whereHas('organization', function ($query) {
                    $query->where('name', 'Rawat Jalan');
                })->get();
                $pengkajian = CPPT::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.cppt-perawat', compact('registration', 'registrations', 'pengkajian', 'menu', 'departements', 'jadwal_dokter', 'perawat', 'path', 'data'));

            case 'cppt_farmasi':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.farmasi.cppt-farmasi', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'cppt_dokter':
                $dokter = Employee::where('is_doctor', 1)->get();
                $pengkajian = CPPT::where('registration_id', $registration->id)->first();
                $gudangs = WarehouseMasterGudang::where('apotek', 1)->where('warehouse', 0)->get();
                return view('pages.simrs.erm.form.dokter.cppt-dokter', compact('gudangs', 'registration', 'registrations', 'pengkajian', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'resume_medis':
                $dokter = Employee::where('is_doctor', 1)->get();
                $pengkajian = ResumeMedisRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.dokter.resume_medis', compact('registration', 'registrations', 'pengkajian', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'rekonsiliasi_obat':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.farmasi.rekonsiliasi-obat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'pengkajian_lanjutan':
                $form = FormKategori::all();
                $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();
                $pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.pengkajian-lanjutan', compact('pengkajian', 'registration', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian', 'path'));

            case 'tindakan_medis':
                $tindakan_medis = TindakanMedis::all();
                $doctors = Doctor::with('employee', 'departements')->get()->groupBy(function ($doctor) {
                    return $doctor->department_from_doctors->name;
                });
                $tindakan_medis_yang_dipakai = OrderTindakanMedis::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.layanan.tindakan-medis', compact('doctors', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'tindakan_medis', 'tindakan_medis_yang_dipakai', 'path'));

            case 'pemakaian_alat':
                $list_peralatan = Peralatan::all();
                $alat_medis_yang_dipakai = OrderAlatMedis::where('registration_id', $registration->id)->get();
                $doctors = Doctor::with('employee')
                    ->whereHas('employee')
                    ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
                    ->get();
                return view('pages.simrs.erm.form.layanan.pemakaian-alat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'list_peralatan', 'alat_medis_yang_dipakai', 'doctors', 'path'));

            case 'patologi_klinik':
                $order_lab = OrderLaboratorium::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.layanan.patologi-klinik', compact('order_lab', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'transfer_pasien_perawat':
                $pengkajian = TransferPasienAntarRuangan::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.transfer_pasien_perawat', compact('registration', 'pengkajian', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_anak':
                $pengkajian = EWSAnak::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.ews-anak', compact('registration', 'pengkajian', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_dewasa':
                $pengkajian = EWSDewasa::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.ews-dewasa', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_obstetri':
                $pengkajian = EWSObstetri::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.ews-obstetri', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'assesment_gadar':
                $pengkajian = AssesmentKeperawatanGadar::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.assesment-gadar', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'rujuk_antar_rs':
                $pengkajian = RujukAntarRS::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.rujuk-antar-rs', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            default:
                return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'path'));
        }
    }

    public function saveSignature(Request $request, $id)
    {
        $request->validate([
            'signature_image' => 'required|string',
        ]);

        // Mapping target tipe form
        $targetType = $request->input('type', 'triage');

        $modelClass = match ($targetType) {
            'triage' => Triage::class,
            'gadar' => AssesmentKeperawatanGadar::class,
            // Tambah sesuai kebutuhan
            default => null,
        };

        if (! $modelClass) {
            return response()->json(['error' => 'Tipe form tidak dikenali'], 400);
        }

        $form = $modelClass::findOrFail($id);

        // Simpan atau update signature
        $signature = $form->signature()->updateOrCreate([], [
            'signature' => $request->signature_image,
        ]);

        return response()->json([
            'message' => 'Tanda tangan berhasil disimpan.',
            'path' => $signature->signature,
        ]);
    }
}
