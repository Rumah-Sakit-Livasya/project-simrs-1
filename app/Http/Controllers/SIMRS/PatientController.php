<?php

namespace App\Http\Controllers\SIMRS;

use RealRashid\SweetAlert\Facades\Alert;
use App\Helpers\MedicalRecordHelper;
use App\Http\Controllers\Controller;
use App\Models\SIMRS\Ethnic;
use App\Models\SIMRS\Family;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Provinsi;
use App\Models\SIMRS\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

use function displayAge;


class PatientController extends Controller
{
    public function daftar_rm(Request $request)
    {
        $query = Patient::query();
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
        // $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');
        $dataPenjamin = Penjamin::all();
        $provinces = Provinsi::all();
        return view('pages.simrs.pendaftaran.pendaftaran-pasien-baru', [
            'penjamins' => $dataPenjamin,
            'provinces' => $provinces,
            'ethnics' => Ethnic::all()
        ]);
    }

    public function detail_patient(Patient $patient)
    {
        $lastRegis = $patient->registration->last();
        if ($lastRegis) {
            if ($lastRegis->status === 'aktif') {
                return redirect("/daftar-registrasi-pasien/" . $lastRegis->id);
            }
        }
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);

        return view('pages.simrs.pendaftaran.detail-pasien', [
            'patient' => $patient,
            'age' => $age
        ]);
    }

    public function simpan_pendaftaran_pasien(Request $request)
    {
        $validatedData =
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'nickname' => 'max:255',
                'title' => 'required|max:255',
                'gender' => 'required|max:255',
                'place' => 'required|max:255',
                'date_of_birth' => 'required|max:255',
                'religion' => 'required|max:255',
                'blood_group' => 'max:255',
                'allergy' => 'max:255',
                'married_status' => 'max:255',
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
                'job' => 'required|max:255',

                // Informasi Keluarga
                'family_name' => 'max:255',
                'father_name' => 'max:255',
                'mother_name' => 'max:255',
                'family_number' => 'max:255',
                'family_age' => 'max:255',
                'family_job' => 'max:255',
                'family_relation' => 'max:255',
                'family_address' => 'max:255',

                // Informasi Penjamin
                'penjamin_id' => 'nullable',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'nickname.max' => 'Nama panggilan tidak boleh lebih dari 255 karakter.',
                'title.required' => 'Gelar wajib diisi.',
                'title.max' => 'Gelar tidak boleh lebih dari 255 karakter.',
                'gender.required' => 'Jenis kelamin wajib diisi.',
                'gender.max' => 'Jenis kelamin tidak boleh lebih dari 255 karakter.',
                'place.required' => 'Tempat lahir wajib diisi.',
                'place.max' => 'Tempat lahir tidak boleh lebih dari 255 karakter.',
                'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
                'date_of_birth.max' => 'Tanggal lahir tidak boleh lebih dari 255 karakter.',
                'religion.required' => 'Agama wajib diisi.',
                'religion.max' => 'Agama tidak boleh lebih dari 255 karakter.',
                'blood_group.max' => 'Golongan darah tidak boleh lebih dari 255 karakter.',
                'allergy.max' => 'Alergi tidak boleh lebih dari 255 karakter.',
                'married_status.max' => 'Status pernikahan tidak boleh lebih dari 255 karakter.',
                'language.required' => 'Bahasa wajib diisi.',
                'language.max' => 'Bahasa tidak boleh lebih dari 255 karakter.',
                'citizenship.max' => 'Kewarganegaraan tidak boleh lebih dari 255 karakter.',
                'id_card.required' => 'Nomor KTP wajib diisi.',
                'id_card.max' => 'Nomor KTP tidak boleh lebih dari 255 karakter.',
                'address.required' => 'Alamat wajib diisi.',
                'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
                'province.max' => 'Provinsi tidak boleh lebih dari 255 karakter.',
                'regency.required' => 'Kabupaten/Kota wajib diisi.',
                'regency.max' => 'Kabupaten/Kota tidak boleh lebih dari 255 karakter.',
                'subdistrict.required' => 'Kecamatan wajib diisi.',
                'subdistrict.max' => 'Kecamatan tidak boleh lebih dari 255 karakter.',
                'ward.required' => 'Kelurahan wajib diisi.',
                'ward.max' => 'Kelurahan tidak boleh lebih dari 255 karakter.',
                'mobile_phone_number.max' => 'Nomor HP tidak boleh lebih dari 255 karakter.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'last_education.required' => 'Pendidikan terakhir wajib diisi.',
                'last_education.max' => 'Pendidikan terakhir tidak boleh lebih dari 255 karakter.',
                'ethnic.required' => 'Suku wajib diisi.',
                'ethnic.max' => 'Suku tidak boleh lebih dari 255 karakter.',
                'job.required' => 'Pekerjaan wajib diisi.',
                'job.max' => 'Pekerjaan tidak boleh lebih dari 255 karakter.',

                // Informasi Keluarga
                'family_name.max' => 'Nama keluarga tidak boleh lebih dari 255 karakter.',
                'father_name.max' => 'Nama ayah tidak boleh lebih dari 255 karakter.',
                'mother_name.max' => 'Nama ibu tidak boleh lebih dari 255 karakter.',
                'family_number.max' => 'Nomor keluarga tidak boleh lebih dari 255 karakter.',
                'family_age.max' => 'Umur keluarga tidak boleh lebih dari 255 karakter.',
                'family_job.max' => 'Pekerjaan keluarga tidak boleh lebih dari 255 karakter.',
                'family_relation.max' => 'Hubungan keluarga tidak boleh lebih dari 255 karakter.',
                'family_address.max' => 'Alamat keluarga tidak boleh lebih dari 255 karakter.',

                // Informasi Penjamin
                'penjamin_id.nullable' => 'Penjamin tidak wajib diisi.',
            ]);

        if ($request['penjamin_id']) {
            if ($request['penjamin_id'] !== 1) {
                $validatedData['nomor_penjamin'] = $request->nomor_penjamin;
                $validatedData['nama_pegawai'] = $request->nama_pegawai;
                $validatedData['nama_perusahaan_pegawai'] = $request->nama_perusahaan_pegawai;
                $validatedData['hubungan_pegawai'] = $request->hubungan_pegawai;
                $validatedData['nomor_kepegawaian'] = $request->nomor_kepegawaian;
                $validatedData['bagian_pegawai'] = $request->bagian_pegawai;
                $validatedData['grup_perusahaan'] = $request->grup_perusahaan;
            }
        }

        $validatedData['medical_record_number'] = MedicalRecordHelper::generateMedicalRecordNumber();
        $family = Family::create($validatedData);
        $validatedData['family_id'] = $family->id;
        $patient = Patient::create($validatedData);
        return redirect("/patients/$patient->id")->with('success', 'Pasien berhasil ditambahkan!');
    }

    public function edit_pendaftaran_pasien(Patient $patient)
    {
        $dataPenjamin = Penjamin::all();
        $provinces = Provinsi::all();

        return view('pages.simrs.pendaftaran.edit-pasien', [
            'patient' => $patient,
            'penjamins' => $dataPenjamin,
            'provinces' => $provinces,
            'ethnics' => Ethnic::all()
        ]);
    }

    public function update_pendaftaran_pasien(Request $request, Patient $patient)
    {
        return $patient;
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'nickname' => 'max:255',
            'title' => 'required|max:255',
            'gender' => 'required|max:255',
            'place' => 'required|max:255',
            'date_of_birth' => 'required|max:255',
            'religion' => 'required|max:255',
            'blood_group' => 'max:255',
            'allergy' => 'max:255',
            'married_status' => 'max:255',
            'language' => 'required|max:255',
            'citizenship' => 'max:255',
            'id_card' => 'max:255',
            'address' => 'required|max:255',
            'province' => 'max:255',
            'regency' => 'required|max:255',
            'subdistrict' => 'required|max:255',
            'ward' => 'required|max:255',
            'mobile_phone_number' => 'max:255',
            'email' => 'max:255',
            'last_education' => 'required|max:255',
            'ethnic' => 'required|max:255',
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

    public function print_kartu_pasien(Patient $patient)
    {
        // Ambil data pasien berdasarkan ID
        $patient = Patient::findOrFail($patient->id);

        // Render view ke PDF
        $pdf = Pdf::loadView('pages.simrs.pendaftaran.print-kartu-pasien', compact('patient'));

        // Unduh atau tampilkan PDF
        return $pdf->stream('kartu-pasien.pdf'); // Untuk menampilkan di browser
        // return $pdf->download('kartu-pasien.pdf'); // Untuk mengunduh file
    }

    public function history_kunjungan_pasien(Patient $patient)
    {
        $birthdate = $patient->date_of_birth;
        $age = displayAge($birthdate);
        return view('pages.simrs.pendaftaran.history-kunjungan', [
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
        $lastRanapRegistration = Registration::where(['patient_id' => $patient->id, 'registration_type' => 'rawat-inap'])->orderBy('created_at', 'desc')->first();
        $grupPenjaminBPJS = GroupPenjamin::where('name', 'like', '%BPJS%')->first();
        $ranapBPJSdalam1bulan = false;
        if ($lastRanapRegistration) {
            $ranapBPJSdalam1bulan =
                $lastRanapRegistration['penjamin_id'] == $grupPenjaminBPJS->id && // ranap dengan BPJS
                \Carbon\Carbon::parse($lastRanapRegistration['registration_date'])->diffInDays() <= 30; // dalam 30 hari / 1 bulan
        }

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
                    'age' => $age,
                    'ranapBPJSdalam1bulan' => $ranapBPJSdalam1bulan
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

            // case 'radiologi':
            //     return view('pages.simrs.pendaftaran.form-registrasi', [
            //         'title' => "Radiologi",
            //         'case' => 'radiologi',
            //         'patient' => $patient,
            //         'age' => $age
            //     ]);
            //     break;

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

    public function search(Request $request)
    {
        $query = $request->get('query');
        $patients = Patient::where('name', 'LIKE', "%{$query}%")
            ->orWhere('medical_record_number', 'LIKE', "%{$query}%")
            ->with(['registration' => function ($query) {
                $query->orderBy('created_at', 'desc')->first();
            }])
            ->limit(5)
            ->get();

        return response()->json($patients);
    }
}
