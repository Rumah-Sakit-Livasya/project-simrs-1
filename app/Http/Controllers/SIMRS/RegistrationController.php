<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
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
use App\Models\SIMRS\GantiPenjamin;
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
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
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
        $query = Registration::query()
            ->with('patient');

        $regFilters = ['medical_record_number', 'status', 'departement_id', 'registration_type'];
        $filterApplied = false;

        // Default: tampilkan registrasi hari ini jika tidak ada filter
        if (!$request->filled('registration_date') && !$request->filled('name') && !$request->filled('address') && !$request->filled('date_of_birth')) {
            $today = Carbon::today()->format('Y-m-d');
            $query->whereDate('registration_date', $today);
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

        foreach ($regFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

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

        $registrations = $query->orderBy('registration_date', 'asc')->get();

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

        $kelas_rawats = KelasRawat::orderBy('urutan')->get();
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
            // if ($doctor->department_from_doctors && $doctor->department_from_doctors->name !== 'UGD') {
            //     $deptName = $doctor->department_from_doctors->name;
            //     $groupedDoctors[$deptName][] = $doctor;
            // }
        }

        $doctorsIGD = Doctor::with('employee', 'department_from_doctors')->whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%UGD%');
        })->get();

        $doctorsLAB = Doctor::with('employee', 'departements')->whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%lab%');
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

            case 'rawat-inap':
                $lastRanapRegistration = Registration::where([
                    'patient_id' => $patient->id,
                    'registration_type' => 'rawat-inap'
                ])->orderBy('created_at', 'desc')->first();

                $grupPenjaminBPJS = GroupPenjamin::where('name', 'like', '%BPJS%')->first();
                $ranapBPJSdalam1bulan =
                    $lastRanapRegistration
                    && $lastRanapRegistration['penjamin_id'] == $grupPenjaminBPJS->id
                    && \Carbon\Carbon::parse($lastRanapRegistration['registration_date'])->diffInDays() <= 30;

                if ($ranapBPJSdalam1bulan) {
                    $penjamins = Penjamin::where('group_penjamin_id', '!=', $grupPenjaminBPJS->id)->get();
                }

                // Hilangkan kelas rawat dengan nama "Rawat Jalan"
                $kelasRawatFiltered = $kelas_rawats->filter(function ($kelas) {
                    return stripos($kelas->kelas, 'rawat jalan') === false;
                })->values();

                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Inap",
                    'groupedDoctors' => $groupedDoctors,
                    'doctors' => $doctors,
                    'kelas_rawats' => $kelasRawatFiltered,
                    'kelasTitipan' => $kelasRawatFiltered,
                    'penjamins' => $penjamins,
                    'case' => 'rawat-inap',
                    'patient' => $patient,
                    'age' => $age,
                    'ranapBPJSdalam1bulan' => $ranapBPJSdalam1bulan,
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
                'user_id' => 'required|integer',
                'employee_id' => 'required|integer',
                'doctor_id' => 'required|integer',
                'registration_type' => 'required|string|in:rawat-jalan,igd,rawat-inap,odc',
                'penjamin_id' => 'required|integer',
                'rujukan' => 'required|string',
                'poliklinik' => 'nullable|string',
                'dokter_perujuk' => 'nullable|integer',
                'tipe_rujukan' => 'nullable|string',
                'igd_type' => 'nullable|string',
                'odc_type' => 'nullable|string',
                'nama_perujuk' => 'nullable|string',
                'telp_perujuk' => 'nullable|string',
                'alamat_perujuk' => 'nullable|string',
                'diagnosa_awal' => 'nullable|string',
                'kelas_rawat_id' => 'nullable|integer',
                'bed_id' => 'nullable|integer',
                'nextregis' => 'sometimes|boolean',
            ], [
                'patient_id.required' => 'Kolom Pasien tidak boleh kosong.',
                'patient_id.integer' => 'Kolom Pasien harus berupa angka.',
                'user_id.required' => 'Kolom User tidak boleh kosong.',
                'user_id.integer' => 'Kolom User harus berupa angka.',
                'employee_id.required' => 'Kolom Pegawai tidak boleh kosong.',
                'employee_id.integer' => 'Kolom Pegawai harus berupa angka.',
                'doctor_id.required' => 'Kolom Dokter tidak boleh kosong.',
                'doctor_id.integer' => 'Kolom Dokter harus berupa angka.',
                'registration_type.required' => 'Kolom Tipe Registrasi tidak boleh kosong.',
                'registration_type.string' => 'Kolom Tipe Registrasi harus berupa teks.',
                'registration_type.in' => 'Tipe Registrasi tidak valid.',
                'penjamin_id.required' => 'Kolom Penjamin tidak boleh kosong.',
                'penjamin_id.integer' => 'Kolom Penjamin harus berupa angka.',
                'rujukan.required' => 'Kolom Rujukan wajib diisi.',
                'rujukan.string' => 'Kolom Rujukan harus berupa teks.',
                'poliklinik.string' => 'Kolom Poliklinik harus berupa teks.',
                'dokter_perujuk.integer' => 'Kolom Dokter Perujuk harus berupa angka.',
                'tipe_rujukan.string' => 'Kolom Tipe Rujukan harus berupa teks.',
                'igd_type.string' => 'Kolom Tipe IGD harus berupa teks.',
                'odc_type.string' => 'Kolom Tipe ODC harus berupa teks.',
                'nama_perujuk.string' => 'Kolom Nama Perujuk harus berupa teks.',
                'telp_perujuk.string' => 'Kolom Telepon Perujuk harus berupa teks.',
                'alamat_perujuk.string' => 'Kolom Alamat Perujuk harus berupa teks.',
                'diagnosa_awal.string' => 'Kolom Diagnosa Awal harus berupa teks.',
                'kelas_rawat_id.integer' => 'Kolom Kelas Rawat harus berupa angka.',
                'bed_id.integer' => 'Kolom Tempat Tidur harus berupa angka.',
                'nextregis.boolean' => 'Kolom Nextregis harus berupa boolean.',
            ]);

            Log::info('Registration validated data:', $validatedData);

            // CEK JIKA SUDAH ADA REGISTRASI AKTIF UNTUK PASIEN INI
            $allowDoubleRegistration = false;

            if ($request->has('nextRegis') && $request->nextRegis) {
                $allowDoubleRegistration = true;
                Log::info('Flag nextregis terdeteksi, memperbolehkan registrasi ganda.');
            }

            $existingRegistration = Registration::where('patient_id', $validatedData['patient_id'])
                ->where('status', 'aktif')
                ->first();

            // Jika tidak ada flag nextregis atau nextregis==false, tetap blok double regis
            if ($existingRegistration && !$allowDoubleRegistration) {
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
            $validatedData['date'] = Carbon::now();
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
                    Log::warning('Kelas rawat_id tidak disediakan untuk Rawat Inap, menggunakan default.');
                    $kelas_rawat = KelasRawat::where('kelas', 'like', '%Rawat Inap%')->first();
                    if ($kelas_rawat) {
                        $validatedData['kelas_rawat_id'] = $kelas_rawat->id;
                    } else {
                        Log::error('Kelas rawat default untuk Rawat Inap tidak ditemukan!');
                        return response()->json(['message' => 'Kelas rawat default untuk Rawat Inap tidak ditemukan!'], 500);
                    }
                }
            }

            // Set department based on registration type
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
                if ($bed->patient_id !== null) { // Atau cek status 'terisi' di tabel beds
                    Log::error('Bed ' . $bed->id . ' sudah terisi.');
                    return response()->json(['message' => 'Bed yang dipilih sudah terisi atau tidak tersedia!'], 400);
                }

                // Update bed dan log riwayat pivot
                $bed->update(['patient_id' => $validatedData['patient_id']]);
                Log::info('Bed ' . $bed->id . ' patient_id column updated to ' . $validatedData['patient_id']);

                try {
                    $patientId = $validatedData['patient_id'];
                    $pivotData = [
                        'status' => 'terisi',
                        'tanggal_masuk' => Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    $bed->patients()->attach($patientId, $pivotData);

                    Log::info('Patient ' . $patientId . ' attached to Bed ' . $bed->id . ' in bed_patient pivot table with status: aktif.');
                } catch (\Exception $e) {
                    // Batalkan update pada tabel beds
                    $bed->update(['patient_id' => null]);

                    Log::error('================================================================');
                    Log::error('GAGAL MENAMBAHKAN RIWAYAT BED PASIEN');
                    Log::error('Pesan Error: ' . $e->getMessage());
                    Log::error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
                    Log::error('Stack Trace: ' . $e->getTraceAsString());
                    Log::error('================================================================');

                    return response()->json([
                        'message' => 'Terjadi kesalahan internal. Silakan periksa log.',
                        'error' => $e->getMessage()
                    ], 500);
                }

                Log::info('Bed assigned successfully to patient ' . $validatedData['patient_id']);
            }

            // Generate registration numbers
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
                    ->where('tarif_registrasi_id', 1)
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
                    'user_id' => Auth::id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $registration->id,
                    'date' => Carbon::now(),
                    'tagihan' => "[Biaya Administrasi] Rawat Jalan",
                    'nominal_awal' => $hargaTarifAdmin->harga,
                    'quantity' => 1,
                    'nominal' => $hargaTarifAdmin->harga,
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
                    ->where('tarif_registrasi_id', 3)
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
                    'nominal_awal' => $hargaTarifAdmin->harga,
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
                        'nominal_awal' => $tarifAdmin,
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

    public function show($id)
    {
        // =================================================================
        // KODE YANG SUDAH ADA (TIDAK DIUBAH)
        // =================================================================
        $registration = Registration::with([
            'penjamin',
            'patient'
        ])->findOrFail($id);

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
        $kelasRawat = in_array($tipeRegis, ['rawat-jalan', 'igd', 'odc']) ? 'RAWAT JALAN' : 'RAWAT INAP';

        // Limit doctors and departements to only those needed for this registration
        $doctors = Doctor::with(['employee', 'departements', 'department_from_doctors'])
            ->whereHas('department_from_doctors')
            ->get();

        $departements = Departement::with(['grup_tindakan_medis' => function ($q) {
            $q->with('tindakan_medis');
        }])->get();

        // Only load tindakan_medis that are used in this registration's departements
        // FIX: Kolom 'departement_id' tidak ada, gunakan relasi dari departements
        $tindakan_medis = collect();
        foreach ($departements as $departement) {
            if ($departement->grup_tindakan_medis) {
                foreach ($departement->grup_tindakan_medis as $grup) {
                    if ($grup->tindakan_medis) {
                        foreach ($grup->tindakan_medis as $tindakan) {
                            $tindakan_medis->push($tindakan);
                        }
                    }
                }
            }
        }
        $tindakan_medis = $tindakan_medis->unique('id')->values();

        // Group doctors by department
        $groupedDoctors = [];
        foreach ($doctors as $doctor) {
            if ($doctor->department_from_doctors) {
                $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
            }
        }

        // Only load doctors for laboratorium department
        $laboratoriumDoctors = Doctor::with('employee')
            ->whereHas('department_from_doctors', function ($query) {
                $query->where('name', 'like', '%lab%');
            })
            ->get();

        // Only load laboratorium orders for this registration, eager load only necessary relations
        $laboratoriumOrders = OrderLaboratorium::where('registration_id', $registration->id)
            ->with([
                'doctor.employee:id,fullname',
                'order_parameter_laboratorium.parameter_laboratorium'
            ])
            ->orderBy('order_date', 'desc')
            ->get();

        // Only load peralatan that are used in this registration
        $list_peralatan = Peralatan::query()->get(); // Limit to 100 for performance, adjust as needed
        $alat_medis_yang_dipakai = OrderAlatMedis::where('registration_id', $registration->id)
            ->with('alat')
            ->get();

        // Doctors for alat medis, only those with employee
        $doctorsAlat = Doctor::with('employee')
            ->whereHas('employee')
            ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
            ->limit(100)
            ->get();

        // Only load radiology doctors
        $radiologyDoctors = Doctor::with('employee')
            ->whereHas('department_from_doctors', function ($query) {
                $query->where('name', 'like', '%radiologi%');
            })
            ->get();

        // Only load radiologi orders for this registration
        $radiologiOrders = [];
        OrderRadiologi::where('registration_id', $registration->id)
            ->with(['doctor.employee'])
            ->get()
            ->each(function ($order) use (&$radiologiOrders) {
                $radiologiOrders[$order->id] = $order;
            });

        // Eager load only recent registrations for the patient
        $patient = Patient::with(['registration' => function ($query) {
            $query->orderBy('id', 'desc')->limit(10);
        }])->find($registration->patient->id);

        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);

        // Only get the groupPenjaminId directly
        $groupPenjaminId = $registration->penjamin->group_penjamin_id;

        // Limit operasi types and categories for performance
        $jenisOperasi = TipeOperasi::orderBy('tipe')->limit(50)->get();
        $kategoriOperasi = KategoriOperasi::orderBy('nama_kategori')->limit(50)->get();
        $ruangans = Room::orderBy('ruangan')->get();
        $ruangans_operasi = Room::where('ruangan', 'OK')->orderBy('ruangan', 'asc')->limit(20)->get();

        // Only load order operasi for this registration, eager load only necessary relations
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
            'doctorsAlat' => $doctorsAlat,
            'list_peralatan' => $list_peralatan,
            'alat_medis_yang_dipakai' => $alat_medis_yang_dipakai,
            // 'dTindakan' => $dTindakan,
            'orderOperasi' => $orderOperasi,
            'kelasRawat' => $kelasRawat,
            'penjamin' => $penjamin,
            'groupedDoctors' => $groupedDoctors,
            'radiologyDoctors' => $radiologyDoctors,
            'radiologiOrders' => $radiologiOrders,
            'laboratoriumDoctors' => $laboratoriumDoctors,
            'laboratoriumOrders' => $laboratoriumOrders,
            'groupPenjaminId' => $groupPenjaminId,
            'laboratorium_categories' => KategoriLaboratorium::get(),
            'laboratorium_tarifs' => TarifParameterLaboratorium::get(),
            'radiology_categories' => KategoriRadiologi::get(),
            'radiology_tarifs' => TarifParameterRadiologi::get(),
            'kelas_rawats' => KelasRawat::limit(20)->get(),
            'registration' => $registration,
            'patient' => $patient,
            'departements' => $departements,
            'tindakan_medis' => $tindakan_medis,
            'age' => $age,
            'doctors' => $doctors,
            'ruangans_operasi' => $ruangans_operasi,
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

    /**
     * Menampilkan detail layanan untuk registrasi pasien tertentu.
     *
     * @param  int  $id
     * @param  string  $layanan
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function layanan(int $registrations, string $layanan)
    {
        $registration = Registration::with(['patient', 'doctor', 'penjamin'])
            ->findOrFail($registrations);

        $doctors = Doctor::with(['employee', 'departements', 'department_from_doctors'])->get();

        // Group doctors by department name, but keep as a collection of models, not arrays
        $groupedDoctors = $doctors->groupBy(function ($doctor) {
            return optional($doctor->department_from_doctors)->name;
        })->filter();

        $dTindakan = Departement::with(['grup_tindakan_medis.tindakan_medis'])->get();
        $kelas_rawats = KelasRawat::all();

        $patient = Patient::with(['registration' => function ($query) {
            $query->orderByDesc('id')->limit(10);
        }])->findOrFail($registration->patient->id);

        $age = displayAge($patient->date_of_birth);

        $tipeRegis = $registration->registration_type;
        $kelasRawat = in_array($tipeRegis, ['rawat-jalan', 'igd', 'odc']) ? 'RAWAT JALAN' : 'RAWAT INAP';

        $jaminan = $registration->penjamin->name;
        $penjamin = match ($jaminan) {
            'Umum' => 'Jaminan Pribadi',
            'BPJS' => 'BPJS Kesehatan',
            default => $jaminan,
        };

        $kategoriOperasi = KategoriOperasi::orderBy('nama_kategori')->limit(50)->get();
        $jenisOperasi = TipeOperasi::orderBy('tipe')->limit(50)->get();
        $ruangans = Room::orderBy('ruangan')->get();
        $ruangans_operasi = Room::where('ruangan', 'OK')->orderBy('ruangan')->limit(20)->get();

        $radiologiOrders = OrderRadiologi::where('registration_id', $registration->id)
            ->with(['doctor.employee'])
            ->get()
            ->keyBy('id');

        $radiologyDoctors = Doctor::with('employee')
            ->whereHas('department_from_doctors', fn($q) => $q->where('name', 'like', '%radiologi%'))
            ->get();

        $radiology_categories = KategoriRadiologi::get();
        $radiology_tarifs = TarifParameterRadiologi::get();

        $groupPenjaminId = $registration->penjamin->group_penjamin_id;

        $laboratoriumDoctors = Doctor::with('employee')
            ->whereHas('department_from_doctors', fn($q) => $q->where('name', 'like', '%lab%'))
            ->get();

        $laboratoriumOrders = OrderLaboratorium::where('registration_id', $registration->id)
            ->with([
                'doctor.employee:id,fullname',
                'order_parameter_laboratorium.parameter_laboratorium'
            ])
            ->orderByDesc('order_date')
            ->get();

        $laboratorium_categories = KategoriLaboratorium::get();
        $laboratorium_tarifs = TarifParameterLaboratorium::get();

        $list_peralatan = Peralatan::all();
        $alat_medis_yang_dipakai = OrderAlatMedis::where('registration_id', $registration->id)
            ->with('alat')
            ->get();

        $doctorsAlat = Doctor::with('employee')
            ->whereHas('employee')
            ->orderBy(Employee::select('fullname')->whereColumn('employees.id', 'doctors.employee_id'))
            ->limit(100)
            ->get();

        $departements = Departement::with(['grup_tindakan_medis.tindakan_medis'])->get();

        $tindakan_medis = $departements
            ->pluck('grup_tindakan_medis')
            ->flatten()
            ->pluck('tindakan_medis')
            ->flatten()
            ->unique('id')
            ->values();

        $viewData = compact(
            'registration',
            'doctors',
            'groupedDoctors',
            'dTindakan',
            'kelas_rawats',
            'patient',
            'age',
            'kelasRawat',
            'penjamin',
            'kategoriOperasi',
            'jenisOperasi',
            'ruangans',
            'ruangans_operasi',
            'radiologiOrders',
            'radiologyDoctors',
            'radiology_categories',
            'radiology_tarifs',
            'groupPenjaminId',
            'laboratoriumDoctors',
            'laboratoriumOrders',
            'laboratorium_categories',
            'laboratorium_tarifs',
            'alat_medis_yang_dipakai',
            'list_peralatan',
            'doctorsAlat',
            'departements',
        );

        return match ($layanan) {
            'tindakan-medis'   => view('pages.simrs.pendaftaran.partials.tindakan-medis', $viewData),
            'laboratorium'     => view('pages.simrs.pendaftaran.partials.laboratorium', $viewData),
            'radiologi'        => view('pages.simrs.pendaftaran.partials.radiologi', $viewData),
            'pemakaian-alat'   => view('pages.simrs.pendaftaran.partials.pemakaian-alat', $viewData),
            'visite-dokter'    => view('pages.simrs.pendaftaran.partials.visite-dokter', $viewData),
            'operasi'          => view('pages.simrs.pendaftaran.partials.operasi', $viewData),
            'persalinan'       => view('pages.simrs.pendaftaran.partials.persalinan', $viewData),
            'gizi'             => view('pages.simrs.pendaftaran.partials.gizi', $viewData),
            'order-obat'       => view('pages.simrs.pendaftaran.partials.order-obat', $viewData),

            default            => abort(404, 'Layanan tidak ditemukan'),
        };
    }

    /**
     * Menampilkan halaman pop-up untuk mengubah penjamin.
     */
    public function ubahPenjaminView(Registration $registration)
    {
        // Ambil ID penjamin standar (asumsi namanya 'UMUM')
        $standarPenjamin = Penjamin::where('nama_perusahaan', 'STANDAR')->first();

        // Daftar semua penjamin untuk dropdown
        $penjamins = Penjamin::orderBy('nama_perusahaan')->get();

        // User verifikator
        $verifikatorUsers = User::where('is_active', 1)
            ->whereHas('otorisasiUser', function ($query) {
                $query->where('otorisasi_type', 'Ganti Penjamin');
            })
            ->orderBy('name')
            ->get();

        // Opsi untuk dropdown Hubungan (masih berguna jika ingin menampilkan/edit)
        $hubunganOptions = [
            'Diri Sendiri',
            'Suami',
            'Istri',
            'Anak',
            'Ayah',
            'Ibu',
            'Saudara Kandung Laki-laki',
            'Saudara Kandung Perempuan',
            'Lain-lain'
        ];

        return view('pages.simrs.pendaftaran.popups.ubah-penjamin', [
            'registration' => $registration,
            'patient' => $registration->patient, // <-- KIRIM DATA PATIENT KE VIEW
            'penjamins' => $penjamins,
            'users' => $verifikatorUsers,
            'standarPenjaminId' => $standarPenjamin ? $standarPenjamin->id : null,
            'hubunganOptions' => $hubunganOptions,
        ]);
    }

    /**
     * Memproses perubahan penjamin dari form pop-up.
     */
    public function ubahPenjaminAction(Request $request, Registration $registration)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string',
            'alasan' => 'required|string|min:10',
            'penjamin_id' => 'required|exists:penjamins,id',
            // Data pendukung (opsional, tergantung penjamin)
            'nama_perusahaan_pegawai' => 'nullable|string|max:255',
            'nomor_kepegawian' => 'nullable|string|max:255',
            'bagian_pegawai' => 'nullable|string|max:255',
            'grup_perusahaan' => 'nullable|string|max:255',
            'hubungan_pegawai' => 'nullable|string|max:255',
        ]);

        $user = User::find($request->user_id);
        if (!$this->zimbraLogin($user->email, $request->password)) {
            return back()->with('error', 'Password tidak valid untuk user yang dipilih (Verifikasi Gagal).')->withInput();
        }

        $penjaminLamaId = $registration->penjamin_id;

        // Update penjamin_id di tabel registrations.
        $registration->update([
            'penjamin_id' => $request->penjamin_id,
        ]);

        // Update data pendukung di tabel patients (jika ada input)
        $patient = $registration->patient;
        $patient->update([
            'nama_perusahaan_pegawai' => $request->input('nama_perusahaan_pegawai'),
            'nomor_kepegawaian' => $request->input('nomor_kepegawian'),
            'bagian_pegawai' => $request->input('bagian_pegawai'),
            'grup_perusahaan' => $request->input('grup_perusahaan'),
            'hubungan_pegawai' => $request->input('hubungan_pegawai'),
        ]);

        GantiPenjamin::create([
            'registration_id' => $registration->id,
            'user_id' => auth()->id(),
            'penjamin_id_lama' => $penjaminLamaId,
            'penjamin_id_baru' => $request->penjamin_id,
            'alasan' => $request->alasan,
        ]);

        return view('pages.simrs.pendaftaran.popups.close-and-refresh');
    }
}
