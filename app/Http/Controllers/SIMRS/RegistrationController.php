<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\BatalRegister;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GantiDiagnosa;
use App\Models\SIMRS\GantiDokter;
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

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $patients = Patient::take(100)->orderBy('created_at', 'desc')->get();
        $registrations = Registration::where('status', '=', 'online')
            ->orderBy('created_at')
            ->get();

        return view('pages.simrs.pendaftaran.daftar-registrasi-pasien', [
            'registrations' => $registrations,
            // 'patients' => $patients,
            'departements' => Departement::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, $registrasi)
    {
        $patient = Patient::where('id', $id)->first();
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);


        switch ($registrasi) {
            case 'rawat-jalan':
                $doctors = Doctor::with('employee', 'departement')->get();

                // Group doctors by department
                $groupedDoctors = [];
                foreach ($doctors as $doctor) {
                    $groupedDoctors[$doctor->departement->name][] = $doctor;
                }

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
                    'doctors' => Doctor::all(),
                    'penjamins' => Penjamin::all(),
                    'case' => 'igd',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'odc':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "ODC",
                    'doctors' => Doctor::all(),
                    'penjamins' => Penjamin::all(),
                    'case' => 'odc',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'rawat-inap':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Inap",
                    'doctors' => Doctor::all(),
                    'penjamins' => Penjamin::all(),
                    'case' => 'rawat-inap',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'laboratorium':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Laboratorium",
                    'doctors' => Doctor::all(),
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'user_id' => 'required',
            'employee_id' => 'required',
            'doctor_id' => 'required',
            'registration_type' => 'required',
            'registration_date' => 'required',
            'doctor_id' => 'required',
            'poliklinik' => 'required|string',
            'penjamin_id' => 'required',
            'rujukan' => 'required|string',
            'dokter_perujuk' => 'nullable|integer',
            'tipe_rujukan' => 'nullable|string',
            'nama_perujuk' => 'nullable|string',
            'telp_perujuk' => 'nullable|string',
            'alamat_perujuk' => 'nullable|string',
            'diagnosa_awal' => 'required|string',
        ]);

        $validatedData['registration_date'] = Carbon::now();
        $validatedData['status'] = 'online';

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = Registration::findOrFail($id);
        // return dd($registration);

        $jaminan = $registration->penjamin->name;
        if ($jaminan === 'Umum') {
            $penjamin = 'Jaminan Pribadi';
        } elseif ($jaminan === 'BPJS') {
            $penjamin = "BPJS Kesehatan";
        } else {
            $penjamin = $registration->penjamin->name;
        }

        $tipeRegis = $registration->registration_type;
        if ($tipeRegis === 'rawat-jalan') {
            $kelasRawat = 'Rawat Jalan';
        }

        $doctors = Doctor::with('employee', 'departement')->get();

        // Group doctors by department
        $groupedDoctors = [];
        foreach ($doctors as $doctor) {
            $groupedDoctors[$doctor->departement->name][] = $doctor;
        }

        $patient = $registration->patient;
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.detail-registrasi-pasien', [
            'kelasRawat' => $kelasRawat,
            'penjamin' => $penjamin,
            'groupedDoctors' => $groupedDoctors,
            'jam' => Carbon::parse($registration->registration_date)->format('H:i'),
            'registration' => $registration,
            'patient' => $patient,
            'age' => $age
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
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
            $registration->update(['status' => 'batal']);

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

            // Create a new BatalRegister entry
            TutupKunjungan::create([
                'registration_id' => $registration->id,
                'user_id' => auth()->user()->id,
                'alasan_keluar' => $request->alasan_keluar,
                'lp_manual' => $request->lp_manual,
                'proses_keluar' => $request->proses_keluar,
            ]);

            // Update the status of the registration
            $registration->update(['status' => 'tutup_kunjungan']);

            return redirect()->route('detail.registrasi.pasien', $registration->id)->with('success', 'Registration has been closed successfully.');
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
