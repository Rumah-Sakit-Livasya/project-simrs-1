<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\BatalRegister;
use App\Models\SIMRS\Bed;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GantiDiagnosa;
use App\Models\SIMRS\GantiDokter;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TutupKunjungan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class RegistrationController extends Controller
{
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
            'registrations' => $registrations->where('status', 'online'),
            'departements' => Departement::orderBy('name')->get(),
        ]);
    }

    public function create($id, $registrasi)
    {
        $patient = Patient::where('id', $id)->first();
        $kelas_rawats = KelasRawat::all();
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        $doctors = Doctor::with('employee', 'departement')->get();

        // Group doctors by department
        $groupedDoctors = [];
        foreach ($doctors as $doctor) {
            $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
        }

        $doctorsIGD = Doctor::with('employee', 'departement')
            ->whereHas('departement', function ($query) {
                $query->where('name', 'POLIKLINIK UMUM');
            })
            ->get();

        $doctorsLAB = Doctor::with('employee', 'departement')
            ->whereHas('departement', function ($query) {
                $query->where('name', 'like', '%Laboratorium%');
            })
            ->get();

        switch ($registrasi) {
            case 'rawat-jalan':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Jalan",
                    'groupedDoctors' => $groupedDoctors,
                    'penjamins' => Penjamin::all(),
                    'case' => 'rawat-jalan',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'igd':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "IGD",
                    'doctors' => $doctorsIGD,
                    'penjamins' => Penjamin::all(),
                    'case' => 'igd',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'odc':
                $doctors = Doctor::with('employee', 'departement')->get();

                // Group doctors by department
                $groupedDoctors = [];
                foreach ($doctors as $doctor) {
                    $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
                }

                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "ODC",
                    'groupedDoctors' => $groupedDoctors,
                    'penjamins' => Penjamin::all(),
                    'case' => 'odc',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'rawat-inap':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Inap",
                    'groupedDoctors' => $groupedDoctors,
                    'kelas_rawats' => $kelas_rawats,
                    'kelasTitipan' => $kelas_rawats,
                    'penjamins' => Penjamin::all(),
                    'case' => 'rawat-inap',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'laboratorium':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Laboratorium",
                    'doctors' => $doctorsLAB,
                    'penjamins' => Penjamin::all(),
                    'case' => 'laboratorium',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'radiologi':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Radiologi",
                    'doctors' => Doctor::all(),
                    'penjamins' => Penjamin::all(),
                    'case' => 'radiologi',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'hemodialisa':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Hemodialisa",
                    'doctors' => Doctor::all(),
                    'penjamins' => Penjamin::all(),
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


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'nullable',
            'user_id' => 'nullable',
            'employee_id' => 'nullable',
            'doctor_id' => 'nullable',
            'registration_type' => 'nullable',
            'registration_date' => 'nullable',
            'doctor_id' => 'nullable',
            'poliklinik' => 'nullable|string',
            'penjamin_id' => 'nullable',
            'rujukan' => 'required|string',
            'dokter_perujuk' => 'nullable|integer',
            'tipe_rujukan' => 'nullable|string',
            'igd_type' => 'nullable|string',
            'odc_type' => 'nullable|string',
            'nama_perujuk' => 'nullable|string',
            'telp_perujuk' => 'nullable|string',
            'alamat_perujuk' => 'nullable|string',
            'diagnosa_awal' => 'nullable|string',
        ]);

        $validatedData['registration_date'] = Carbon::now();
        $validatedData['status'] = 'online';

        if ($validatedData['registration_type'] == 'rawat-jalan' || $validatedData['registration_type'] == 'igd') {
            $validatedData['departement_id'] = Doctor::where('id', $validatedData['doctor_id'])->first()->department_from_doctors->id;
        } else if ($validatedData['registration_type'] == 'odc') {
            $departement_id = Departement::where('kode', 'ODC')->first('id')->id;
            $validatedData['departement_id'] = $departement_id;
        } else if ($validatedData['registration_type'] == 'rawat-inap') {
            $departement_id = Departement::where('kode', 'RAWAT INAP')->first('id')->id;
            $validatedData['departement_id'] = $departement_id;
        }

        if ($validatedData['registration_type'] == 'rawat-inap') {
            $bed = Bed::findOrFail($request->bed_id);
            $bed->patient_id = $request->patient_id;
            $bed->update();
        }

        // Generate No Registration
        $registrationNumber = generate_registration_number();

        // Generate the sequence number for the doctor
        $doctorSequenceNumber = generateDoctorSequenceNumber($request->doctor_id, $request->registration_date);

        $validatedData['registration_number'] = $registrationNumber;
        $validatedData['no_urut'] = $doctorSequenceNumber;

        // return dd($validatedData);
        $store = Registration::create($validatedData);
        return redirect("/daftar-registrasi-pasien/$store->id")->with('success', 'Registrasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $registration = Registration::findOrFail($id);
        $cppt = CPPT::where('registration_id', $id)->get();
        // dd($cppt);
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

        $doctors = Doctor::with('employee', 'departement')->get();

        // Group doctors by department
        $groupedDoctors = [];
        foreach ($doctors as $doctor) {
            $groupedDoctors[$doctor->department_from_doctors->name][] = $doctor;
        }

        // $patient = $registration->patient;
        $patient = Patient::with(['registration' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($registration->patient->id);
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.detail-registrasi-pasien', [
            'kelasRawat' => $kelasRawat,
            'penjamin' => $penjamin,
            'groupedDoctors' => $groupedDoctors,
            'registration' => $registration,
            'patient' => $patient,
            'age' => $age
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



    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registration $registration)
    {
        //
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

            // Kosongkan Bed
            if ($registration['registration_type'] == 'rawat-inap') {
                $bed = Bed::findOrFail($registration->patient->bed->id);
                $bed->patient_id = null;
                $bed->save();
            }



            // Create a new BatalRegister entry
            BatalRegister::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'tgl_batal' => $request->tgl_batal,
                'alasan' => $request->alasan,
            ]);

            // // Delete the registration record and its related data
            // $registration->delete();

            // Update the status of the registration
            $registration->update([
                'status' => 'batal',
                'registration_close_date' => Carbon::now()
            ]);

            return redirect()->route('detail.pendaftaran.pasien', $registration->patient->id)->with('success', 'Registration has been cancelled successfully.');
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
                'status' => 'online',
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

            // Kosongkan Bed
            if ($registration['registration_type'] == 'rawat-inap') {
                $bed = Bed::findOrFail($registration->patient->bed->id);
                $bed->patient_id = null;
                $bed->save();
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
}
