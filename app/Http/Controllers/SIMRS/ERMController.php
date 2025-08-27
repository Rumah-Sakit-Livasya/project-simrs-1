<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\ChildInitialAssessment;
use App\Models\DischargePlanning;
use App\Models\DoctorInitialAssessment;
use App\Models\Echocardiography;
use App\Models\Employee;
use App\Models\GeriatricInitialAssessment;
use App\Models\HospitalInfectionSurveillance;
use App\Models\InpatientInitialAssessment;
use App\Models\InpatientInitialExamination;
use App\Models\MidwiferyInitialAssessment;
use App\Models\NeonatusInitialAssessment;
use App\Models\NeonatusInitialAssessmentDoctor;
use App\Models\NursingActivityChecklist;
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
use App\Models\SIMRS\Pengkajian\FormTemplate;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use App\Models\SIMRS\TindakanMedis;
use App\Models\StoredBarangFarmasi;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

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

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeSurveilansInfeksi(Request $request)
    {
        // Validasi dasar (bisa dibuat lebih detail)
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            // ... tambahkan validasi lain jika perlu
        ]);

        DB::beginTransaction();
        try {
            $dataToStore = $request->except(['_token', 'signatures']);
            $dataToStore['user_id'] = Auth::id();

            // Gunakan updateOrCreate untuk menyimpan data utama
            $surveilans = HospitalInfectionSurveillance::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            // Gunakan saveSignatureFile untuk menyimpan tanda tangan
            if ($request->has('signatures')) {
                foreach ($request->signatures as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                        $oldPath = optional($surveilans->signatures()->where('role', $role)->first())->signature;
                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "surveilans_{$surveilans->id}_{$role}");

                        $surveilans->signatures()->updateOrCreate(
                            ['role' => $role],
                            ['pic' => $signatureData['pic'], 'signature' => $newPath]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data Surveilans Infeksi berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Surveilans Infeksi: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeDischargePlanning(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
        ]);

        DB::beginTransaction();
        try {
            // Gabungkan tanggal dan jam penjelasan menjadi satu timestamp
            $waktuPenjelasan = null;
            if ($request->filled('tgl_penjelasan') && $request->filled('jam_penjelasan')) {
                $waktuPenjelasan = $request->tgl_penjelasan . ' ' . $request->jam_penjelasan;
            }

            // Siapkan data untuk disimpan
            $dataToStore = $request->except(['_token', 'tgl_penjelasan', 'jam_penjelasan']);
            $dataToStore['user_id'] = Auth::id();
            $dataToStore['waktu_penjelasan'] = $waktuPenjelasan;

            $planning = DischargePlanning::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            DB::commit();
            return response()->json(['success' => 'Data Rencana Pulang berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Rencana Pulang: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeChecklistKeperawatan(Request $request)
    {
        $request->validate(['registration_id' => 'required|exists:registrations,id']);

        DB::beginTransaction();
        try {
            // Kita hanya perlu menyimpan satu field JSON besar
            $checklistData = $request->except(['_token', 'registration_id']);

            $checklist = NursingActivityChecklist::updateOrCreate(
                ['registration_id' => $request->registration_id],
                [
                    'user_id' => Auth::id(),
                    'checklist_data' => $checklistData
                ]
            );

            DB::commit();
            return response()->json(['success' => 'Checklist Kegiatan Keperawatan berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Checklist Keperawatan: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php

    public function storeAsesmenAwalRanap(Request $request)
    {
        // Validasi dasar (bisa dibuat lebih detail jika perlu)
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'tgl_masuk' => 'nullable|date_format:Y-m-d',
            'jam_masuk' => 'nullable|date_format:H:i',
            'signatures' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Gabungkan tanggal dan jam menjadi satu timestamp
            $waktuMasuk = null;
            if ($request->filled('tgl_masuk') && $request->filled('jam_masuk')) {
                // Format input 'Y-m-d' (dari type="date") dan 'H:i' (dari type="time")
                $waktuMasuk = Carbon::createFromFormat('Y-m-d H:i', $request->tgl_masuk . ' ' . $request->jam_masuk)->toDateTimeString();
            }

            // 2. Siapkan data utama untuk disimpan/diperbarui
            // Kita mengambil semua data kecuali yang akan ditangani secara terpisah
            $dataToStore = $request->except(['_token', 'tgl_masuk', 'jam_masuk', 'signatures']);
            $dataToStore['user_id'] = Auth::id(); // Selalu catat user terakhir yang melakukan aksi
            $dataToStore['waktu_masuk_ruangan'] = $waktuMasuk;

            // 3. Gunakan updateOrCreate untuk menyimpan data asesmen
            $asesmen = InpatientInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id], // Kunci untuk mencari
                $dataToStore // Data untuk diupdate atau dibuat
            );

            // 4. Gunakan saveSignatureFile untuk menyimpan tanda tangan
            if ($request->has('signatures')) {
                foreach ($request->signatures as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {

                        // Cari path file lama sebelum di-update
                        $oldPath = optional($asesmen->signatures()->where('role', $role)->first())->signature;

                        // Simpan file baru dengan saveSignatureFile
                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_ranap_{$asesmen->id}_{$role}");

                        // Lakukan Update or Create untuk signature dengan role ini
                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role], // Kunci unik
                            [
                                'pic' => $signatureData['pic'], // Data baru
                                'signature' => $newPath         // Data baru
                            ]
                        );

                        // Hapus file tanda tangan lama jika ada
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Rawat Inap berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Ranap: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan hubungi administrator.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeAsesmenAwalRanapAnak(Request $request)
    {
        $request->validate(['registration_id' => 'required|exists:registrations,id']);
        DB::beginTransaction();
        try {
            $dataToStore = $request->except(['_token', 'signatures']);
            $dataToStore['user_id'] = Auth::id();

            $asesmen = ChildInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            // Gunakan saveSignatureFile untuk menyimpan tanda tangan jika ada
            if ($request->has('signatures')) {
                foreach ($request->signatures as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                        $oldPath = optional($asesmen->signatures()->where('role', $role)->first())->signature;
                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_anak_{$asesmen->id}_{$role}");

                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role],
                            ['pic' => $signatureData['pic'], 'signature' => $newPath]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Anak berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Anak: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeAsesmenAwalRanapLansia(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'tgl_masuk' => 'nullable|date_format:Y-m-d',
            'jam_masuk' => 'nullable|date_format:H:i',
            'signatures' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Gabungkan tanggal dan jam menjadi satu timestamp
            $waktuMasuk = null;
            if ($request->filled('tgl_masuk') && $request->filled('jam_masuk')) {
                // Format input 'Y-m-d' (dari type="date") dan 'H:i' (dari type="time")
                $waktuMasuk = Carbon::createFromFormat('Y-m-d H:i', $request->tgl_masuk . ' ' . $request->jam_masuk)->toDateTimeString();
            }

            // 2. Siapkan data utama untuk disimpan/diperbarui
            $dataToStore = $request->except(['_token', 'tgl_masuk', 'jam_masuk', 'signatures']);
            $dataToStore['user_id'] = Auth::id();
            $dataToStore['waktu_masuk_ruangan'] = $waktuMasuk;

            // 3. Gunakan updateOrCreate untuk menyimpan data asesmen
            $asesmen = GeriatricInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id], // Kunci untuk mencari
                $dataToStore // Data untuk diupdate atau dibuat
            );

            // 4. Gunakan saveSignatureFile untuk menyimpan tanda tangan
            if ($request->has('signatures')) {
                foreach ($request->signatures as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {

                        $oldPath = optional($asesmen->signatures()->where('role', 'like', '%' . $role . '%')->first())->signature;

                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_lansia_{$asesmen->id}_{$role}");

                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role], // Kunci unik
                            [
                                'pic' => $signatureData['pic'], // Data baru
                                'signature' => $newPath         // Data baru
                            ]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Lansia berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Lansia: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan hubungi administrator.'], 500);
        }
    }

    public function storeAsesmenAwalRanapNeonatus(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'tgl_masuk' => 'nullable|date_format:Y-m-d',
            'jam_masuk_pasien' => 'nullable|date_format:H:i',
            'signatures' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Gabungkan tanggal dan jam menjadi satu timestamp
            $waktuMasuk = null;
            if ($request->filled('tgl_masuk') && $request->filled('jam_masuk_pasien')) {
                // Format input 'Y-m-d' (dari type="date") dan 'H:i' (dari type="time")
                $waktuMasuk = Carbon::createFromFormat('Y-m-d H:i', $request->tgl_masuk . ' ' . $request->jam_masuk_pasien)->toDateTimeString();
            }

            // 2. Siapkan data utama untuk disimpan/diperbarui
            $dataToStore = $request->except(['_token', 'tgl_masuk', 'jam_masuk_pasien', 'signatures']);
            $dataToStore['user_id'] = Auth::id();
            $dataToStore['waktu_masuk_ruangan'] = $waktuMasuk;

            // 3. Gunakan updateOrCreate untuk menyimpan data asesmen
            $asesmen = NeonatusInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id], // Kunci untuk mencari
                $dataToStore // Data untuk diupdate atau dibuat
            );

            // 4. Logika lengkap untuk menyimpan tanda tangan
            if ($request->has('signatures')) {
                foreach ($request->signatures as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {

                        $oldPath = optional($asesmen->signatures()->where('role', $role)->first())->signature;

                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_neonatus_{$asesmen->id}_{$role}");

                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role], // Kunci unik
                            [
                                'pic' => $signatureData['pic'], // Data baru
                                'signature' => $newPath         // Data baru
                            ]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Neonatus berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Neonatus: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan hubungi administrator.'], 500);
        }
    }

    // app/Http/Controllers/SIMRS/ERMController.php
    public function storeAsesmenAwalKebidanan(Request $request)
    {
        $request->validate(['registration_id' => 'required|exists:registrations,id']);
        DB::beginTransaction();
        try {
            $waktuMasuk = null;
            if ($request->filled('tgl_masuk') && $request->filled('jam_dilayani1')) {
                $waktuMasuk = Carbon::createFromFormat('d-m-Y H:i', $request->tgl_masuk . ' ' . $request->jam_dilayani1)->toDateTimeString();
            }

            $dataToStore = $request->except(['_token', 'tgl_masuk', 'jam_dilayani1', 'signatures']);
            $dataToStore['user_id'] = Auth::id();
            $dataToStore['waktu_masuk_ruangan'] = $waktuMasuk;

            $asesmen = MidwiferyInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            if ($request->has('signatures')) {
                foreach ($request->input('signatures', []) as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {

                        $oldPath = optional($asesmen->signatures()->where('role', $role)->first())->signature;

                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_kebidanan_{$asesmen->id}_{$role}");

                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role], // Kunci unik
                            [
                                'pic' => $signatureData['pic'], // Data baru
                                'signature' => $newPath         // Data baru
                            ]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Kebidanan berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Kebidanan: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function storeNeonatusInitialAssesmentDoctor(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'data' => 'required|array'
        ]);
        DB::beginTransaction();
        try {
            // 1. Siapkan data utama untuk disimpan/diperbarui
            $dataToStore = [
                'user_id' => Auth::id(),
                'data' => $request->data,
            ];

            // 2. Simpan/update data pengkajian awal neonatus dokter
            $pengkajian = \App\Models\NeonatusInitialAssessmentDoctor::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            // 3. Logika lengkap untuk menyimpan tanda tangan
            if ($request->has('signatures')) {
                foreach ($request->input('signatures', []) as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {

                        $oldPath = optional($pengkajian->signatures()->where('role', $role)->first())->signature;

                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "pengkajian_awal_neonatus_dokter_{$pengkajian->id}_{$role}");

                        $pengkajian->signatures()->updateOrCreate(
                            ['role' => $role], // Kunci unik
                            [
                                'pic' => $signatureData['pic'] ?? null,
                                'signature' => $newPath
                            ]
                        );

                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Pengkajian Awal Neonatus (Dokter) berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Pengkajian Awal Neonatus (Dokter): ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function storeAsesmenAwalDokter(Request $request)
    {
        // TAMBAHKAN BARIS INI UNTUK MELIHAT DATA MENTAH DI FILE LOG
        Log::info('Mencoba menyimpan Asesmen Dokter. Data request:', $request->all());

        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'status' => 'required|in:draft,final',

            // Validasi Tanda Vital (gunakan dot notation untuk array)
            'tanda_vital.pr' => 'nullable|string|max:100', // Sesuaikan jika harus numeric
            'tanda_vital.rr' => 'nullable|string|max:100',
            'tanda_vital.bp' => 'nullable|string|max:100',
            'tanda_vital.temperatur' => 'nullable|string|max:100',
            'tanda_vital.height_badan' => 'nullable|numeric',
            'tanda_vital.weight_badan' => 'nullable|numeric',
            'tanda_vital.spo2' => 'nullable|string|max:100',

            // Validasi Tanggal dan Waktu
            'tgl_masuk' => 'required|date_format:d-m-Y',
            'jam_masuk' => 'required|date_format:H:i',
            'tgl_dilayani' => 'required|date_format:d-m-Y',
            'jam_dilayani' => 'required|date_format:H:i',

            // Anda bisa menambahkan validasi lain jika perlu
            'pemeriksaan_fisik' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $waktuMasuk = $request->filled('tgl_masuk') && $request->filled('jam_masuk')
                ? Carbon::createFromFormat('d-m-Y H:i', $request->tgl_masuk . ' ' . $request->jam_masuk)->toDateTimeString()
                : null;

            $waktuDilayani = $request->filled('tgl_dilayani') && $request->filled('jam_dilayani')
                ? Carbon::createFromFormat('d-m-Y H:i', $request->tgl_dilayani . ' ' . $request->jam_dilayani)->toDateTimeString()
                : null;

            // Kelompokkan data ke dalam format yang sesuai dengan struktur JSON di database
            $dataToStore = [
                'user_id' => Auth::id(),
                'status' => $request->status,
                'waktu_masuk' => $waktuMasuk,
                'waktu_dilayani' => $waktuDilayani,
                'tanda_vital' => $request->input('tanda_vital', []),
                'anamnesis' => $request->input('anamnesis', []),
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'diagnosa_kerja' => $request->diagnosa_kerja,
                'diagnosa_banding' => $request->diagnosa_banding,
                'terapi_tindakan' => $request->terapi_tindakan,
                'gambar_anatomi' => $request->input('gambar_anatomi', []),
                'edukasi' => $request->input('edukasi', []),
                'evaluasi_penyakit' => $request->input('evaluasi_penyakit', []),
                'rencana_tindak_lanjut_pasien' => $request->input('rencana_tindak_lanjut_pasien', []),
            ];

            $asesmen = DoctorInitialAssessment::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            // Logika penyimpanan tanda tangan (sama seperti yang sudah ada)
            if ($request->has('signatures')) {
                foreach ($request->input('signatures', []) as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                        $oldPath = optional($asesmen->signatures()->where('role', 'dokter_pemeriksa')->first())->signature;
                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "asesmen_awal_dokter_{$asesmen->id}_{$role}");
                        $asesmen->signatures()->updateOrCreate(
                            ['role' => $role],
                            ['pic' => $signatureData['pic'], 'signature' => $newPath]
                        );
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Asesmen Awal Dokter berhasil disimpan sebagai ' . $request->status . '!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Asesmen Awal Dokter: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function storeEchocardiography(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'status' => 'required|in:draft,final'
        ]);

        DB::beginTransaction();
        try {
            // Data sudah terstruktur dalam array dari form, jadi kita bisa langsung menyimpannya.
            $dataToStore = [
                'user_id' => Auth::id(),
                'status' => $request->status,
                'aorta' => $request->input('aorta', []),
                'left_atrium' => $request->input('left_atrium', []),
                'right_ventricle' => $request->input('right_ventricle', []),
                'left_ventricle' => $request->input('left_ventricle', []),
                'mitral_valve' => $request->input('mitral_valve', []),
                'other_valves' => $request->input('other_valves', []),
                'pericardial_effusion' => $request->input('pericardial_effusion', []),
                'comments' => $request->input('comments', []),
                'conclussion' => $request->conclussion,
                'advice' => $request->advice,
            ];

            $echo = Echocardiography::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $dataToStore
            );

            // Logic untuk signature (jika ditambahkan di masa depan)
            if ($request->has('signatures')) {
                foreach ($request->input('signatures', []) as $role => $signatureData) {
                    if (!empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                        $newPath = $this->saveSignatureFile($signatureData['signature_image'], "echocardiography_{$echo->id}_{$role}");
                        $echo->signatures()->updateOrCreate(
                            ['role' => $role],
                            ['pic' => $signatureData['pic'], 'signature' => $newPath]
                        );
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data Echocardiography berhasil disimpan sebagai ' . $request->status . '!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Echocardiography: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function storeInpatientInitialExamination(Request $request)
    {
        // --- PERBAIKI BLOK VALIDASI INI ---
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',

            // Tanda Vital: Sebagian besar harus numerik, kecuali Tensi (BP) yang bisa "120/80"
            'vital_sign_pr' => 'nullable|numeric',
            'vital_sign_rr' => 'nullable|numeric',
            'vital_sign_bp' => 'nullable|string|max:20', // Contoh: "120/80 mmHg"
            'vital_sign_temperature' => 'nullable|numeric',

            // Antropometri: Semua harus numerik
            'anthropometry_height' => 'nullable|numeric',
            'anthropometry_weight' => 'nullable|numeric',
            'anthropometry_bmi' => 'nullable|numeric',
            'anthropometry_bmi_category' => 'nullable|string|max:100',
            'anthropometry_chest_circumference' => 'nullable|numeric',
            'anthropometry_abdominal_circumference' => 'nullable|numeric',

            // Alergi & Catatan: Boleh string
            'allergy_medicine' => 'nullable|string',
            'allergy_food' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'registration_notes' => 'nullable|string',
        ], [
            // Tambahkan pesan custom agar lebih jelas bagi pengguna
            'numeric' => 'Kolom :attribute harus berupa angka.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;
            $data['user_id'] = Auth::id();

            // Ubah string dari tags input menjadi array
            // Gunakan trim untuk menghapus spasi ekstra jika ada
            $data['allergy_medicine'] = $request->filled('allergy_medicine')
                ? array_map('trim', explode(',', $request->allergy_medicine))
                : null;
            $data['allergy_food'] = $request->filled('allergy_food')
                ? array_map('trim', explode(',', $request->allergy_food))
                : null;

            \App\Models\InpatientInitialExamination::updateOrCreate(
                ['registration_id' => $request->registration_id],
                $data
            );

            DB::commit();
            return response()->json(['success' => 'Data Pemeriksaan Awal Rawat Inap berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan Pemeriksaan Awal Rawat Inap: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Helper function untuk menyimpan file tanda tangan dari data base64.
     * (Pastikan fungsi ini sudah ada di dalam ERMController)
     */
    private function saveSignatureFile(string $base64Image, string $fileNamePrefix): string
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $fileName = $fileNamePrefix . '_' . time() . '.png';
        $path = 'signatures/' . $fileName;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    // Tambahkan metode ini di dalam ERMController

    public function getUploadedDocuments(Request $request, Registration $registration)
    {
        if ($request->ajax()) {
            $data = \App\Models\UploadedDocument::with('user', 'category')
                ->where('registration_id', $registration->id)
                ->latest();

            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = route('erm.dokumen.view', $row->id);
                    $btn = '<a href="' . $viewUrl . '" target="_blank" class="btn btn-sm btn-success mr-2" title="Lihat Dokumen"><i class="fas fa-eye"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete-document" data-id="' . $row->id . '" title="Hapus Dokumen"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function storeUploadedDocument(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png', // max 5MB
            'registration_id' => 'required|exists:registrations,id',
            'document_category_id' => 'required|exists:document_categories,id',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $registrationId = $request->registration_id;
        $path = "documents/{$registrationId}";
        $storedFile = $file->store($path, 'public');

        \App\Models\UploadedDocument::create([
            'registration_id' => $registrationId,
            'user_id' => Auth::id(),
            'document_category_id' => $request->document_category_id,
            'description' => $request->description,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => basename($storedFile),
            'file_path' => $storedFile,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json(['success' => 'Dokumen berhasil diunggah!']);
    }

    public function viewUploadedDocument(\App\Models\UploadedDocument $document)
    {
        // Pastikan file ada sebelum mencoba menampilkannya
        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->response($document->file_path);
        }
        abort(404, 'File tidak ditemukan.');
    }

    public function destroyUploadedDocument(\App\Models\UploadedDocument $document)
    {
        try {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            // Hapus record dari database
            $document->delete();
            return response()->json(['success' => 'Dokumen berhasil dihapus!']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus dokumen: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus dokumen.'], 500);
        }
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
                $barangs = WarehouseBarangFarmasi::with(["stored_items", "satuan"])->get();

                $default_column = "rajal_default";
                if ($registration->registration_type == "rawat-inap") $default_column = "ranap_default";
                $default_apotek = WarehouseMasterGudang::select('id')->where($default_column, 1)->first();

                return view('pages.simrs.erm.form.dokter.cppt-dokter', compact('gudangs', 'barangs', 'default_apotek', 'registration', 'registrations', 'pengkajian', 'menu', 'departements', 'jadwal_dokter', 'dokter', 'path'));

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
                $groupedDoctors = Doctor::with('employee', 'departements')->get()->groupBy(function ($doctor) {
                    return $doctor->department_from_doctors->name;
                });
                $tindakan_medis_yang_dipakai = OrderTindakanMedis::where('registration_id', $registration->id)->get();
                $kelas_rawats = \App\Models\SIMRS\KelasRawat::all();
                return view('pages.simrs.erm.form.layanan.tindakan-medis', compact('groupedDoctors', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'tindakan_medis', 'tindakan_medis_yang_dipakai', 'kelas_rawats', 'path'));

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

            case 'resep_harian':
                $pengkajian = RujukAntarRS::where('registration_id', $registration->id)->first();
                return view('pages.simrs.erm.form.perawat.resep-harian', compact('pengkajian', 'registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'infusion_monitor':
                // Di sini kita tidak perlu mengambil data awal ($pengkajian) karena
                // datanya akan dimuat oleh Datatables. Kita hanya perlu mengirim
                // variabel $registration yang penting untuk view.
                return view('pages.simrs.erm.form.perawat.infusion-monitor', compact('registration', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'path'));

            case 'surveilans_infeksi':
                // Gunakan firstOrNew untuk menangani form baru dan edit
                $pengkajian = HospitalInfectionSurveillance::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.surveilans-infeksi', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'discharge_planning':
                $pengkajian = DischargePlanning::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.discharge-planning', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'checklist_keperawatan':
                $pengkajian = NursingActivityChecklist::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.checklist-keperawatan', compact('registration', 'pengkajian', 'path',  'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap':
                $pengkajian = InpatientInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.asesmen-awal-ranap-dewasa', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_anak':
                $pengkajian = ChildInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.asesmen-awal-anak', compact('registration', 'pengkajian', 'path',  'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_lansia':
                $pengkajian = GeriatricInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.asesmen-awal-lansia', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_ranap_neonatus':
                $pengkajian = NeonatusInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.asesmen-awal-neonatus', compact('registration', 'pengkajian', 'path',  'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_kebidanan':
                $pengkajian = MidwiferyInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.asesmen-awal-kebidanan', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'pengkajian_awal_neonatus':
                $pengkajian = NeonatusInitialAssessmentDoctor::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.dokter.pengkajian-awal-neonatus', compact('registration', 'pengkajian',  'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'asesmen_awal_dokter':
                $pengkajianNurse = PengkajianNurseRajal::where('registration_id', $registration->id)->first();
                $pengkajian = DoctorInitialAssessment::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.dokter.asesmen-awal-dokter', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter', 'pengkajianNurse'));

            case 'echocardiography':
                $pengkajian = Echocardiography::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.penunjang.echocardiography', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'upload_dokumen':
                $documentCategories = \App\Models\DocumentCategory::orderBy('name')->get();
                return view('pages.simrs.erm.form.penunjang.upload-dokumen', compact('registration', 'documentCategories', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            case 'pemeriksaan_awal_ranap':
                $pengkajian = InpatientInitialExamination::firstOrNew(['registration_id' => $registration->id]);
                return view('pages.simrs.erm.form.perawat.pemeriksaan-awal-ranap', compact('registration', 'pengkajian', 'path', 'registrations', 'menu', 'departements', 'jadwal_dokter'));

            default:
                return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'path'));
        }
    }

    public function get_obat(int $gudang_id)
    {
        $query = WarehouseBarangFarmasi::with(['stored_items']);
        $query->whereHas('stored_items', function ($q) use ($gudang_id) {
            $q->where('gudang_id', $gudang_id);
            $q->where('warehouse_penerimaan_barang_farmasi_item.qty', '>', 0);
        });

        $items = $query->get();
        foreach ($items as $item) {
            $stored = $item->stored_items->where('gudang_id', $gudang_id);
            $item->qty = $stored->sum('qty');
        }

        return response()->json([
            'items' => $items
        ]);
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
