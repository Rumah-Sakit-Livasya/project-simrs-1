<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.igd.daftar-pasien', [
                'registrations' => $registration
            ]);
        }
    }

    public function filterPasien(Request $request)
    {
        try {
            $routePath = parse_url($request['route'], PHP_URL_PATH);

            if ($routePath === '/simrs/igd/catatan-medis') {
                $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', 'igd');
            } else {
                $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', '!=',  'igd');
            }

            $query->when($request->departement_id, function ($q) use ($request) {
                return $q->where('departement_id', $request->departement_id);
            });

            $query->when($request->doctor_id, function ($q) use ($request) {
                return $q->where('doctor_id', $request->doctor_id);
            });

            $registrations = $query->get();

            // Render partial view sebagai HTML
            $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations'))->render();

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

        $departements = $path === 'igd' ? Departement::where('name', 'like', 'ugd')->get() : Departement::all();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();

        $registration = Registration::where('registration_number', $noRegist)->first();

        // Jika ada registration, ambil pasien dengan departement_id dan doctor_id yang sama
        if ($registration) {
            $query = Registration::query();

            $query->when(isset($registration->departement_id), function ($q) use ($registration) {
                return $q->where('departement_id', $registration->departement_id);
            });

            $query->when(isset($registration->doctor_id), function ($q) use ($registration) {
                return $q->where('doctor_id', $registration->doctor_id);
            });

            $registrations = $query->get();
        } else {
            // Jika tidak ada filter / registrasi dikirim, tampilkan data hari ini saja
            $registrations = Registration::where('registration_type', 'igd')
                ->get();
        }

        // Jika permintaan datang dari klik menu dan nomor registrasi tersedia
        if ($menu && $noRegist) {
            // $html = view('pages.simrs.erm.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        }


        // Jika halaman awal dibuka (tanpa filter)
        return view('pages.simrs.erm.index', compact('departements', 'menu', 'jadwal_dokter', 'registration', 'registrations', 'path'));
    }


    public static function poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path)
    {
        Carbon::setLocale('id');

        switch ($menu) {
            case 'triage':
                $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.triage', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_perawat':
                $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.pengkajian-perawat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_dokter':
                $pengkajian = PengkajianDokterRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.dokter.pengkajian-dokter', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_resep':
                $pengkajian = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.farmasi.pengkajian-resep', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'cppt_perawat':
                $perawat = Employee::whereHas('organization', function ($query) {
                    $query->where('name', 'Rawat Jalan');
                })->get();
                return view('pages.simrs.erm.form.perawat.cppt-perawat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'perawat', 'path'));

            case 'cppt_farmasi':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.farmasi.cppt-farmasi', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'cppt_dokter':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.dokter.cppt-dokter', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'resume_medis_rajal':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.dokter.resume_medis', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'rekonsiliasi_obat':
                $dokter = Employee::where('is_doctor', 1)->get();
                return view('pages.simrs.erm.form.farmasi.rekonsiliasi-obat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'pengkajian_lanjutan':
                $form = FormKategori::all();
                $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.pengkajian-lanjutan', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian', 'path'));

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
                $order_lab = OrderLaboratorium::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.perawat.transfer_pasien_perawat', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_anak':
                // $ews_anak = OrderLaboratorium::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.perawat.ews-anak', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_dewasa':
                $ews_dewasa = OrderLaboratorium::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.perawat.ews-dewasa', compact('ews_dewasa', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_obstetri':
                $ews_obstetri = OrderLaboratorium::where('registration_id', $registration->id)->get();
                return view('pages.simrs.erm.form.perawat.ews-obstetri', compact('ews_obstetri', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'assesment_gadar':
                $pengkajian = AssesmentKeperawatanGadar::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.assesment-gadar', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            default:
                return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'path'));
        }
    }
}
