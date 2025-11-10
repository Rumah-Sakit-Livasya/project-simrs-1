<?php

namespace App\Http\Controllers\SIMRS\Dokter;

use App\Helpers\ErmHelper;
use App\Http\Controllers\Controller;
use App\Models\ChildInitialAssessment;
use App\Models\DischargePlanning;
use App\Models\DoctorInitialAssessment;
use App\Models\Echocardiography;
use App\Models\Employee;
use App\Models\FarmasiResepHarian;
use App\Models\GeriatricInitialAssessment;
use App\Models\HospitalInfectionSurveillance;
use App\Models\InpatientInitialAssessment;
use App\Models\InpatientInitialExamination;
use App\Models\MidwiferyInitialAssessment;
use App\Models\NeonatusInitialAssessment;
use App\Models\NeonatusInitialAssessmentDoctor;
use App\Models\NursingActivityChecklist;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\AssesmentKeperawatanGadar;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\DoctorVisit;
use App\Models\SIMRS\EWSAnak;
use App\Models\SIMRS\EWSDewasa;
use App\Models\SIMRS\EWSObstetri;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\KategoriRadiologi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use App\Models\SIMRS\Operasi\OrderOperasi;
use App\Models\SIMRS\Operasi\TipeOperasi;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pelayanan\RujukAntarRS;
use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use App\Models\SIMRS\PengkajianDokterIGD;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use App\Models\SIMRS\Room;
use App\Models\SIMRS\TindakanMedis;
use App\Models\SkriningGiziDewasa;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $uriSegments = explode('/', request()->path());
        $path = $uriSegments[1];

        $menu = $request->menu;
        $noRegist = $request->registration;

        $hariIni = Carbon::now()->translatedFormat('l');
        $departements = Departement::get();
        $tipeRegis = [
            ['name' => 'rawat-jalan', 'value' => 'Rawat Jalan'],
            ['name' => 'rawat-inap', 'value' => 'Rawat Inap'],
            ['name' => 'igd', 'value' => 'IGD']
        ];

        $doctors = Doctor::with('employee.user') // <-- Tambahkan ini untuk memuat relasi
            ->whereHas('employee.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->get();

        $departements = Departement::where('name', '!=', 'ugd')->get();
        $registrations = Registration::where('registration_type', 'rawat-jalan')
            ->where('status', 'aktif')
            ->where('doctor_id', auth()->user()?->employee?->doctor?->id)
            ->whereNull('registration_close_date')
            ->get();

        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)
            ->whereHas('doctor', function ($q) {
                $q->whereHas('department_from_doctors', function ($subQuery) {
                    $subQuery->whereRaw('LOWER(name) != ?', ['ugd']);
                });
            })
            ->get();

        $registration = Registration::where('registration_number', $noRegist)->first();

        // Jika permintaan datang dari klik menu dan nomor registrasi tersedia
        $pengkajian = $registration;
        if ($menu && $noRegist) {

            $menuResponse = $this->poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        }

        // Jika halaman awal dibuka (tanpa filter)
        return view('pages.simrs.erm.index', compact('tipeRegis', 'doctors', 'departements', 'pengkajian', 'menu', 'jadwal_dokter', 'registration', 'tipeRegis', 'doctors', 'registrations', 'path'));
    }


    public function filterPasien(Request $request)
    {
        try {
            $routePath = parse_url($request['route'], PHP_URL_PATH);

            $query = Registration::query();
            // Filter by department first
            $query->when($request->registration_type, function ($q) use ($request) {
                return $q->where('registration_type', $request->registration_type);
            });

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
            $html = view('pages.simrs.dokter.partials.list-pasien', compact('registrations'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'html' => $html,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public static function poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path)
    {
        Carbon::setLocale('id');
        $tipeRegis = [
            ['name' => 'rawat-jalan', 'value' => 'Rawat Jalan'],
            ['name' => 'rawat-inap', 'value' => 'Rawat Inap'],
            ['name' => 'igd', 'value' => 'IGD']
        ];

        $doctors = Doctor::with('employee.user') // <-- Tambahkan ini untuk memuat relasi
            ->whereHas('employee.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->get();

        switch ($menu) {
            case 'triage':
                $pengkajian = Triage::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.triage', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_perawat':
                $pengkajian = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.pengkajian-perawat', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'pengkajian_dokter':
                $rawData = null;

                // Ambil data sesuai dengan path
                if ($path === 'igd') {
                    $rawData = Triage::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'rawat-jalan') {
                    $rawData = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'rawat-inap') {
                    $rawData = InpatientInitialExamination::firstWhere('registration_id', $registration->id);
                }

                // Generalisasi data ke format yang konsisten
                $data = null;
                if ($rawData) {
                    $data = (object) [];

                    // Mapping untuk Triage
                    if ($rawData instanceof \App\Models\SIMRS\Pelayanan\Triage) {
                        $data->pr = $rawData->pr;
                        $data->rr = $rawData->rr;
                        $data->bp = $rawData->bp;
                        $data->temperatur = $rawData->temperatur;
                        $data->body_height = $rawData->body_height;
                        $data->body_weight = $rawData->body_weight;
                        $data->sp02 = $rawData->sp02;
                        $data->skor_nyeri = null; // Triage tidak punya skor nyeri
                        $data->keluhan_utama = null; // Triage tidak punya keluhan utama
                        $data->diagnosa_keperawatan = null; // Triage tidak punya diagnosa keperawatan
                    }
                    // Mapping untuk PengkajianNurseRajal
                    elseif ($rawData instanceof \App\Models\SIMRS\Pengkajian\PengkajianNurseRajal) {
                        $data->pr = $rawData->pr;
                        $data->rr = $rawData->rr;
                        $data->bp = $rawData->bp;
                        $data->temperatur = $rawData->temperatur;
                        $data->body_height = $rawData->body_height;
                        $data->body_weight = $rawData->body_weight;
                        $data->sp02 = $rawData->sp02;
                        $data->skor_nyeri = $rawData->skor_nyeri;
                        $data->keluhan_utama = $rawData->keluhan_utama;
                        $data->diagnosa_keperawatan = $rawData->diagnosa_keperawatan;
                    }
                    // Mapping untuk InpatientInitialExamination
                    elseif ($rawData instanceof \App\Models\InpatientInitialExamination) {
                        $data->pr = $rawData->vital_sign_pr;
                        $data->rr = $rawData->vital_sign_rr;
                        $data->bp = $rawData->vital_sign_bp;
                        $data->temperatur = $rawData->vital_sign_temperature;
                        $data->body_height = $rawData->anthropometry_height;
                        $data->body_weight = $rawData->anthropometry_weight;
                        $data->sp02 = null; // InpatientInitialExamination tidak punya sp02
                        $data->skor_nyeri = null; // InpatientInitialExamination tidak punya skor nyeri
                        $data->keluhan_utama = null; // InpatientInitialExamination tidak punya keluhan utama
                        $data->diagnosa_keperawatan = null; // InpatientInitialExamination tidak punya diagnosa keperawatan
                    }
                }

                $pengkajian = PengkajianDokterRajal::firstWhere('registration_id', $registration->id);
                $triage = Triage::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.dokter.pengkajian-dokter', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'triage', 'path', 'data'));

            case 'pengkajian_dokter_igd':
                $triage = Triage::firstWhere('registration_id', $registration->id);
                $pengkajian = PengkajianDokterIGD::firstWhere('registration_id', $registration->id);
                // dd($triage);
                return view('pages.simrs.erm.form.dokter.pengkajian-dokter-igd', compact(
                    'registration',
                    'tipeRegis',
                    'doctors',
                    'registrations',
                    'menu',
                    'departements',
                    'jadwal_dokter',
                    'pengkajian',
                    'triage',
                    'path',
                ));

            case 'pengkajian_resep':
                $pengkajian = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.farmasi.pengkajian-resep', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajian', 'path'));

            case 'cppt_perawat':
                // 1. Ambil data mentah (Logika ini tetap sama)
                $rawData = null;
                if ($path === 'igd') {
                    $rawData = Triage::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'poliklinik') {
                    $rawData = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'rawat-inap') {
                    $rawData = InpatientInitialExamination::firstWhere('registration_id', $registration->id);
                }

                // 2. Mapping data mentah ke objek standar menggunakan helper
                // (Pastikan helper sudah diupdate - lihat di bawah)
                $data = ErmHelper::mapRawDataToStandardObject($rawData);

                // 3. Ambil data CPPT yang sudah tersimpan
                $pengkajian = CPPT::firstWhere('registration_id', $registration->id);

                // 4. Inisialisasi semua variabel teks
                $subjectiveText = '';
                $objectiveText = '';
                $assessmentText = '';
                $planningText = '';

                // ====================================================================
                // PERBAIKAN: Ambil data implementasi & evaluasi dari $pengkajian
                // ====================================================================
                $implementationText = $pengkajian?->implementasi ?? '';
                $evaluationText = $pengkajian?->evaluasi ?? '';


                if ($data) {
                    // --- Membangun Teks SOAP berdasarkan Tipe Registrasi ---
                    if ($registration->registration_type === 'rawat-inap') {
                        // Teks untuk Rawat Inap
                        $subjectiveLines = ['Keluhan Utama: ' . ($data->keluhan_utama ?? '')];
                        $objectiveLines = [
                            'Keadaan Umum : ' . ($data->keadaan_umum ?? ''),
                            'Nadi : ' . ($data->pr ?? '') . ' /menit',
                            'Respirasi(RR) : ' . ($data->rr ?? '') . ' /menit',
                            'Tensi (BP) : ' . ($data->bp ?? '') . ' mmHg',
                            'Suhu (T) : ' . ($data->temperatur ?? '') . ' C',
                            'Berat badan : ' . ($data->body_weight ?? '') . ' Kg',
                            'Skor EWS : ' . ($data->skor_ews ?? ''),
                            'Skor nyeri : ' . ($data->skor_nyeri ?? ''),
                            'Saturasi : ' . ($data->sp02 ?? ''),
                            'Skor resiko jatuh : ' . ($data->skor_resiko_jatuh ?? ''),
                        ];
                        $assessmentLines = ['Diagnosa Kerja:', ($data->diagnosis ?? '')];
                        $planningLines = ['Rencana Tindak Lanjut:', ($data->rencana_tindak_lanjut ?? '')];
                    } else {
                        // Teks untuk IGD & Poliklinik (default)
                        $allergyText = 'Tidak ada';
                        if (!empty($data->allergy_medicine)) {
                            $allergyText = is_array($data->allergy_medicine) ? implode(', ', $data->allergy_medicine) : $data->allergy_medicine;
                        }
                        $inspectionDate = $data->created_at ? \Illuminate\Support\Carbon::parse($data->created_at)->format('d/m/Y H:i') : '';

                        $subjectiveLines = [
                            'Keluhan Utama: ' . ($data->keluhan_utama ?? ''),
                            'Skor Nyeri: ' . ($data->skor_nyeri ?? ''),
                            'Dokter Pemeriksa: ' . ($data->doctor_name ?? ''),
                            'Tanggal Pemeriksaan: ' . $inspectionDate,
                            'Riwayat Alergi: ' . $allergyText,
                            'Riwayat Penyakit Sekarang: ' . ($data->riwayat_penyakit_sekarang ?? ''),
                            'Riwayat Penyakit Dahulu: ' . ($data->riwayat_penyakit_dahulu ?? ''),
                            'Riwayat Penyakit Keluarga: ' . ($data->riwayat_penyakit_keluarga ?? ''),
                        ];
                        $objectiveLines = [
                            'TD: ' . ($data->bp ?? '-'),
                            'HR: ' . ($data->pr ?? '-'),
                            'RR: ' . ($data->rr ?? '-'),
                            'Suhu: ' . ($data->temperatur ?? '-'),
                            'SpO2: ' . ($data->sp02 ?? '-'),
                            'Tinggi Badan: ' . ($data->body_height ?? '-'),
                            'Berat Badan: ' . ($data->body_weight ?? '-'),
                            'Diagnosa Keperawatan: ' . ($data->diagnosa_keperawatan ?? ''),
                        ];
                        $assessmentLines = [
                            'Diagnosa Kerja:',
                            ($data->diagnosis ?? ''),
                            "\n",
                            'Diagnosa Keperawatan:',
                            ($data->diagnosa_keperawatan ?? ''),
                            "\n",
                            'Analisis Masalah:',
                            ($data->registration_notes ?? ''),
                        ];
                        $planningLines = [
                            'Terapi / Tindakan :',
                            ($data->therapy_text ?? ''),
                            "\n",
                            'Intervensi Keperawatan:',
                            ($data->intervensi_keperawatan ?? ''),
                        ];
                    }

                    // Gabungkan array menjadi string
                    $subjectiveText = implode("\n", $subjectiveLines);
                    $objectiveText = implode("\n", $objectiveLines);
                    $assessmentText = implode("\n", $assessmentLines);
                    $planningText = implode("\n", $planningLines);
                }

                $perawat = Employee::whereHas('organization', function ($query) {
                    $query->whereIn('name', ['Rawat Jalan', 'Rawat Inap', 'Perinatologi', 'VK & PONEK', 'IGD', 'OK']);
                })->get();

                return view('pages.simrs.erm.form.perawat.cppt-perawat', compact(
                    'registration',
                    'tipeRegis',
                    'doctors',
                    'registrations',
                    'pengkajian',
                    'menu',
                    'departements',
                    'jadwal_dokter',
                    'perawat',
                    'path',
                    'data',
                    'subjectiveText',
                    'objectiveText',
                    'assessmentText',
                    'planningText',
                    'implementationText', // Variabel ini sekarang sudah diisi
                    'evaluationText'      // Variabel ini sekarang sudah diisi
                ));

            case 'cppt_farmasi':
                $dokter = Employee::where('is_doctor', 1)->get();

                return view('pages.simrs.erm.form.farmasi.cppt-farmasi', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'cppt_dokter':
                $dokter = Employee::where('is_doctor', 1)->get();
                $pengkajian = CPPT::firstWhere(['registration_id' => $registration->id, 'tipe_cppt' => 'dokter']);
                $gudangs = WarehouseMasterGudang::where('apotek', 1)->where('warehouse', 0)->get();
                $barangs = WarehouseBarangFarmasi::with(['stored_items', 'satuan'])->get();

                $default_column = 'rajal_default';
                if ($registration->registration_type == 'rawat-inap') {
                    $default_column = 'ranap_default';
                }
                $default_apotek = WarehouseMasterGudang::select('id')->where($default_column, 1)->first();

                if ($registration->registration_type == 'rawat-inap') {
                    $assesment = PengkajianDokterRajal::firstWhere('registration_id', $registration->id);
                }
                if ($registration->registration_type == 'rawat-jalan') {
                    $assesment = DoctorInitialAssessment::firstWhere('registration_id', $registration->id);
                }
                if ($registration->registration_type == 'igd') {
                    $assesment = PengkajianDokterIGD::firstWhere('registration_id', $registration->id);
                }

                // dd($assesment);

                // Flag untuk menampilkan SweetAlert2 jika assesment belum ada
                $showSwal = false;
                if (!$assesment || !$assesment->exists) {
                    $showSwal = true;
                }

                $soapTemplates = \App\Models\SIMRS\Dokter\SoapTemplate::orderBy('template_name')->get();

                return view('pages.simrs.erm.form.dokter.cppt-dokter', compact(
                    'gudangs',
                    'barangs',
                    'default_apotek',
                    'registration',
                    'tipeRegis',
                    'doctors',
                    'registrations',
                    'pengkajian',
                    'menu',
                    'departements',
                    'jadwal_dokter',
                    'dokter',
                    'path',
                    'assesment',
                    'showSwal',
                    'soapTemplates'
                ));

            case 'resume_medis':
                $cppt = CPPT::firstWhere(['registration_id' => $registration->id, 'tipe_cppt' => 'dokter']);
                $diagnosa_utama = null;
                $diagnosa_tambahan = null;
                if ($cppt && !empty($cppt->assesment)) {
                    // Cari baris yang mengandung "Diagnosa Kerja:"
                    if (preg_match('/Diagnosa Kerja:\s*(.*)/i', $cppt->assesment, $matches)) {
                        $diagnosa_utama = trim($matches[1]);
                    }
                    // Cari baris yang mengandung "Diagnosa Banding:"
                    if (preg_match('/Diagnosa Banding:\s*(.*)/i', $cppt->assesment, $matches_tambahan)) {
                        $diagnosa_tambahan = trim($matches_tambahan[1]);
                    }
                }

                $dokter = Employee::where('is_doctor', 1)->get();
                $pengkajian = ResumeMedisRajal::firstWhere('registration_id', $registration->id);
                $assesment = null;
                $terapi_tindakan = null;
                $keluhan_utama = null;
                $assesmentError = null;

                try {
                    if ($registration->registration_type == 'rawat-jalan') {
                        $assesment = DoctorInitialAssessment::firstWhere('registration_id', $registration->id);
                    } else {
                        $assesment = PengkajianDokterIGD::firstWhere('registration_id', $registration->id);
                    }

                    // Proses terapi_tindakan & keluhan_utama jika assesment tersedia
                    if ($assesment) {
                        $terapi_tindakan = $assesment->terapi_tindakan ?? null;

                        $anamnesisData = $assesment->anamnesis ?? null;
                        if (is_array($anamnesisData) && array_key_exists('keluhan_utama', $anamnesisData)) {
                            $keluhan_utama = $anamnesisData['keluhan_utama'];
                        }
                    }
                } catch (\Throwable $e) {
                    $assesmentError = $e->getMessage();
                }

                // Flag untuk memicu SweetAlert2 jika assesment belum ada
                $showSwal = false;
                if (is_null($assesment) || (method_exists($assesment, 'exists') && !$assesment->exists)) {
                    $showSwal = true;
                }

                // [FLAG] Flag untuk memicu SweetAlert di view
                // Kirim true jika $assesment null atau tidak ditemukan
                $assessmentNotFilled = is_null($assesment);

                // Kirim $assesmentError ke view (pastikan juga compact atau with-nya sesuai di bawah)
                $tindakanMedis = $registration->order_tindakan_medis
                    ? $registration->order_tindakan_medis->map(function ($order) {
                        return $order->tindakan_medis ? $order->tindakan_medis->nama_tindakan : null;
                    })->filter()->values()
                    : collect();

                return view('pages.simrs.erm.form.dokter.resume_medis', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'pengkajian', 'assesment', 'diagnosa_utama', 'diagnosa_tambahan', 'keluhan_utama', 'terapi_tindakan', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path',        'assessmentNotFilled', 'tindakanMedis'));

            case 'rekonsiliasi_obat':
                $dokter = Employee::where('is_doctor', 1)->get();

                return view('pages.simrs.erm.form.farmasi.rekonsiliasi-obat', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

            case 'pengkajian_lanjutan':
                $form = FormKategori::all();
                $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();
                $pengkajian = PengkajianLanjutan::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.pengkajian-lanjutan', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian', 'path'));

            case 'tindakan_medis':
                $tindakan_medis = TindakanMedis::all();
                // Gabungkan semua data dalam satu collection
                $allData = collect();

                // [UBAH] Kita akan standarkan agar value yang dikirim adalah employee_id
                // 1. Ambil data dokter (dari tabel Doctor)
                $doctors = Doctor::with('employee', 'departements')->get();
                foreach ($doctors as $doctor) {
                    // Hanya proses jika dokter memiliki relasi employee yang valid
                    if ($doctor->employee) {
                        $allData->push([
                            'employee_id' => $doctor->employee_id, // Ini akan jadi value di <option>
                            'name' => $doctor->employee->fullname ?? 'N/A',
                            'department' => $doctor->department_from_doctors->name ?? 'Dokter', // Beri label grup yang jelas
                        ]);
                    }
                }

                // 2. Ambil data perawat (jobPosition id = 7)
                $nurses = Employee::whereHas('jobPosition', function ($query) {
                    $query->where('id', 7);
                })->where('is_active', 1)->get();
                foreach ($nurses as $nurse) {
                    $allData->push([
                        'employee_id' => $nurse->id, // Ini akan jadi value di <option>
                        'name' => $nurse->fullname ?? 'N/A',
                        'department' => 'Perawat', // Beri label grup yang jelas
                    ]);
                }

                // 3. Ambil data bidan (jobPosition id = 26)
                $midwives = Employee::whereHas('jobPosition', function ($query) {
                    $query->where('id', 26);
                })->where('is_active', 1)->get();
                foreach ($midwives as $midwife) {
                    $allData->push([
                        'employee_id' => $midwife->id, // Ini akan jadi value di <option>
                        'name' => $midwife->fullname ?? 'N/A',
                        'department' => 'Bidan', // Beri label grup yang jelas
                    ]);
                }

                // 4. Group by department
                // [UBAH] Ubah nama variabel agar lebih deskriptif
                $groupedPersonnel = $allData->groupBy('department');

                $tindakan_medis_yang_dipakai = OrderTindakanMedis::where('registration_id', $registration->id)->get();
                $kelas_rawats = \App\Models\SIMRS\KelasRawat::all();
                $dTindakan = \App\Models\SIMRS\Departement::with('grup_tindakan_medis.tindakan_medis')->get();

                // [UBAH] Kirim variabel yang sudah diubah namanya ke view
                return view('pages.simrs.erm.form.layanan.tindakan-medis', compact('groupedPersonnel', 'registration', 'tipeRegis', 'doctors', 'registrations', 'dTindakan', 'menu', 'departements', 'jadwal_dokter', 'tindakan_medis', 'tindakan_medis_yang_dipakai', 'kelas_rawats', 'path'));
            case 'pemakaian_alat':
                $list_peralatan = Peralatan::all();
                $alat_medis_yang_dipakai = OrderAlatMedis::where('registration_id', $registration->id)->get();
                $doctors = Doctor::with('employee')
                    ->whereHas('employee')
                    ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
                    ->get();

                return view('pages.simrs.erm.form.layanan.pemakaian-alat', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'list_peralatan', 'alat_medis_yang_dipakai', 'doctors', 'path'));

            case 'visite_dokter':
                $visite = DoctorVisit::where('registration_id', $registration->id)->get();
                $doctors = Doctor::with('employee')
                    ->whereHas('employee')
                    ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
                    ->get();

                return view('pages.simrs.erm.form.layanan.visite-dokter', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'visite', 'doctors', 'path'));

            case 'patologi_klinik':
                $patient = $registration->patient;
                $laboratoriumDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
                    $query->where('name', 'like', '%lab%');
                })->get();
                $laboratorium_categories = KategoriLaboratorium::all();
                $laboratorium_tarifs = TarifParameterLaboratorium::all();
                $kelas_rawats = KelasRawat::all();
                // Mengambil semua order laboratorium untuk registrasi yang spesifik
                $laboratoriumOrders = OrderLaboratorium::where('registration_id', $registration->id)
                    // Eager loading relasi untuk menghindari N+1 query problem. Ini SANGAT PENTING untuk performa.
                    ->with([
                        'doctor.employee', // Memuat relasi dokter dan data pegawainya
                        'order_parameter_laboratorium.parameter_laboratorium' // Memuat detail order & nama parameternya
                    ])
                    // Mengurutkan data agar order terbaru muncul di paling atas
                    ->orderBy('order_date', 'desc')
                    // Eksekusi query dan dapatkan hasilnya sebagai koleksi
                    ->get();
                // $order_lab = OrderLaboratorium::where('registration_id', $registration->id)->get();

                $groupPenjaminId = GroupPenjamin::where('id', $registration->penjamin->group_penjamin_id)->first()->id;
                $tipeRegis = $registration->registration_type;
                $kelasRawat = in_array($tipeRegis, ['rawat-jalan', 'igd', 'odc']) ? 'RAWAT JALAN' : 'RAWAT INAP';
                $jaminan = $registration->penjamin->name;
                if ($jaminan === 'Umum') {
                    $penjamin = 'Jaminan Pribadi';
                } elseif ($jaminan === 'BPJS') {
                    $penjamin = "BPJS Kesehatan";
                } else {
                    $penjamin = $registration->penjamin->name;
                }

                // Group doctors by department
                $doctors = Doctor::with(['employee', 'departements', 'department_from_doctors'])
                    ->whereHas('department_from_doctors')
                    ->get();

                $groupedDoctors = [];
                foreach ($doctors as $doctor) {
                    if ($doctor->department_from_doctors) {
                        $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
                    }
                }

                return view('pages.simrs.erm.form.layanan.patologi-klinik', compact('laboratoriumOrders', 'laboratoriumDoctors', 'laboratorium_categories', 'laboratorium_tarifs', 'patient', 'groupPenjaminId', 'groupedDoctors', 'penjamin', 'kelas_rawats', 'kelasRawat', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'transfer_pasien_perawat':
                $pengkajian = TransferPasienAntarRuangan::firstWhere('registration_id', $registration->id);
                $cppt = CPPT::firstWhere(['registration_id' => $registration->id, 'tipe_cppt' => 'dokter']);
                // dd($cppt);

                return view('pages.simrs.erm.form.perawat.transfer_pasien_perawat', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'cppt', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_anak':
                $pengkajian = EWSAnak::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.ews-anak', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_dewasa':
                $pengkajian = EWSDewasa::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.ews-dewasa', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'ews_obstetri':
                $pengkajian = EWSObstetri::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.ews-obstetri', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'assesment_gadar':
                $pengkajian = AssesmentKeperawatanGadar::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.assesment-gadar', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'rujuk_antar_rs':
                $pengkajian = RujukAntarRS::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.perawat.rujuk-antar-rs', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'resep_harian':

                $gudangs = WarehouseMasterGudang::where('apotek', 1)->where('warehouse', 0)->get();
                $barangs = WarehouseBarangFarmasi::with(['stored_items', 'satuan'])->get();
                $default_column = 'rajal_default';
                if ($registration->registration_type == 'rawat-inap') {
                    $default_column = 'ranap_default';
                }
                $default_apotek = WarehouseMasterGudang::select('id')->where($default_column, 1)->first();
                $pengkajian = RujukAntarRS::where('registration_id', $registration->id)->first();
                $reseps = FarmasiResepHarian::with(['items', 'items.barang', 'doctor', 'doctor.employee', 'gudang'])->where('registration_id', $registration->id)->get();

                return view('pages.simrs.erm.form.perawat.resep-harian', compact('reseps', 'gudangs', 'barangs', 'default_apotek', 'pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'radiologi':
                $pengkajian = AssesmentKeperawatanGadar::where('registration_id', $registration->id)->first();
                $radiologyDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
                    $query->where('name', 'like', '%radiologi%');
                })->get();
                $patient = $registration->patient;
                $groupPenjaminId = GroupPenjamin::where('id', $registration->penjamin->group_penjamin_id)->first()->id;

                $orders = [];
                OrderRadiologi::where('registration_id', $registration->id)
                    ->get()
                    ->each(function ($order) use (&$orders) {
                        $orders[$order->id] = $order;
                    });
                $radiology_categories = KategoriRadiologi::all();
                $radiology_tarifs = TarifParameterRadiologi::all();
                $kelas_rawats = KelasRawat::all();

                return view('pages.simrs.erm.form.perawat.radiologi', compact('kelas_rawats', 'groupPenjaminId', 'patient', 'radiology_categories', 'radiology_tarifs', 'orders', 'radiologyDoctors', 'pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'infusion_monitor':
                return view('pages.simrs.erm.form.perawat.infusion-monitor', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'surveilans_infeksi':
                // Gunakan firstOrNew untuk menangani form baru dan edit
                $pengkajian = HospitalInfectionSurveillance::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.surveilans-infeksi', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'discharge_planning':
                $pengkajian = DischargePlanning::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.discharge-planning', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'checklist_keperawatan':
                $pengkajian = NursingActivityChecklist::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.checklist-keperawatan', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap':
                $pengkajian = InpatientInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.asesmen-awal-ranap-dewasa', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_anak':
                $pengkajian = ChildInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.asesmen-awal-anak', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_lansia':
                $pengkajian = GeriatricInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.asesmen-awal-lansia', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_neonatus':
                $pengkajian = NeonatusInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.asesmen-awal-neonatus', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_kebidanan':
                $pengkajian = MidwiferyInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.asesmen-awal-kebidanan', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'pengkajian_awal_neonatus':
                $pengkajian = NeonatusInitialAssessmentDoctor::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.dokter.pengkajian-awal-neonatus', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_dokter':
                $pengkajianNurse = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);
                $pengkajian = DoctorInitialAssessment::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.dokter.asesmen-awal-dokter', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajianNurse'));

            case 'echocardiography':
                $pengkajian = Echocardiography::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.penunjang.echocardiography', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'upload_dokumen':
                $documentCategories = \App\Models\DocumentCategory::orderBy('name')->get();

                return view('pages.simrs.erm.form.penunjang.upload-dokumen', compact('registration', 'tipeRegis', 'doctors', 'documentCategories', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'pemeriksaan_awal_ranap':
                $pengkajian = InpatientInitialExamination::firstOrNew(['registration_id' => $registration->id]);

                return view('pages.simrs.erm.form.perawat.pemeriksaan-awal-ranap', compact('registration', 'tipeRegis', 'doctors', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'rencana_operasi':
                // 1. [Template Dasar] Mengambil data pengkajian
                $pengkajian = RujukAntarRS::firstWhere('registration_id', $registration->id);

                // 2. [Data Master] Mengambil data untuk dropdown di modal
                $kelas_rawats = KelasRawat::all();
                $jenisOperasi = TipeOperasi::orderBy('tipe')->get();
                $kategoriOperasi = KategoriOperasi::orderBy('nama_kategori')->get();
                $ruangans_operasi = Room::where('ruangan', 'OK')->orderBy('ruangan', 'asc')->get();
                $doctors = Doctor::with('employee')->whereHas('employee', function ($q) {
                    $q->where('is_active', true);
                })->get();

                $orderOperasi = OrderOperasi::with([
                    'tipeOperasi',
                    'kategoriOperasi',
                    'jenisOperasi',
                    'doctorOperator.employee', // Memuat relasi dokter operator
                    'prosedurOperasi' => function ($query) {
                        $query->with([
                            'tindakanOperasi',
                            'dokterOperator.employee',
                            'assDokterOperator1.employee',
                            'assDokterOperator2.employee',
                            'assDokterOperator3.employee',
                            'dokterAnastesi.employee',
                            'assDokterAnastesi.employee',
                            'dokterResusitator.employee',
                            'createdByUser',
                            // Anda bisa menambahkan relasi dokter tambahan jika perlu
                        ]);
                    },
                ])->where('registration_id', $registration->id)->get();

                // 4. Mengirim SEMUA variabel yang dibutuhkan ke view
                return view('pages.simrs.erm.form.layanan.rencana-operasi', compact(
                    // Variabel standar
                    'pengkajian',
                    'registration',
                    'tipeRegis',
                    'doctors',
                    'registrations',
                    'menu',
                    'departements',
                    'jadwal_dokter',
                    'path',

                    // Variabel untuk modal
                    'kelas_rawats',
                    'jenisOperasi',
                    'kategoriOperasi',
                    'ruangans_operasi',
                    'doctors',
                    'orderOperasi'
                ));

            case 'rencana_persalinan':
                $pengkajian = RujukAntarRS::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.layanan.rencana-persalinan', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'pengkajian_gizi':
                $form = FormKategori::all();
                $daftar_pengkajian = PengkajianLanjutan::where('registration_id', $registration->id)->get();
                $pengkajian = PengkajianLanjutan::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.pengkajian-lanjutan', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'form', 'daftar_pengkajian', 'path'));

            case 'cppt_gizi':
                $rawData = null;

                // dd($path);

                // Ambil data sesuai dengan path
                if ($path === 'igd') {
                    $rawData = Triage::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'poliklinik') {
                    $rawData = PengkajianNurseRajal::firstWhere('registration_id', $registration->id);
                } elseif ($path === 'rawat-inap') {
                    $rawData = InpatientInitialExamination::firstWhere('registration_id', $registration->id);
                }

                // dd($rawData);

                // Generalisasi data ke format yang konsisten
                $data = null;
                if ($rawData) {
                    $data = (object) [];

                    // Mapping untuk Triage
                    if ($rawData instanceof \App\Models\SIMRS\Pelayanan\Triage) {
                        $data->pr = $rawData->pr;
                        $data->rr = $rawData->rr;
                        $data->bp = $rawData->bp;
                        $data->temperatur = $rawData->temperatur;
                        $data->body_height = $rawData->body_height;
                        $data->body_weight = $rawData->body_weight;
                        $data->sp02 = $rawData->sp02;
                        $data->skor_nyeri = null; // Triage tidak punya skor nyeri
                        $data->keluhan_utama = null; // Triage tidak punya keluhan utama
                        $data->diagnosa_keperawatan = null; // Triage tidak punya diagnosa keperawatan
                    }
                    // Mapping untuk PengkajianNurseRajal
                    elseif ($rawData instanceof \App\Models\SIMRS\Pengkajian\PengkajianNurseRajal) {
                        $data->created_at = $rawData->created_at;
                        $data->allergy_medicine = $rawData->allergy_medicine;
                        $data->pr = $rawData->pr;
                        $data->rr = $rawData->rr;
                        $data->bp = $rawData->bp;
                        $data->temperatur = $rawData->temperatur;
                        $data->body_height = $rawData->body_height;
                        $data->body_weight = $rawData->body_weight;
                        $data->sp02 = $rawData->sp02;
                        $data->skor_nyeri = $rawData->skor_nyeri;
                        $data->keluhan_utama = $rawData->keluhan_utama;
                        $data->diagnosa_keperawatan = $rawData->diagnosa_keperawatan;
                    }
                    // Mapping untuk InpatientInitialExamination
                    elseif ($rawData instanceof \App\Models\InpatientInitialExamination) {
                        $data->pr = $rawData->vital_sign_pr;
                        $data->rr = $rawData->vital_sign_rr;
                        $data->bp = $rawData->vital_sign_bp;
                        $data->temperatur = $rawData->vital_sign_temperature;
                        $data->body_height = $rawData->anthropometry_height;
                        $data->body_weight = $rawData->anthropometry_weight;
                        $data->sp02 = null; // InpatientInitialExamination tidak punya sp02
                        $data->skor_nyeri = null; // InpatientInitialExamination tidak punya skor nyeri
                        $data->keluhan_utama = null; // InpatientInitialExamination tidak punya keluhan utama
                        $data->diagnosa_keperawatan = null; // InpatientInitialExamination tidak punya diagnosa keperawatan
                    }
                }

                $perawat = Employee::whereHas('organization', function ($query) {
                    $query->whereIn('name', [
                        'Rawat Jalan',
                        'Rawat Inap',
                        'Perinatologi',
                        'VK & PONEK',
                        'IGD',
                        'OK',
                    ]);
                })->get();
                $pengkajian = CPPT::firstWhere('registration_id', $registration->id);
                // dd($data);

                return view('pages.simrs.erm.form.gizi.cppt-gizi', compact('registration', 'tipeRegis', 'doctors', 'registrations', 'pengkajian', 'menu', 'departements', 'jadwal_dokter', 'perawat', 'path', 'data'));

            case 'mst_gizi':
                $pengkajian = SkriningGiziDewasa::firstWhere('registration_id', $registration->id);

                return view('pages.simrs.erm.form.gizi.mst-gizi', compact('pengkajian', 'registration', 'tipeRegis', 'doctors', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            default:
                return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'path'));
        }
    }
}
