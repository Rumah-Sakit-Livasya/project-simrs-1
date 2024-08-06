<?php

namespace App\Http\Controllers\SIMRS;

use RealRashid\SweetAlert\Facades\Alert;
use App\Helpers\MedicalRecordHelper;
use App\Http\Controllers\Controller;
use App\Models\SIMRS\Ethnic;
use App\Models\SIMRS\Family;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

use function displayAge;


class PatientController extends Controller
{

    public function daftar_rm(Request $request)
    {
        $query = Patient::query();

        // Apply filters based on form inputs
        $filters = ['medical_record_number', 'telp', 'name', 'mobile_phone_number', 'date_of_birth', 'address'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Check if penjamin_id filter is applied
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $patients = $query->orderBy('name', 'asc')->get();
        } else {
            // Return empty collection if no filters applied
            $patients = collect();
        }

        // Get penjamin data for the select input
        $penjamins = Penjamin::all();

        return view('pages.simrs.pendaftaran.daftar-rekam-medis', [
            'patients' => $patients,
            'penjamins' => $penjamins
        ]);
    }


    public function getData()
    {
        $data = Patient::all();
        return response()->json($data);
    }

    public function pendaftaran_pasien_baru()
    {
        $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');
        $provinces = $response->json()['provinsi'];
        return view('pages.simrs.pendaftaran.pendaftaran-pasien-baru', [
            'provinces' => $provinces,
            'ethnics' => Ethnic::all()
        ]);
    }

    public function detail_patient(Patient $patient)
    {
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.detail-pasien', [
            'patient' => $patient,
            'age' => $age
        ]);
    }

    public function simpan_pendaftaran_pasien(Request $request)
    {
        // return $request;
        // return $request['penjamin_id'] !== '3';
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'nickname' => 'max:255',
            'penjamin_id' => 'required',
            'title' => 'required|max:255',
            'gender' => 'required|max:255',
            'place' => 'required|max:255',
            'date_of_birth' => 'required|max:255',
            'religion' => 'required|max:255',
            'blood_group' => 'max:255',
            'married_status' => 'max:255',
            'job' => 'required|max:255',
            'language' => 'required|max:255',
            'citizenship' => 'max:255',
            'id_card' => 'required|max:255',
            'address' => 'required|max:255',
            'province' => 'max:255',
            'regency' => 'required|max:255',
            'subdistrict' => 'required|max:255',
            'ward' => 'required|max:255',
            'mobile_phone_number' => 'max:255',
            'email' => 'max:255',
            'last_education' => 'required|max:255',
            'ethnic' => 'required|max:255',

            // Informasi Keluarga
            'family_name' => 'required|max:255',
            'father_name' => 'required|max:255',
            'mother_name' => 'required|max:255',
            'family_number' => 'required|max:255',
            'family_age' => 'required|max:255',
            'family_job' => 'required|max:255',
            'family_relation' => 'required|max:255',
            'family_address' => 'required|max:255',
        ]);

        if ($request['penjamin_id'] !== 1) {
            $validatedData['nomor_penjamin'] = $request->nomor_penjamin;
            $validatedData['nama_pegawai'] = $request->nama_pegawai;
            $validatedData['nama_perusahaan_pegawai'] = $request->nama_perusahaan_pegawai;
            $validatedData['hubungan_pegawai'] = $request->hubungan_pegawai;
            $validatedData['nomor_kepegawaian'] = $request->nomor_kepegawaian;
            $validatedData['bagian_pegawai'] = $request->bagian_pegawai;
            $validatedData['grup_perusahaan'] = $request->grup_perusahaan;
        }

        $validatedData['medical_record_number'] = MedicalRecordHelper::generateMedicalRecordNumber();
        $family = Family::create($validatedData);
        $validatedData['family_id'] = $family->id;
        Patient::create($validatedData);
        return redirect('/daftar-rekam-medis')->with('success', 'Pasien berhasil ditambahkan!');
    }

    public function edit_pendaftaran_pasien(Patient $patient)
    {
        // return $patient;
        $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');
        $provinces = $response->json()['provinsi'];

        return view('pages.simrs.pendaftaran.edit-pasien', [
            'patient' => $patient,
            'provinces' => $provinces,
            'ethnics' => Ethnic::all()
        ]);
    }

    public function update_pendaftaran_pasien(Request $request, Patient $patient)
    {
        return $patient;
        $validatedData = $request->validate([
            'name' => 'required|max:255', 'nickname' => 'max:255',
            'title' => 'required|max:255', 'gender' => 'required|max:255',
            'place' => 'required|max:255', 'date_of_birth' => 'required|max:255',
            'religion' => 'required|max:255', 'blood_group' => 'max:255',
            'allergy' => 'max:255', 'married_status' => 'max:255',
            'language' => 'required|max:255', 'citizenship' => 'max:255',
            'id_card' => 'max:255', 'address' => 'required|max:255',
            'province' => 'max:255', 'regency' => 'required|max:255',
            'subdistrict' => 'required|max:255', 'ward' => 'required|max:255',
            'mobile_phone_number' => 'max:255', 'email' => 'max:255',
            'last_education' => 'required|max:255', 'ethnic' => 'required|max:255',
            'job' => 'required|max:255',
        ]);

        $validatedData['medical_record_number'] = MedicalRecordHelper::generateMedicalRecordNumber();
        // Patient::create($validatedData);
        return redirect('/daftar_rekam_medis')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function print_identitas_pasien(Patient $patient)
    {
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.print-identitas-pasien', [
            'patient' => $patient,
            'age' => $age
        ]);
    }

    public function history_kunjungan_pasien(Patient $patient)
    {
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.history-kunjungan-pasien', [
            'patient' => $patient,
            'age' => $age
        ]);
    }

    public function detail_registrasi_pasien(Patient $patient)
    {

        return dd($patient);
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.detail-registrasi-pasien', [
            'patient' => $patient,
            'age' => $age
        ]);
    }

    public function form_registrasi($id, $registrasi)
    {
        $patient = Patient::where('id', $id)->first();
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);

        switch ($registrasi) {
            case 'rawat-jalan':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Jalan",
                    'case' => 'rawat-jalan',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'igd':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "IGD",
                    'case' => 'igd',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'odc':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "ODC",
                    'case' => 'odc',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'rawat-inap':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Rawat Inap",
                    'case' => 'rawat-inap',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'laboratorium':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Laboratorium",
                    'case' => 'laboratorium',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'radiologi':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Radiologi",
                    'case' => 'radiologi',
                    'patient' => $patient,
                    'age' => $age
                ]);
                break;

            case 'hemodialisa':
                return view('pages.simrs.pendaftaran.form-registrasi', [
                    'title' => "Hemodialisa",
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

    public function store_registrasi()
    {
    }
}
