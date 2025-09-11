<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\BatalRegister;
use App\Models\SIMRS\Bed;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GantiDiagnosa;
use App\Models\SIMRS\GantiDokter;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\KategoriRadiologi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Operasi\KategoriOperasi;
use App\Models\SIMRS\Operasi\OrderOperasi;
use App\Models\SIMRS\Operasi\TipeOperasi;
use App\Models\SIMRS\ParameterRadiologi;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use App\Models\SIMRS\Setup\BiayaAdministrasiRawatInap;
use App\Models\SIMRS\Setup\HargaTarifRegistrasi;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TindakanMedis;
use App\Models\SIMRS\TutupKunjungan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class RegistrationController extends Controller
{
    public function getRegistrationData($id)
    {
        try {
            // Cari data registrasi berdasarkan ID
            $registration = Registration::findOrFail($id);
            $tindakan_medis = TindakanMedis::all();

            // Buat response dengan data yang sesuai
            return response()->json([
                'success' => true,
                'message' => 'Data registrasi ditemukan.',
                'data' => [
                    // 'tanggal_tindakan' => $registration->tanggal_tindakan,
                    'dokter_id' => $registration->doctor_id,
                    'departement_id' => $registration->departement_id,
                    'kelas_id' => intval($registration->kelas_rawat_id),
                    'tindakan_medis' => $tindakan_medis
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika ID tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Data registrasi tidak ditemukan.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            // Error umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data registrasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = Registration::query()->with('patient');

        // Apply filters based on form inputs
        $regFilters = ['medical_record_number', 'status', 'departement_id', 'registration_type'];
        $filterApplied = false;

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

        foreach ($regFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        $registrations = $query->orderBy('registration_date', 'asc')->get();

        // Filter by patient's name
        if ($request->filled('name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        // Filter by patient's address
        if ($request->filled('address')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
            $filterApplied = true;
        }

        // Filter by patient's date_of_birth
        if ($request->filled('date_of_birth')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('date_of_birth', 'like', '%' . $request->date_of_birth . '%');
            });
            $filterApplied = true;
        }


        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registrations = $query->orderBy('registration_date', 'asc')->get();
        } else {
            // Return an empty collection if no filters are applied
            $registrations = collect();
        }

        return view('pages.simrs.pendaftaran.daftar-registrasi-pasien', [
            'registrations' => $registrations->where('status', 'aktif'),
            'departements' => Departement::orderBy('name')->get(),
        ]);
    }

    public function create($id, $registrasi)
    {
        $patient = Patient::where('id', $id)->first();

        // Cek jika sudah ada data registrasi aktif untuk pasien dan jenis registrasi ini
        $existingRegistration = Registration::where('patient_id', $id)
            ->where('registration_type', $registrasi)
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingRegistration) {
            // Redirect ke detail registrasi jika sudah ada
            return redirect()->route('detail.registrasi.pasien', $existingRegistration->id)
                ->with('info', 'Pasien sudah terdaftar pada jenis registrasi ini.');
        }

        $kelas_rawats = KelasRawat::all();
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        $doctors = Doctor::with('employee', 'departements')->get();

        $groupPenjaminStandarId = GroupPenjamin::where('name', 'like', '%standar%')->first()->id;
        $kelasRawatRajalId = KelasRawat::where('kelas', 'like', '%rawat jalan%')->first()->id;

        // 1. Ambil hari dan jam sekarang
        $hariIni = ucfirst(Carbon::now()->locale('id')->isoFormat('dddd')); // Contoh: 'Rabu'
        $jamSekarang = Carbon::now()->format('H:i:s'); // Contoh: '14:33:06'

        // 2. Ambil semua dokter yang sedang bertugas saat ini sesuai jadwal dan departemen
        $jadwal_dokter = JadwalDokter::with('doctor.department_from_doctors')
            ->where('hari', $hariIni)
            ->where(function ($query) use ($jamSekarang) {
                $query->where(function ($q) use ($jamSekarang) {
                    // Jadwal normal (contoh 08:00 - 17:00)
                    $q->whereRaw('jam_mulai <= jam_selesai')
                        ->where('jam_mulai', '<=', $jamSekarang)
                        ->where('jam_selesai', '>=', $jamSekarang);
                })->orWhere(function ($q) use ($jamSekarang) {
                    // Jadwal malam (contoh 22:00 - 06:00)
                    $q->whereRaw('jam_mulai > jam_selesai')
                        ->where(function ($sub) use ($jamSekarang) {
                            $sub->where('jam_mulai', '<=', $jamSekarang)
                                ->orWhere('jam_selesai', '>=', $jamSekarang);
                        });
                });
            })
            ->get();

        // 3. Kelompokkan dokter berdasarkan nama departemen (kecuali UGD)
        $groupedDoctors = [];

        foreach ($jadwal_dokter as $jadwal) {
            $doctor = $jadwal->doctor;

            // Pastikan relasi department ada dan bukan 'UGD'
            if ($doctor->department_from_doctors && $doctor->department_from_doctors->name !== 'UGD') {
                $deptName = $doctor->department_from_doctors->name;
                $groupedDoctors[$deptName][] = $doctor;
            }
        }

        $doctorsIGD = Doctor::with('employee', 'department_from_doctors')->whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%UGD%');
        })->get();

        $doctorsLAB = Doctor::with('employee', 'departements')->whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%LABORATORIUM%');
        })->get();

        $penjamins = Penjamin::all();

        // get group penjamin with name "Standar"
        $grupPenjaminStandar = GroupPenjamin::where('name', 'like', '%standar%')->first();

        // get kelas rawat with name "Rawat Jalan"
        $kelasRawatRajal = KelasRawat::where('kelas', 'like', '%rawat jalan%')->first();

        // get Dokter
        $doctors = [];
        $dokters = Doctor::with('department_from_doctors', 'employee')->get();
        foreach ($dokters as $dokter) {
            if ($dokter->department_from_doctors && $dokter->department_from_doctors->name !== 'UGD') {
                $deptName = $dokter->department_from_doctors->name;
                $doctors[$deptName][] = $dokter;
            }
        }

        switch ($registrasi) {
            case 'rawat-jalan':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Jalan",
                    'groupedDoctors' => $groupedDoctors,
                    'doctors' => $doctors,
                    'penjamins' => $penjamins,
                    'case' => 'rawat-jalan',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'igd':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "IGD",
                    'doctors' => $doctorsIGD,
                    'penjamins' => $penjamins,
                    'case' => 'igd',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'odc':
                $doctors = Doctor::with('employee', 'departements')->get();

                // Group doctors by department
                $groupedDoctors = [];
                foreach ($doctors as $doctor) {
                    $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
                }

                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "ODC",
                    'groupedDoctors' => $groupedDoctors,
                    'penjamins' => $penjamins,
                    'case' => 'odc',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'rawat-inap':
                $lastRanapRegistration = Registration::where(['patient_id' => $patient->id, 'registration_type' => 'rawat-inap'])->orderBy('created_at', 'desc')->first();
                $grupPenjaminBPJS = GroupPenjamin::where('name', 'like', '%BPJS%')->first();
                $ranapBPJSdalam1bulan =
                    $lastRanapRegistration && $lastRanapRegistration['penjamin_id'] == $grupPenjaminBPJS->id && // ranap BPJS
                    \Carbon\Carbon::parse($lastRanapRegistration['registration_date'])->diffInDays() <= 30; // kurang dari 30 hari / 1 bulan
                if ($ranapBPJSdalam1bulan) {
                    // reassign the $penjamins variable
                    // filter it to exclude penjamins BPJS
                    $penjamins = Penjamin::where('group_penjamin_id', '!=', $grupPenjaminBPJS->id)->get();
                }
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Inap",
                    'groupedDoctors' => $groupedDoctors,
                    'kelas_rawats' => $kelas_rawats,
                    'kelasTitipan' => $kelas_rawats,
                    'penjamins' => $penjamins,
                    'case' => 'rawat-inap',
                    'patient' => $patient,
                    'age' => $age,
                    'ranapBPJSdalam1bulan' => $ranapBPJSdalam1bulan
                ]);
                break;

            case 'laboratorium':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Laboratorium",
                    'doctors' => Doctor::all(),
                    'laboratorium_categories' => KategoriLaboratorium::all(),
                    'penjamin_standar_id' => $groupPenjaminStandarId,
                    'tarifs' => TarifParameterLaboratorium::where('kelas_rawat_id', $kelasRawatRajal->id)
                        ->where('group_penjamin_id', $grupPenjaminStandar->id)
                        ->get(),
                    'case' => 'laboratorium',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'radiologi':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Radiologi",
                    'doctors' => Doctor::all(),
                    'radiology_categories' => KategoriRadiologi::all(),
                    'penjamin_standar_id' => $groupPenjaminStandarId,
                    'tarifs' => TarifParameterRadiologi::where('kelas_rawat_id', $kelasRawatRajal->id)
                        ->where('group_penjamin_id', $grupPenjaminStandar->id)
                        ->get(),
                    'case' => 'radiologi',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'hemodialisa':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Hemodialisa",
                    'doctors' => Doctor::all(),
                    'penjamins' => $penjamins,
                    'case' => 'hemodialisa',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            default:
                # code...
                break;
        }
    }

    // Tambahkan di bagian import controller Anda:
    // use App\Models\SIMRS\Setup\BiayaAdministrasiRawatInap;

    /**
     * Simpan registrasi baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'user_id' => 'required|integer', // Asumsi ini adalah ID user yang melakukan registrasi
                'employee_id' => 'required|integer', // Asumsi ini adalah ID pegawai yang menangani
                'doctor_id' => 'required|integer',
                'registration_type' => 'required|string|in:rawat-jalan,igd,rawat-inap,odc', // Tambah 'odc' jika ada
                'penjamin_id' => 'required|integer',
                'rujukan' => 'required|string',
                'poliklinik' => 'nullable|string', // ID poliklinik jika untuk rawat jalan/IGD
                'dokter_perujuk' => 'nullable|integer',
                'tipe_rujukan' => 'nullable|string',
                'igd_type' => 'nullable|string',
                'odc_type' => 'nullable|string',
                'nama_perujuk' => 'nullable|string',
                'telp_perujuk' => 'nullable|string',
                'alamat_perujuk' => 'nullable|string',
                'diagnosa_awal' => 'nullable|string',
                'kelas_rawat_id' => 'nullable|integer', // Seharusnya integer, bukan string
                'bed_id' => 'nullable|integer', // Tambahkan validasi untuk bed_id
            ], [
                'patient_id.required' => 'Kolom Pasien tidak boleh kosong.',
                'user_id.required' => 'Kolom User tidak boleh kosong.',
                'employee_id.required' => 'Kolom Pegawai tidak boleh kosong.',
                'doctor_id.required' => 'Kolom Dokter tidak boleh kosong.',
                'registration_type.required' => 'Kolom Tipe Registrasi tidak boleh kosong.',
                'penjamin_id.required' => 'Kolom Penjamin tidak boleh kosong.',
                'rujukan.required' => 'Kolom rujukan wajib diisi.',
                // ... (pesan validasi lainnya) ...
            ]);

            Log::info('Registration validated data:', $validatedData);

            // CEK JIKA SUDAH ADA REGISTRASI AKTIF UNTUK PASIEN INI
            $existingRegistration = Registration::where('patient_id', $validatedData['patient_id'])
                ->where('status', 'aktif')
                ->first();

            if ($existingRegistration) {
                Log::warning('Registrasi aktif sudah ada untuk pasien ID: ' . $validatedData['patient_id']);
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien ini masih memiliki registrasi aktif. Tidak dapat melakukan registrasi ganda.',
                    'registration_id' => $existingRegistration->id,
                    'redirect_url' => url('/daftar-registrasi-pasien/' . $existingRegistration->id)
                ], 409);
            }

            // Set registration date and status
            $validatedData['registration_date'] = Carbon::now();
            $validatedData['date'] = Carbon::now(); // Mungkin ini bisa digabung dengan registration_date
            $validatedData['status'] = 'aktif';

            Log::info('Registration type: ' . $request->registration_type);

            // Penentuan kelas rawat jika belum diset dari form
            if (!isset($validatedData['kelas_rawat_id']) || is_null($validatedData['kelas_rawat_id'])) {
                if ($request->registration_type == 'rawat-jalan' || $request->registration_type == 'igd' || $request->registration_type == 'odc') {
                    $kelas_rawat = KelasRawat::where('kelas', 'like', '%Rawat Jalan%')->first();
                    if ($kelas_rawat) {
                        $validatedData['kelas_rawat_id'] = $kelas_rawat->id;
                    } else {
                        Log::error('Kelas rawat untuk Rawat Jalan/IGD/ODC tidak ditemukan!');
                        return response()->json(['message' => 'Kelas rawat dasar tidak ditemukan!'], 500);
                    }
                } else if ($request->registration_type == 'rawat-inap') {
                    // Untuk rawat inap, kelas_rawat_id biasanya dipilih dari bed atau explicit di form
                    // Jika tidak ada di form, ini bisa jadi error atau perlu default lain
                    Log::warning('Kelas rawat_id tidak disediakan untuk Rawat Inap, menggunakan default.');
                    $kelas_rawat = KelasRawat::where('kelas', 'like', '%Rawat Inap%')->first(); // Asumsi ada kelas default
                    if ($kelas_rawat) {
                        $validatedData['kelas_rawat_id'] = $kelas_rawat->id;
                    } else {
                        Log::error('Kelas rawat default untuk Rawat Inap tidak ditemukan!');
                        return response()->json(['message' => 'Kelas rawat default untuk Rawat Inap tidak ditemukan!'], 500);
                    }
                }
            }


            // Set department based on registration type
            // Anda perlu mengimplementasikan method ini sesuai logika departemen Anda
            $validatedData['departement_id'] = $this->getDepartmentId($validatedData);
            Log::info('Department ID assigned: ' . $validatedData['departement_id']);

            // Update bed if rawat inap
            if ($validatedData['registration_type'] == 'rawat-inap') {
                Log::info('Processing rawat-inap bed assignment');
                Log::info('Bed ID from request: ' . $request->bed_id);

                if (!$request->bed_id) {
                    Log::error('Bed ID is required for rawat-inap but not provided!');
                    return response()->json(['message' => 'Bed ID wajib diisi untuk rawat inap!'], 400);
                }

                $bed = Bed::find($request->bed_id);
                if (!$bed) {
                    Log::error('Bed not found with ID: ' . $request->bed_id);
                    return response()->json(['message' => 'Bed dengan ID tersebut tidak ditemukan!'], 404);
                }
                // Pastikan bed tersedia
                // Pengecekan ini penting untuk mencegah double booking
                if ($bed->patient_id !== null) { // Atau cek status 'terisi' di tabel `beds`
                    Log::error('Bed ' . $bed->id . ' sudah terisi.');
                    return response()->json(['message' => 'Bed yang dipilih sudah terisi atau tidak tersedia!'], 400);
                }

                // ==========================================================
                // START: MODIFIKASI DAN PENAMBAHAN KODE
                // ==========================================================

                // 1. Update tabel `beds` untuk menandai pasien saat ini (menjaga fungsionalitas yang ada)
                // Ini berguna untuk query cepat "siapa yang ada di bed ini sekarang?"
                $bed->update(['patient_id' => $validatedData['patient_id']]);
                Log::info('Bed ' . $bed->id . ' patient_id column updated to ' . $validatedData['patient_id']);

                // 2. Insert ke tabel pivot `bed_patient` untuk mencatat riwayat penggunaan bed
                // Method attach() adalah cara Eloquent untuk menyisipkan data ke tabel pivot.
                try {
                    $patientId = $validatedData['patient_id'];
                    $pivotData = [
                        'status' => 'terisi', // Status saat pasien masuk. Bukan 'kosong'.
                        'tanggal_masuk' => Carbon::now(),
                        'created_at' => Carbon::now(), // Mengisi timestamp secara eksplisit
                        'updated_at' => Carbon::now()
                    ];

                    $bed->patients()->attach($patientId, $pivotData);

                    Log::info('Patient ' . $patientId . ' attached to Bed ' . $bed->id . ' in bed_patient pivot table with status: aktif.');
                } // Versi BARU (untuk debugging)
                catch (\Exception $e) {
                    // Batalkan update pada tabel beds
                    $bed->update(['patient_id' => null]);

                    // ================== LOGGING LEBIH DETAIL ==================
                    Log::error('================================================================');
                    Log::error('GAGAL MENAMBAHKAN RIWAYAT BED PASIEN');
                    Log::error('Pesan Error: ' . $e->getMessage()); // Ini akan memberi tahu apa masalahnya
                    Log::error('File: ' . $e->getFile() . ' on line ' . $e->getLine()); // Tahu lokasi error
                    Log::error('Stack Trace: ' . $e->getTraceAsString()); // Detail lengkap error
                    Log::error('================================================================');

                    // Kembalikan pesan error yang sebenarnya ke frontend untuk debugging
                    return response()->json([
                        'message' => 'Terjadi kesalahan internal. Silakan periksa log.',
                        'error' => $e->getMessage() // Kirim pesan asli ke frontend
                    ], 500);
                }

                // ==========================================================
                // END: MODIFIKASI DAN PENAMBAHAN KODE
                // ==========================================================

                Log::info('Bed assigned successfully to patient ' . $validatedData['patient_id']);
            }

            // Generate registration numbers (Pastikan fungsi ini ada di helper Anda)
            $validatedData['registration_number'] = generate_registration_number();
            $validatedData['no_urut'] = generateDoctorSequenceNumber($request->doctor_id, $request->registration_date);

            Log::info('Generated registration number: ' . $validatedData['registration_number']);
            Log::info('Generated sequence number: ' . $validatedData['no_urut']);

            // Create registration
            $registration = Registration::create($validatedData);
            Log::info('Registration created with ID: ' . $registration->id);

            // Get group penjamin ID
            $penjamin = Penjamin::with('group_penjamin')->find($request->penjamin_id);
            if (!$penjamin) {
                Log::error('Penjamin not found with ID: ' . $request->penjamin_id);
                return response()->json(['message' => 'Penjamin tidak ditemukan!'], 404);
            }

            if (!$penjamin->group_penjamin) {
                Log::error('Group penjamin not found for penjamin ID: ' . $request->penjamin_id);
                return response()->json(['message' => 'Group penjamin tidak ditemukan untuk penjamin ini!'], 404);
            }
            $gruop_penjamin_id = $penjamin->group_penjamin->id;
            Log::info('Group penjamin ID: ' . $gruop_penjamin_id);

            // Handle billing based on registration type
            if ($validatedData['registration_type'] == 'rawat-jalan') {
                Log::info('Processing rawat-jalan billing');

                $hargaTarifAdmin = HargaTarifRegistrasi::where('group_penjamin_id', $gruop_penjamin_id)
                    ->where('tarif_registrasi_id', 1) // Asumsi tarif_registrasi_id 1 untuk Rawat Jalan
                    ->first();

                if (!$hargaTarifAdmin) {
                    Log::error('Tarif administrasi rawat jalan tidak ditemukan untuk group_penjamin_id: ' . $gruop_penjamin_id);
                    return response()->json(['message' => 'Tarif administrasi rawat jalan tidak ditemukan!'], 404);
                }

                Log::info('Tarif admin rawat jalan: ' . $hargaTarifAdmin->harga);

                $billing = Bilingan::create([
                    'registration_id' => $registration->id,
                    'patient_id' => $request->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => $hargaTarifAdmin->harga
                ]);

                $tagihanPasien = TagihanPasien::create([
                    'user_id' => Auth::id(), // Menggunakan user yang sedang login
                    'bilingan_id' => $billing->id,
                    'registration_id' => $registration->id,
                    'date' => Carbon::now(),
                    'tagihan' => "[Biaya Administrasi] Rawat Jalan",
                    'nominal' => $hargaTarifAdmin->harga,
                    'quantity' => 1,
                    'harga' => $hargaTarifAdmin->harga,
                    'wajib_bayar' => $hargaTarifAdmin->harga
                ]);

                BilinganTagihanPasien::create([
                    'tagihan_pasien_id' => $tagihanPasien->id,
                    'bilingan_id' => $billing->id,
                ]);
            } else if ($validatedData['registration_type'] == 'igd') {
                Log::info('Processing IGD billing');

                $hargaTarifAdmin = HargaTarifRegistrasi::where('group_penjamin_id', $gruop_penjamin_id)
                    ->where('tarif_registrasi_id', 3) // Asumsi tarif_registrasi_id 3 untuk IGD
                    ->first();

                if (!$hargaTarifAdmin) {
                    Log::error('Tarif administrasi IGD tidak ditemukan untuk group_penjamin_id: ' . $gruop_penjamin_id);
                    return response()->json(['message' => 'Tarif administrasi IGD tidak ditemukan!'], 404);
                }

                Log::info('Tarif admin IGD: ' . $hargaTarifAdmin->harga);

                $billing = Bilingan::create([
                    'registration_id' => $registration->id,
                    'patient_id' => $request->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => $hargaTarifAdmin->harga
                ]);

                $tagihanPasien = TagihanPasien::create([
                    'user_id' => Auth::id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $registration->id,
                    'date' => Carbon::now(),
                    'tagihan' => "[Biaya Administrasi] UGD",
                    'nominal' => $hargaTarifAdmin->harga,
                    'quantity' => 1,
                    'harga' => $hargaTarifAdmin->harga,
                    'wajib_bayar' => $hargaTarifAdmin->harga
                ]);

                BilinganTagihanPasien::create([
                    'tagihan_pasien_id' => $tagihanPasien->id,
                    'bilingan_id' => $billing->id,
                ]);
            } else if ($validatedData['registration_type'] == 'rawat-inap') {
                Log::info('Processing rawat-inap billing');

                $biayaAdminRawatInap = BiayaAdministrasiRawatInap::where('group_penjamin_id', $gruop_penjamin_id)
                    ->first();

                if (!$biayaAdminRawatInap) {
                    Log::error('Biaya administrasi rawat inap tidak ditemukan untuk group_penjamin_id: ' . $gruop_penjamin_id);
                    return response()->json(['message' => 'Biaya administrasi rawat inap tidak ditemukan untuk penjamin ini!'], 404);
                }

                $tarifAdmin = $biayaAdminRawatInap->min_tarif ?: 0;

                Log::info('Tarif admin rawat inap yang akan digunakan: ' . $tarifAdmin);

                $billing = Bilingan::create([
                    'registration_id' => $registration->id,
                    'patient_id' => $request->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => $tarifAdmin
                ]);

                if ($tarifAdmin > 0) {
                    $tagihanPasien = TagihanPasien::create([
                        'user_id' => Auth::id(),
                        'bilingan_id' => $billing->id,
                        'registration_id' => $registration->id,
                        'date' => Carbon::now(),
                        'tagihan' => "[Biaya Administrasi] Rawat Inap",
                        'nominal' => $tarifAdmin,
                        'quantity' => 1,
                        'harga' => $tarifAdmin,
                        'wajib_bayar' => $tarifAdmin
                    ]);

                    BilinganTagihanPasien::create([
                        'tagihan_pasien_id' => $tagihanPasien->id,
                        'bilingan_id' => $billing->id,
                    ]);
                }
                Log::info('Rawat inap billing created successfully');
            }
            // Tambahkan logika billing untuk 'odc' jika diperlukan

            Log::info('Registration process completed successfully');

            // Mengembalikan JSON response untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil ditambahkan!',
                'redirect_url' => url('/daftar-registrasi-pasien/' . $registration->id)
            ], 200);
        } catch (ValidationException $e) {
            // Laravel secara otomatis mengembalikan JSON dengan status 422 untuk AJAX request
            // Jadi block ini mungkin tidak selalu terpanggil, tapi baik untuk logging/debugging.
            Log::error('Registration validation failed:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Mohon periksa kembali input Anda.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }


    private function getDepartmentId($data)
    {
        switch ($data['registration_type']) {
            case 'rawat-jalan':
            case 'igd':
                return Doctor::where('id', $data['doctor_id'])->first()->department_from_doctors->id;
            case 'odc':
                return Departement::where('kode', 'ODC')->first()->id;
            case 'rawat-inap':
                return Departement::where('kode', 'RAWAT INAP')->first()->id;
            default:
                return null;
        }
    }

    // app/Http/Controllers/SIMRS/RegistrationController.php

    public function show($id)
    {
        // =================================================================
        // KODE YANG SUDAH ADA (TIDAK DIUBAH)
        // =================================================================
        $registration = Registration::findOrFail($id);
        $cppt = CPPT::where('registration_id', $id)->get();
        $jaminan = $registration->penjamin->name;
        if ($jaminan === 'Umum') {
            $penjamin = 'Jaminan Pribadi';
        } elseif ($jaminan === 'BPJS') {
            $penjamin = "BPJS Kesehatan";
        } else {
            $penjamin = $registration->penjamin->name;
        }

        $tipeRegis = $registration->registration_type;
        if ($tipeRegis === 'rawat-jalan' || $tipeRegis === 'igd' || $tipeRegis === 'odc') {
            $kelasRawat = 'RAWAT JALAN';
        } else {
            $kelasRawat = 'RAWAT INAP';
        }

        $doctors = Doctor::with('employee', 'departements')->get();
        $departements = Departement::with('grup_tindakan_medis.tindakan_medis')->get();
        $tindakan_medis = TindakanMedis::all();

        // Group doctors by department
        $groupedDoctors = [];
        foreach ($doctors as $doctor) {
            $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
        }

        $laboratoriumDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%laboratorium%');
        })->get();

        $laboratoriumOrders = [];

        OrderLaboratorium::where('registration_id', $registration->id)
            ->get()
            ->each(function ($order) use (&$laboratoriumOrders) {
                // dd($order->registration);
                $laboratoriumOrders[$order->id] = $order;
            });

        $radiologyDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%radiologi%');
        })->get();

        $radiologiOrders = [];

        OrderRadiologi::where('registration_id', $registration->id)
            ->get()
            ->each(function ($order) use (&$radiologiOrders) {
                $radiologiOrders[$order->id] = $order;
            });

        $patient = Patient::with(['registration' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($registration->patient->id);
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        $groupPenjaminId = GroupPenjamin::where('id', $registration->penjamin->group_penjamin_id)->first()->id;

        $jenisOperasi = TipeOperasi::orderBy('tipe')->get();
        $kategoriOperasi = KategoriOperasi::orderBy('nama_kategori')->get();
        $ruangans = Room::orderBy('ruangan')->get();
        $ruangans_operasi = Room::where('ruangan', 'OK')->orderBy('ruangan', 'asc')->get();

        $dTindakan = \App\Models\SIMRS\Departement::with('grup_tindakan_medis.tindakan_medis')->get();

        $orderOperasi = OrderOperasi::with([
            'tipeOperasi',
            'kategoriOperasi',
            'jenisOperasi',
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
                    'dokterTambahan1.employee',
                    'dokterTambahan2.employee',
                    'dokterTambahan3.employee',
                    'dokterTambahan4.employee',
                    'dokterTambahan5.employee',
                    'createdByUser'
                ]);
            }
        ])->where('registration_id', $id)->get();

        return view('pages.simrs.pendaftaran.detail-registrasi-pasien', [
            // Variabel yang sudah ada
            'dTindakan' => $dTindakan,
            'orderOperasi' => $orderOperasi,
            'kelasRawat' => $kelasRawat,
            'penjamin' => $penjamin,
            'groupedDoctors' => $groupedDoctors,
            'radiologyDoctors' => $radiologyDoctors,
            'radiologiOrders' => $radiologiOrders,
            'laboratoriumDoctors' => $laboratoriumDoctors,
            'laboratoriumOrders' => $laboratoriumOrders,
            'groupPenjaminId' => $groupPenjaminId,
            'laboratorium_categories' => KategoriLaboratorium::all(),
            'laboratorium_tarifs' => TarifParameterLaboratorium::all(),
            'radiology_categories' => KategoriRadiologi::all(),
            'radiology_tarifs' => TarifParameterRadiologi::all(),
            'kelas_rawats' => KelasRawat::all(), // Sudah ada, ini akan digunakan oleh modal
            'registration' => $registration,
            'patient' => $patient,
            'departements' => $departements,
            'tindakan_medis' => $tindakan_medis,
            'age' => $age,
            'doctors' => $doctors, // <-- PASTIKAN INI DIKIRIM: dibutuhkan untuk dropdown DPJP
            // 'ruanganx`s' => $ruangans,
            'ruangans_operasi' => $ruangans_operasi,
            // === TAMBAHAN BARU YANG DIKIRIM KE VIEW ===
            'jenisOperasi' => $jenisOperasi,
            'kategoriOperasi' => $kategoriOperasi,
        ]);
    }

    public function getDataBed(Request $request)
    {
        // Check if the request contains any search parameters
        if (!$request->kelas_rawat_id && !$request->has('search.value')) {
            // Return an empty response if no search parameter is provided
            return DataTables::of(collect([]))->make(true);
        }

        $query = Bed::with(['room', 'patient'])
            ->when($request->kelas_rawat_id, function ($q) use ($request) {
                return $q->whereHas('room', function ($q) use ($request) {
                    $q->where('kelas_rawat_id', $request->kelas_rawat_id);
                });
            });

        return DataTables::of($query)
            ->addColumn('ruangan', function ($bed) {
                return $bed->room ? $bed->room->ruangan . ' - ' . $bed->room->no_ruang : '-';
            })
            ->addColumn('pasien', function ($bed) {
                return $bed->patient ? $bed->patient->name : 'Kosong';
            })
            ->addColumn('fungsi', function ($bed) {
                return $bed->patient ? '<span class="text-danger">(Terisi)</span>' : '<button type="button" class="btn btn-sm btn-info pilih-bed" data-kelas-id="' . $bed->room->kelas_rawat->id . '" data-bed-id="' . $bed->id . '" data-room-info="' . $bed->room->ruangan . ' - ' . $bed->room->no_ruang . ' (' . $bed->nama_tt . ')">Pilih</button>';
            })
            ->rawColumns(['fungsi'])
            ->filterColumn('ruangan', function ($query, $keyword) {
                $query->whereHas('room', function ($q) use ($keyword) {
                    $q->where('ruangan', 'like', "%$keyword%")
                        ->orWhere('no_ruang', 'like', "%$keyword%");
                });
            })
            ->make(true);
    }

    public function batal_register(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'alasan' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                return back()->with('error', 'User tidak ditemukan!');
            }

            // Find the registration record
            $registration = Registration::findOrFail($id);

            // Cek apakah ada bilingan dan tagihan pasien
            if ($registration->bilingan) {
                $bilingan = $registration->bilingan;

                // Hapus semua tagihan pasien yang terkait dengan bilingan
                if ($bilingan->tagihan_pasien) {
                    foreach ($bilingan->tagihan_pasien as $tagihan) {
                        $tagihan->delete();
                    }
                }

                // Hapus data biling jika semua tagihan telah terhapus
                $bilingan->delete();
            }

            // Kosongkan Bed jika rawat inap
            if ($registration['registration_type'] == 'rawat-inap') {
                $this->removePatientFromBed($registration->patient->bed->id, $registration->patient->id);
            }

            // Buat entri baru untuk pembatalan pendaftaran
            BatalRegister::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'tgl_batal' => $request->tgl_batal,
                'alasan' => $request->alasan,
            ]);

            // Update status registrasi menjadi "batal"
            $registration->update([
                'status' => 'batal',
                'registration_close_date' => Carbon::now()
            ]);

            return redirect()->route('detail.pendaftaran.pasien', $registration->patient->id)
                ->with('success', 'Registration has been cancelled successfully.');
        } else {
            return back()->with('error', 'Email atau Password salah!');
        }
    }

    public function batal_keluar(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'alasan' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                return back()->with('error', 'User tidak ditemukan!');
            }
            // Find the registration record
            $registration = Registration::findOrFail($id);

            // Create a new BatalRegister entry
            BatalRegister::create([
                'registration_id' => $registration->id,
                'user_id' => $request->user_id,
                'tgl_batal' => $request->tgl_batal,
                'alasan' => $request->alasan,
            ]);


            // Update the status of the registration
            $registration->update([
                'status' => 'aktif',
                'registration_close_date' => null
            ]);

            return redirect()->route('detail.registrasi.pasien', $registration->id)->with('success', 'Registration has been cancelled successfully.');
        } else {
            return back()->with('error', 'Email atau Password salah!');
        }
    }

    public function tutup_kunjungan(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'alasan_keluar' => 'required',
            'lp_manual' => 'nullable',
            'proses_keluar' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                return back()->with('error', 'User tidak ditemukan!');
            }
            // Find the registration record
            $registration = Registration::findOrFail($id);

            if ($registration['registration_type'] == 'rawat-inap') {
                $this->removePatientFromBed($registration->patient->bed->id, $registration->patient->id);
            }

            // Create a new BatalRegister entry
            TutupKunjungan::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'alasan_keluar' => $request->alasan_keluar,
                'lp_manual' => $request->lp_manual,
                'proses_keluar' => $request->proses_keluar,
            ]);

            // Update the status of the registration
            $registration->update([
                'status' => 'tutup_kunjungan',
                'registration_close_date' => Carbon::now()
            ]);

            return redirect()->route('detail.pendaftaran.pasien', $registration->patient->id)->with('success', 'Registration has been closed successfully.');
        } else {
            return back()->with('error', 'Email atau Password salah!');
        }
    }

    public function ganti_dpjp(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'alasan' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                return back()->with('error', 'User tidak ditemukan!');
            }
            // Find the registration record
            $registration = Registration::findOrFail($id);

            // Create a new BatalRegister entry
            GantiDokter::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'doctor_id' => $request->doctor_id,
                'tgl_ubah' => $request->tgl_ubah,
                'alasan' => $request->alasan,
            ]);

            // Generate the sequence number for the doctor
            $doctorSequenceNumber = generateDoctorSequenceNumber($request->doctor_id, $request->registration_date);

            // Update the status of the registration
            $registration->update([
                'doctor_id' => $request->doctor_id,
                'no_urut' => $doctorSequenceNumber
            ]);

            return redirect()->route('detail.registrasi.pasien', $registration->id)->with('success', 'Registration has been closed successfully.');
        } else {
            return back()->with('error', 'Email atau Password salah!');
        }
    }

    public function ganti_diagnosa(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'diagnosa_awal' => 'required',
            'alasan' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                return back()->with('error', 'User tidak ditemukan!');
            }

            // Find the registration record
            $registration = Registration::findOrFail($id);

            // Create a new BatalRegister entry
            GantiDiagnosa::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'diagnosa_awal' => $request->diagnosa_awal,
                'alasan' => $request->alasan,
            ]);

            // Update the status of the registration
            $registration->update(['diagnosa_awal' => $request->diagnosa_awal]);

            return redirect()->route('detail.registrasi.pasien', $registration->id)->with('success', 'Registration has been closed successfully.');
        } else {
            return back()->with('error', 'Email atau Password salah!');
        }
    }

    private function zimbraLogin($email, $password)
    {
        $data = [
            "Header" => [
                "context" => [
                    "_jsns" => "urn:zimbra",
                    "userAgent" => ["name" => "curl", "version" => "8.8.15"],
                ],
            ],
            "Body" => [
                "AuthRequest" => [
                    "_jsns" => "urn:zimbraAccount",
                    "account" => ["_content" => $email, "by" => "name"],
                    "password" => $password,
                ],
            ],
        ];

        try {
            $encodedData = json_encode($data);

            $url = 'https://webmail.livasya.com/service/soap';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
            ));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $mark = 'AUTH_FAILED';

            if (strpos($result, $mark) !== false) {
                return false; // Autentikasi gagal
            } else {
                // Autentikasi berhasil
                // Anda mungkin ingin melakukan sesuatu di sini, seperti memproses respons
                // atau mengembalikan informasi tambahan
                return true;
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat menjalankan permintaan cURL
            return false;
        }
    }

    public function assignBedToPatient(Request $request)
    {
        $bed = Bed::findOrFail($request->bed_id);
        $patient = Patient::findOrFail($request->patient_id);

        // Tambahkan pasien ke bed dengan status 'terisi' dan tanggal masuk
        $bed->patients()->attach($patient->id, [
            'status' => 'terisi',
            'tanggal_masuk' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Pasien berhasil ditambahkan ke bed.']);
    }

    public function removePatientFromBed($bed_id, $patient_id)
    {
        $bed = Bed::findOrFail($bed_id);
        $bed->patient_id = null;
        $bed->save();

        $patient = Patient::findOrFail($patient_id);

        // Hapus hubungan pasien dari bed
        $bed->patients()->updateExistingPivot($patient->id, [
            'status' => 'kosong',
            'tanggal_keluar' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Pasien berhasil dihapus dari bed.']);
    }
}
