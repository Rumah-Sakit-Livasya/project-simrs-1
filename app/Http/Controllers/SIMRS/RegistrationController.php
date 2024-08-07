<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::take(100)->orderBy('created_at', 'desc')->get();
        return view('pages.simrs.pendaftaran.daftar-registrasi-pasien', [
            'registrations' => Registration::orderBy('created_at')->get(),
            'patients' => $patients,
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
        return redirect('/daftar-registrasi-pasien')->with('success', 'Registrasi berhasil ditambahkan!');
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

        $patient = $registration->patient;
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.detail-registrasi-pasien', [
            'kelasRawat' => $kelasRawat,
            'penjamin' => $penjamin,
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
}
