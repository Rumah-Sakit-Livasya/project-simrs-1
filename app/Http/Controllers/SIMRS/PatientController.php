<?php

namespace App\Http\Controllers\SIMRS;

use RealRashid\SweetAlert\Facades\Alert;
use App\Helpers\MedicalRecordHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\Ethnic;
use App\Models\SIMRS\Family;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Kelurahan;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Provinsi;
use App\Models\SIMRS\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

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

    public function detail_patient(Patient $patient, Request $request)
    {
        // Jika akses dari tombol "Rujuk Rawat Inap / Poli Lain" (dengan nextRegis di request), jangan redirect, tampilkan detail
        if (!$request->has('nextRegis')) {
            $lastRegis = $patient->registration->last();
            if ($lastRegis && $lastRegis->status === 'aktif') {
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
        $rules = [
            'name' => 'required|max:255',
            'nickname' => 'max:255',
            'title' => 'required|max:255',
            'gender' => 'required|max:255',
            'place' => 'required|max:255',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'religion' => 'required|max:255',
            'blood_group' => 'nullable|max:255',
            'allergy' => 'nullable|max:255',
            'married_status' => 'nullable|max:255',
            'language' => 'required|max:255',
            'citizenship' => 'nullable|max:255',
            'id_card' => 'required|max:255',
            'address' => 'required|max:255',

            'is_manual_address' => 'required|boolean',

            // Conditional validation for address fields
            'province' => ['required_if:is_manual_address,1', 'nullable', 'string', 'max:255'],
            'regency' => ['required_if:is_manual_address,1', 'nullable', 'string', 'max:255'],
            'subdistrict' => ['required_if:is_manual_address,1', 'nullable', 'string', 'max:255'],
            'ward' => [
                Rule::requiredIf($request->is_manual_address == '1'),
                Rule::requiredIf($request->is_manual_address == '0'),
                'nullable',
                // Only validate existence if not manual address
                Rule::when(
                    $request->is_manual_address == '0',
                    Rule::exists('kelurahan', 'id'),
                ),
                'string',
                'max:255'
            ],

            'mobile_phone_number' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'last_education' => 'required|max:255',
            'ethnic' => 'required|max:255',
            'job' => 'required|max:255',

            // Informasi Keluarga
            'family_name' => 'nullable|max:255',
            'father_name' => 'nullable|max:255',
            'mother_name' => 'nullable|max:255',
            'family_number' => 'nullable|max:255',
            'family_age' => 'nullable|max:255',
            'family_job' => 'nullable|max:255',
            'family_relation' => 'nullable|max:255',
            'family_address' => 'nullable|max:255',

            // Informasi Penjamin
            'penjamin_id' => 'nullable|integer',
            'nomor_penjamin' => 'nullable|string|max:255',
            'nama_pegawai' => 'nullable|string|max:255',
            'nama_perusahaan_pegawai' => 'nullable|string|max:255',
            'hubungan_pegawai' => 'nullable|string|max:255',
            'nomor_kepegawaian' => 'nullable|string|max:255',
            'bagian_pegawai' => 'nullable|string|max:255',
            'grup_pegawai' => 'nullable|string|max:255',
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'title.required' => 'Gelar wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib diisi.',
            'place.required' => 'Tempat lahir wajib diisi.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'religion.required' => 'Agama wajib diisi.',
            'language.required' => 'Bahasa wajib diisi.',
            'id_card.required' => 'Nomor KTP wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'regency.required_if' => 'Kabupaten/Kota wajib diisi saat mode manual.',
            'subdistrict.required_if' => 'Kecamatan wajib diisi saat mode manual.',
            'province.required_if' => 'Provinsi wajib diisi saat mode manual.',
            'ward.required' => 'Kelurahan/Desa wajib diisi.',
            'ward.exists' => 'Kelurahan yang dipilih tidak valid.',
            'last_education.required' => 'Pendidikan terakhir wajib diisi.',
            'ethnic.required' => 'Suku wajib diisi.',
            'job.required' => 'Pekerjaan wajib diisi.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // If it's NOT manual address, fetch province/regency/subdistrict from DB
        if ($validatedData['is_manual_address'] == '0' && isset($validatedData['ward'])) {
            $kelurahan = Kelurahan::with('kecamatan.kabupaten.provinsi')->find($validatedData['ward']);
            if (!$kelurahan) {
                return back()
                    ->withErrors(['ward' => 'Kelurahan yang dipilih tidak valid.'])
                    ->withInput();
            }
            $validatedData['subdistrict'] = $kelurahan->kecamatan->name;
            $validatedData['regency'] = $kelurahan->kecamatan->kabupaten->name;
            $validatedData['province'] = $kelurahan->kecamatan->kabupaten->provinsi->name;
        }

        unset($validatedData['is_manual_address']);

        // Penjamin logic
        if ($request['penjamin_id']) {
            if ($request['penjamin_id'] !== 1) {
                $validatedData['nomor_penjamin'] = $request->nomor_penjamin;
                $validatedData['nama_pegawai'] = $request->nama_pegawai;
                $validatedData['nama_perusahaan_pegawai'] = $request->nama_perusahaan_pegawai;
                $validatedData['hubungan_pegawai'] = $request->hubungan_pegawai;
                $validatedData['nomor_kepegawaian'] = $request->nomor_kepegawaian;
                $validatedData['bagian_pegawai'] = $request->bagian_pegawai;
                $validatedData['grup_pegawai'] = $request->grup_pegawai;
            }
        }

        $validatedData['medical_record_number'] = \App\Helpers\MedicalRecordHelper::generateMedicalRecordNumber();
        $family = Family::create($validatedData);
        $validatedData['family_id'] = $family->id;
        $patient = Patient::create($validatedData);

        return redirect("/patients/$patient->id")->with('success', 'Pasien berhasil ditambahkan!');
    }


    public function edit_pendaftaran_pasien(Patient $patient)
    {
        // Gunakan Eager Loading untuk mengambil relasi agar lebih efisien
        $patient->load(['family', 'ethnic', 'penjamin', 'kelurahan.kecamatan.kabupaten.provinsi']);

        $dataPenjamin = Penjamin::all();
        $provinces = Provinsi::all();

        // =================================================================
        // PERBAIKAN UTAMA: SIAPKAN DATA ALAMAT DI SINI
        // =================================================================
        $alamatData = null;

        // 1. Cari data Kelurahan berdasarkan ID dari $patient->ward
        //    dan langsung eager load relasi ke atasnya.
        $kelurahan = Kelurahan::with('kecamatan.kabupaten.provinsi')->find($patient->ward);

        // 2. Sekarang, cek berdasarkan variabel $kelurahan yang baru kita buat
        if ($kelurahan && $kelurahan->kecamatan && $kelurahan->kecamatan->kabupaten && $kelurahan->kecamatan->kabupaten->provinsi) {
            $alamatData = [
                'provinsi' => [
                    'id' => $kelurahan->kecamatan->kabupaten->provinsi->id,
                    'name' => $kelurahan->kecamatan->kabupaten->provinsi->name
                ],
                'kabupaten' => [
                    'id' => $kelurahan->kecamatan->kabupaten->id,
                    'name' => $kelurahan->kecamatan->kabupaten->name
                ],
                'kecamatan' => [
                    'id' => $kelurahan->kecamatan->id,
                    'name' => $kelurahan->kecamatan->name
                ],
            ];
        }

        // dd($patient, $alamatData); // Hapus atau comment baris dd() yang lama

        return view('pages.simrs.pendaftaran.edit-pasien', [
            'patient' => $patient,
            'penjamins' => $dataPenjamin,
            'provinces' => $provinces,
            'ethnics' => Ethnic::all(),
            'alamatData' => $alamatData, // <-- KIRIM DATA ALAMAT YANG SUDAH BERSIH KE VIEW
        ]);
    }

    public function update_pendaftaran_pasien(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'nickname' => 'nullable|max:255',
            'title' => 'required|max:255',
            'gender' => 'required|max:255',
            'place' => 'required|max:255',
            'date_of_birth' => 'required|date_format:d-m-Y', // Ubah ini!
            'religion' => 'required|max:255',
            'blood_group' => 'nullable|max:255',
            'allergy' => 'nullable|max:255',
            'married_status' => 'nullable|max:255',
            'language' => 'required|max:255',
            'citizenship' => 'nullable|max:255',
            // Pastikan validasi unik mengabaikan ID pasien saat ini
            'id_card' => ['required', 'max:255', Rule::unique('patients')->ignore($patient->id)],
            'address' => 'required|max:255',
            // 'regency' => 'required|max:255',
            // 'subdistrict' => 'required|max:255',
            // 'ward' => 'required|max:255',
            'mobile_phone_number' => 'nullable|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('patients')->ignore($patient->id)],
            'last_education' => 'required|max:255',
            'ethnic' => 'required|exists:ethnics,id',
            'job' => 'required|max:255',

            // Informasi Keluarga
            'family_name' => 'nullable|max:255',
            'father_name' => 'nullable|max:255',
            'mother_name' => 'nullable|max:255',
            'family_number' => 'nullable|max:255',
            'family_age' => 'nullable|max:255',
            'family_job' => 'nullable|max:255',
            'family_relation' => 'nullable|max:255',
            'family_address' => 'nullable|max:255',

            // Informasi Penjamin
            'penjamin_id' => 'nullable|exists:penjamins,id',
            'nomor_penjamin' => 'nullable|string|max:255',
            'nama_pegawai' => 'nullable|string|max:255',
            'nama_perusahaan_pegawai' => 'nullable|string|max:255',
            'hubungan_pegawai' => 'nullable|string|max:255',
            'nomor_kepegawaian' => 'nullable|string|max:255',
            'bagian_pegawai' => 'nullable|string|max:255',
            'grup_pegawai' => 'nullable|string|max:255',
        ]);

        // Update data pasien
        $patient->update($validatedData);

        // Update data keluarga (jika ada)
        if ($patient->family) {
            $patient->family->update($validatedData);
        } else {
            // Jika sebelumnya tidak ada data keluarga, buat baru
            $family = Family::create($validatedData);
            $patient->update(['family_id' => $family->id]);
        }

        return "<script>alert('Data berhasil diperbarui'); window.close();</script>";
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

    public function print_label_rm(Patient $patient)
    {
        // Ambil data pasien berdasarkan ID
        $patient = Patient::findOrFail($patient->id);

        // Render view ke PDF
        $pdf = Pdf::loadView('pages.simrs.pendaftaran.print-label-rm', compact('patient'));

        // Unduh atau tampilkan PDF
        return $pdf->stream('label-rm.pdf'); // Untuk menampilkan di browser
        // return $pdf->download('kartu-pasien.pdf'); // Untuk mengunduh file
    }

    public function print_label_rm_pdf(Patient $patient)
    {
        // Ambil data pasien berdasarkan ID
        $patient = Patient::findOrFail($patient->id);

        // Render view ke PDF
        $pdf = Pdf::loadView('pages.simrs.pendaftaran.print-label-rm-pdf', compact('patient'));

        // Unduh atau tampilkan PDF
        return $pdf->stream('label-rm-pdf.pdf'); // Untuk menampilkan di browser
        // return $pdf->download('kartu-pasien.pdf'); // Untuk mengunduh file
    }

    public function print_label_gelang_anak(Patient $patient)
    {
        // Ambil data pasien berdasarkan ID
        $patient = Patient::findOrFail($patient->id);

        // Render view ke PDF
        $pdf = Pdf::loadView('pages.simrs.pendaftaran.print-label-gelang-anak', compact('patient'));

        // Unduh atau tampilkan PDF
        return $pdf->stream('label-gelang-anak.pdf'); // Untuk menampilkan di browser
        // return $pdf->download('kartu-pasien.pdf'); // Untuk mengunduh file
    }

    public function print_label_gelang_dewasa(Patient $patient)
    {
        // Ambil data pasien berdasarkan ID
        $patient = Patient::findOrFail($patient->id);

        // Render view ke PDF
        $pdf = Pdf::loadView('pages.simrs.pendaftaran.print-label-gelang-dewasa', compact('patient'));

        // Unduh atau tampilkan PDF
        return $pdf->stream('label-gelang-dewasa.pdf'); // Untuk menampilkan di browser
        // return $pdf->download('kartu-pasien.pdf'); // Untuk mengunduh file
    }

    public function print_tracer(Patient $patient, ?Registration $registration = null)
    {
        $patient = Patient::findOrFail($patient->id);

        return view('pages.simrs.pendaftaran.print-tracer', compact('patient', 'registration'));
    }

    public function print_charges_slip(Patient $patient, ?Registration $registration = null)
    {
        $patient = Patient::findOrFail($patient->id);

        return view('pages.simrs.pendaftaran.print-charges-slip', compact('patient', 'registration'));
    }

    public function print_surat_keterangan_lahir(Patient $patient, ?Registration $registration = null)
    {
        $patient = Patient::findOrFail($patient->id);

        return view('pages.simrs.pendaftaran.print-surat-keterangan-lahir', compact('patient', 'registration'));
    }

    public function print_general_consent(Patient $patient, ?Registration $registration = null)
    {
        $patient = Patient::findOrFail($patient->id);

        return view('pages.simrs.pendaftaran.print-general-consent', compact('patient', 'registration'));
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
        // Hapus return dd($patient); agar bisa menampilkan detail registrasi
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
        // Cek apakah pasien sudah punya registrasi aktif untuk tipe registrasi yang sama
        $existingRegistration = Registration::where('patient_id', $patient->id)
            ->where('registration_type', $registrasi)
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingRegistration) {
            // Jika sudah ada registrasi aktif, redirect ke detail registrasi
            return redirect("/daftar-registrasi-pasien/" . $existingRegistration->id)
                ->with('info', 'Pasien sudah memiliki registrasi aktif untuk tipe ini.');
        }

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

        $referringDoctors = Employee::where('is_doctor', true)
            ->where('is_active', 1)
            ->orderBy('fullname')
            ->get();

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
                    'age' => $age,
                    'referringDoctors' => $referringDoctors
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
