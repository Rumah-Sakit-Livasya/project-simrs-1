<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bed;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Persalinan\Bayi;
use App\Models\SIMRS\Persalinan\OrderPersalinan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;
use App\Helpers\MedicalRecordHelper;
use App\Models\SIMRS\Operasi\OrderOperasi;

class BayiController extends Controller
{
    // Method untuk mengambil data dokter untuk Select2
    public function getDoctors(Request $request)
    {
        // PERBAIKAN: doctors.employee->id menjadi doctors.employee_id
        $query = Doctor::select('doctors.id', 'employees.fullname as text')
            ->join('employees', 'doctors.employee_id', '=', 'employees.id')
            ->join('departements', 'doctors.departement_id', '=', 'departements.id');

        if ($request->filled('departement_id')) {
            $query->where('doctors.departement_id', $request->departement_id);
        }

        $doctors = $query->orderBy('employees.fullname', 'asc')->get();

        return response()->json($doctors);
    }

    public function getDataForOrder(Request $request, $orderId)
    {
        try {
            $bayiList = Bayi::where(function ($query) use ($orderId) {
                $query->where('order_persalinan_id', $orderId)
                    ->orWhere('order_operasi_id', $orderId);
            })->latest()->get();

            return response()->json($bayiList);
        } catch (\Exception $e) {
            Log::error('Error in getDataForOrder: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data bayi.'], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     // Tambahkan log untuk request data
    //     Log::info('Store bayi request data:', $request->all());

    //     // 1. Validasi Input dari Form
    //     $validatedData = $request->validate([
    //         'order_persalinan_id'   => 'required|exists:order_persalinan,id',
    //         'bayi_id'               => 'nullable|exists:bayi,id',
    //         'doctor_id'             => 'required|exists:doctors,id',
    //         'bed_id'                => 'required|exists:beds,id',
    //         'kelas_rawat_id'        => 'required|exists:kelas_rawat,id',
    //         'nama_bayi'             => 'required|string|max:255',
    //         'tempat_lahir'          => 'required|string|max:255',
    //         'tgl_lahir'             => 'required|date',
    //         'jenis_kelamin'         => 'required|in:Laki-laki,Perempuan',
    //         'berat'                 => 'required|numeric|min:0',
    //         'panjang'               => 'required|numeric|min:0',
    //         'status_lahir'          => 'required|in:Hidup,Meninggal',
    //         'jenis_kelahiran'       => 'required|in:Tunggal,Kembar',
    //         'nama_keluarga'         => 'nullable|string|max:255',
    //         'lingkar_kepala'        => 'nullable|numeric|min:0',
    //         'lingkar_dada'          => 'nullable|numeric|min:0',
    //         'kelahiran_ke'          => 'nullable|integer|min:0',
    //         'kelainan_fisik'        => 'nullable|string',
    //         'kelahiran_normal'      => 'nullable|string',
    //         'kelahiran_dgn_tindakan' => 'nullable|string',
    //         'apgar_score_1_minute'  => 'nullable|integer|min:0|max:10',
    //         'apgar_score_5_minutes' => 'nullable|integer|min:0|max:10',
    //         'gestasi'               => 'nullable|integer|min:0',
    //         'pregnant_g'            => 'nullable|string|max:50',
    //         'pregnant_p'            => 'nullable|string|max:50',
    //         'pregnant_a'            => 'nullable|string|max:50',
    //         'placenta_weight'       => 'nullable|string|max:50',
    //         'placenta_measure'      => 'nullable|string|max:50',
    //         'placenta_anomaly'      => 'nullable|string|max:50',
    //         'pregnant_complication' => 'nullable|string',
    //         'partus'                => 'nullable|string',
    //         'partus_complication'   => 'nullable|string',
    //     ], [
    //         'bed_id.required'              => 'Kelas / Kamar Rawat untuk bayi wajib dipilih.',
    //         'kelas_rawat_id.required'      => 'Kelas Rawat ID tidak boleh kosong.',
    //         'doctor_id.required'           => 'Nama Dokter wajib dipilih.',
    //         'nama_bayi.required'           => 'Nama Bayi wajib diisi.',
    //         'order_persalinan_id.required' => 'Order persalinan tidak valid.',
    //         'order_persalinan_id.exists'   => 'Order persalinan tidak ditemukan.',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         // Tambahkan pengecekan bed_id valid
    //         if (!$request->bed_id || !Bed::find($request->bed_id)) {
    //             throw ValidationException::withMessages([
    //                 'bed_id' => 'Bed tidak valid atau tidak ditemukan.'
    //             ]);
    //         }

    //         // 2. Ambil data order persalinan (ibu)
    //         $order = OrderPersalinan::with('registration.patient')->findOrFail($request->order_persalinan_id);
    //         if (!$order->registration?->patient) {
    //             throw new \Exception('Data registrasi atau pasien ibu tidak ditemukan.');
    //         }
    //         $ibu = $order->registration->patient;

    //         // 3. Logika Pasien Bayi (Create / Update)
    //         $patientBayi = null;
    //         $bayi = null;
    //         $isNewBaby = empty($request->bayi_id);

    //         if ($isNewBaby) {
    //             // =============== MEMBUAT RECORD PATIENT BARU UNTUK BAYI ===============
    //             $patientBayi = Patient::create([
    //                 'medical_record_number' => MedicalRecordHelper::generateMedicalRecordNumber(),
    //                 'name'                  => $request->nama_bayi,
    //                 'place'                 => $request->tempat_lahir,
    //                 'date_of_birth'         => Carbon::parse($request->tgl_lahir)->format('Y-m-d'),
    //                 'gender'                => $request->jenis_kelamin,
    //                 // Data yang diwarisi dari ibu
    //                 'title'                 => 'By.',
    //                 'nickname'              => 'By. ' . explode(' ', $request->nama_bayi)[0],
    //                 'married_status'        => 'Belum Kawin',
    //                 'language'              => 'Indonesia',
    //                 'last_education'        => 'Belum Sekolah',
    //                 'job'                   => 'Belum Bekerja',
    //                 'religion'              => $ibu->religion ?? 'Islam',
    //                 'address'               => $ibu->address ?? '',
    //                 'ward'                  => $ibu->ward ?? '',
    //                 'subdistrict'           => $ibu->subdistrict ?? '',
    //                 'regency'               => $ibu->regency ?? '',
    //                 'province'              => $ibu->province ?? '',
    //                 'mobile_phone_number'   => $ibu->mobile_phone_number ?? '',
    //                 'ethnic'                => $ibu->ethnic ?? '',
    //                 'citizenship'           => $ibu->citizenship ?? 'WNI',
    //             ]);
    //         } else {
    //             // =============== MEMPERBARUI RECORD PATIENT YANG SUDAH ADA (Disesuaikan dengan logika lama yang berfungsi) ===============
    //             $bayi = Bayi::findOrFail($request->bayi_id); // Ambil record bayi yang akan di-update
    //             // Menggunakan findOrFail() seperti kode lama Anda yang berfungsi
    //             $patientBayi = Patient::findOrFail($bayi->patient_id); // Ambil record pasien bayi
    //             $patientBayi->update([
    //                 'name'          => $request->nama_bayi,
    //                 'gender'        => $request->jenis_kelamin,
    //                 'date_of_birth' => Carbon::parse($request->tgl_lahir)->format('Y-m-d'),
    //                 'place'         => $request->tempat_lahir,
    //             ]);
    //         }

    //         // 4. Menyiapkan data untuk tabel 'bayi'
    //         $validatedData['patient_id'] = $patientBayi->id;
    //         $validatedData['no_rm'] = $patientBayi->medical_record_number;
    //         $validatedData['registration_id'] = $order->registration_id;
    //         $validatedData['tgl_lahir'] = Carbon::parse($request->tgl_lahir);

    //         $bedInfo = Bed::with('room.kelas_rawat')->findOrFail($request->bed_id);
    //         if (!$bedInfo->room?->kelas_rawat) {
    //             throw new \Exception('Data bed, ruangan, atau kelas rawat tidak lengkap.');
    //         }
    //         $validatedData['kelas_kamar'] = $bedInfo->room->kelas_rawat->kelas . ' / ' . $bedInfo->room->ruangan . ' - ' . $bedInfo->nama_tt;

    //         // 5. Menyimpan atau Memperbarui data di tabel 'bayi'
    //         // Ambil bed_id ASLI dari objek $bayi sebelum di-update dengan $validatedData
    //         // Ini penting untuk mendapatkan nilai bed_id sebelum perubahan di baris `$bayi->update($validatedData);`
    //         $oldBedId = $isNewBaby ? null : $bayi->getOriginal('bed_id');
    //         $newBedId = (int)$request->bed_id;

    //         // Tambahkan log untuk bed management
    //         Log::info('Bed management:', [
    //             'oldBedId' => $oldBedId,
    //             'newBedId' => $newBedId,
    //         ]);

    //         if ($isNewBaby) {
    //             $bayi = Bayi::create($validatedData);
    //             $bayi->update([
    //                 'tgl_reg' => now(),
    //                 'tgl_jam_registrasi' => now(),
    //                 'no_label' => now()->format('ymd') . '-' . str_pad($bayi->id, 4, '0', STR_PAD_LEFT)
    //             ]);
    //         } else {
    //             // $bayi sudah di-fetch di atas jika !isNewBaby
    //             $bayi->update($validatedData);
    //         }

    //         // 6. =============== LOGIKA MANAJEMEN BED/KAMAR (BAGIAN KRITIS) ===============
    //         // Lakukan manajemen bed hanya jika ada perubahan pada bed_id atau ini bayi baru
    //         if ($isNewBaby || $oldBedId !== $newBedId) {

    //             // A. KOSONGKAN BED LAMA (jika ada bed lama yang terisi oleh pasien ini)
    //             if ($oldBedId && $oldBedId !== $newBedId) { // Pastikan bed lama ada dan berbeda dari bed baru
    //                 // Update tabel bed_patient untuk historis bed lama
    //                 DB::table('bed_patient')
    //                     ->where('bed_id', $oldBedId)
    //                     ->where('patient_id', $patientBayi->id) // Pastikan hanya mengosongkan bed yang ditempati pasien ini
    //                     ->whereNull('tanggal_keluar') // Hanya record riwayat yang masih aktif
    //                     ->update([
    //                         'status' => 'kosong',
    //                         'tanggal_keluar' => now(),
    //                         'updated_at' => now()
    //                     ]);

    //                 // Update tabel beds untuk mengosongkan patient_id di bed lama
    //                 DB::table('beds')
    //                     ->where('id', $oldBedId)
    //                     ->where('patient_id', $patientBayi->id) // Pastikan hanya mengosongkan bed yang ditempati pasien ini
    //                     ->update([
    //                         'patient_id' => null,
    //                         'updated_at' => now()
    //                     ]);
    //             }

    //             // B. ISI BED BARU
    //             // Pengecekan ketersediaan bed baru MENGGUNAKAN bed_patient sebagai sumber kebenaran
    //             $isBedActivelyOccupied = DB::table('bed_patient')
    //                 ->where('bed_id', $newBedId)
    //                 ->whereNull('tanggal_keluar') // Cari record yang masih aktif
    //                 ->where('patient_id', '!=', $patientBayi->id) // Izinkan pasien yang sama untuk tetap di bed yang sama saat update tanpa error
    //                 ->exists();

    //             // Tambahkan log untuk isBedActivelyOccupied
    //             Log::info('isBedActivelyOccupied:', ['value' => $isBedActivelyOccupied]);

    //             if ($isBedActivelyOccupied) {
    //                 throw ValidationException::withMessages([
    //                     'bed_id' => 'Kamar/Bed yang dipilih sudah terisi oleh pasien lain. Silakan pilih yang lain.'
    //                 ]);
    //             }

    //             // Jika bed yang baru sama dengan bed yang lama, tidak perlu insert/update bed_patient atau beds
    //             if ($oldBedId !== $newBedId) {
    //                 // Insert ke tabel bed_patient untuk historis bed baru
    //                 DB::table('bed_patient')->insert([
    //                     'patient_id' => $patientBayi->id,
    //                     'bed_id' => $newBedId,
    //                     'status' => 'terisi',
    //                     'tanggal_masuk' => now(), // Saat ini masuk ke bed baru
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);

    //                 // Update tabel beds untuk mengisi patient_id di bed baru
    //                 DB::table('beds')
    //                     ->where('id', $newBedId)
    //                     ->update([
    //                         'patient_id' => $patientBayi->id,
    //                         'updated_at' => now()
    //                     ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => $isNewBaby ? 'Data bayi berhasil disimpan!' : 'Data bayi berhasil diperbarui!',
    //             'bayi' => $bayi->fresh() // Ambil data terbaru
    //         ]);
    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         Log::error('Validation error saat menyimpan bayi: ' . json_encode($e->errors()));
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->validator->errors()->first(), // Tampilkan pesan error validasi pertama
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Gagal menyimpan data bayi: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //             'request' => $request->all(),
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function store(Request $request)
    // {
    //     Log::info('Store bayi request data:', $request->all());

    //     // 1. Validasi Input dari Form
    //     $validatedData = $request->validate([
    //         'order_persalinan_id'   => 'nullable|exists:order_persalinan,id',
    //         'order_operasi_id'      => 'nullable|exists:order_operasi,id',
    //         'bayi_id'               => 'nullable|exists:bayi,id',
    //         'doctor_id'             => 'required|exists:doctors,id',
    //         'bed_id'                => 'required|exists:beds,id',
    //         'kelas_rawat_id'        => 'required|exists:kelas_rawat,id',
    //         'nama_bayi'             => 'required|string|max:255',
    //         'tempat_lahir'          => 'required|string|max:255',
    //         'tgl_lahir'             => 'required|date',
    //         'jenis_kelamin'         => 'required|in:Laki-laki,Perempuan',
    //         'berat'                 => 'required|numeric|min:0',
    //         'panjang'               => 'required|numeric|min:0',
    //         'status_lahir'          => 'required|in:Hidup,Meninggal',
    //         'jenis_kelahiran'       => 'required|in:Tunggal,Kembar',
    //         'nama_keluarga'         => 'nullable|string|max:255',
    //         'lingkar_kepala'        => 'nullable|numeric|min:0',
    //         'lingkar_dada'          => 'nullable|numeric|min:0',
    //         'kelahiran_ke'          => 'nullable|integer|min:0',
    //         'kelainan_fisik'        => 'nullable|string',
    //         'kelahiran_normal'      => 'nullable|string',
    //         'kelahiran_dgn_tindakan' => 'nullable|string',
    //         'apgar_score_1_minute'  => 'nullable|integer|min:0|max:10',
    //         'apgar_score_5_minutes' => 'nullable|integer|min:0|max:10',
    //         'gestasi'               => 'nullable|integer|min:0',
    //         'pregnant_g'            => 'nullable|string|max:50',
    //         'pregnant_p'            => 'nullable|string|max:50',
    //         'pregnant_a'            => 'nullable|string|max:50',
    //         'placenta_weight'       => 'nullable|string|max:50',
    //         'placenta_measure'      => 'nullable|string|max:50',
    //         'placenta_anomaly'      => 'nullable|string|max:50',
    //         'pregnant_complication' => 'nullable|string',
    //         'partus'                => 'nullable|string',
    //         'partus_complication'   => 'nullable|string',
    //     ], [
    //         'bed_id.required'          => 'Kelas / Kamar Rawat untuk bayi wajib dipilih.',
    //         'kelas_rawat_id.required'  => 'Kelas Rawat ID tidak boleh kosong.',
    //         'doctor_id.required'       => 'Nama Dokter wajib dipilih.',
    //         'nama_bayi.required'       => 'Nama Bayi wajib diisi.',
    //     ]);

    //     // Validasi tambahan: salah satu dari order_persalinan_id atau order_operasi_id harus ada
    //     if (!$request->order_persalinan_id && !$request->order_operasi_id) {
    //         throw ValidationException::withMessages([
    //             'order' => 'Order persalinan atau operasi harus diisi.'
    //         ]);
    //     }
    //     if ($request->order_persalinan_id && $request->order_operasi_id) {
    //         throw ValidationException::withMessages([
    //             'order' => 'Hanya salah satu dari order persalinan atau operasi yang boleh diisi.'
    //         ]);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Pengecekan bed_id valid
    //         if (!$request->bed_id || !Bed::find($request->bed_id)) {
    //             throw ValidationException::withMessages([
    //                 'bed_id' => 'Bed tidak valid atau tidak ditemukan.'
    //             ]);
    //         }

    //         // 2. Ambil data order (persalinan atau operasi)
    //         $order = null;
    //         $ibu = null;
    //         if ($request->order_persalinan_id) {
    //             $order = OrderPersalinan::with('registration.patient')->findOrFail($request->order_persalinan_id);
    //             $ibu = $order->registration->patient;
    //         } elseif ($request->order_operasi_id) {
    //             $order = OrderOperasi::with('registration.patient')->findOrFail($request->order_operasi_id);
    //             $ibu = $order->registration->patient;
    //         }

    //         if (!$ibu) {
    //             throw new \Exception('Data registrasi atau pasien ibu tidak ditemukan.');
    //         }

    //         // 3. Logika Pasien Bayi (Create / Update)
    //         $patientBayi = null;
    //         $bayi = null;
    //         $isNewBaby = empty($request->bayi_id);

    //         if ($isNewBaby) {
    //             // Membuat record patient baru untuk bayi
    //             $patientBayi = Patient::create([
    //                 'medical_record_number' => MedicalRecordHelper::generateMedicalRecordNumber(),
    //                 'name'                  => $request->nama_bayi,
    //                 'place'                 => $request->tempat_lahir,
    //                 'date_of_birth'         => Carbon::parse($request->tgl_lahir)->format('Y-m-d'),
    //                 'gender'                => $request->jenis_kelamin,
    //                 'title'                 => 'By.',
    //                 'nickname'              => 'By. ' . explode(' ', $request->nama_bayi)[0],
    //                 'married_status'        => 'Belum Kawin',
    //                 'language'              => 'Indonesia',
    //                 'last_education'        => 'Belum Sekolah',
    //                 'job'                   => 'Belum Bekerja',
    //                 'religion'              => $ibu->religion ?? 'Islam',
    //                 'address'               => $ibu->address ?? '',
    //                 'ward'                  => $ibu->ward ?? '',
    //                 'subdistrict'           => $ibu->subdistrict ?? '',
    //                 'regency'               => $ibu->regency ?? '',
    //                 'province'              => $ibu->province ?? '',
    //                 'mobile_phone_number'   => $ibu->mobile_phone_number ?? '',
    //                 'ethnic'                => $ibu->ethnic ?? '',
    //                 'citizenship'           => $ibu->citizenship ?? 'WNI',
    //             ]);
    //         } else {
    //             // Memperbarui record patient yang sudah ada
    //             $bayi = Bayi::findOrFail($request->bayi_id);
    //             $patientBayi = Patient::findOrFail($bayi->patient_id);
    //             $patientBayi->update([
    //                 'name'          => $request->nama_bayi,
    //                 'gender'        => $request->jenis_kelamin,
    //                 'date_of_birth' => Carbon::parse($request->tgl_lahir)->format('Y-m-d'),
    //                 'place'         => $request->tempat_lahir,
    //             ]);
    //         }

    //         // 4. Menyiapkan data untuk tabel 'bayi'
    //         $validatedData['patient_id'] = $patientBayi->id;
    //         $validatedData['no_rm'] = $patientBayi->medical_record_number;
    //         $validatedData['registration_id'] = $order->registration_id;
    //         $validatedData['tgl_lahir'] = Carbon::parse($request->tgl_lahir);

    //         $bedInfo = Bed::with('room.kelas_rawat')->findOrFail($request->bed_id);
    //         if (!$bedInfo->room?->kelas_rawat) {
    //             throw new \Exception('Data bed, ruangan, atau kelas rawat tidak lengkap.');
    //         }
    //         $validatedData['kelas_kamar'] = $bedInfo->room->kelas_rawat->kelas . ' / ' . $bedInfo->room->ruangan . ' - ' . $bedInfo->nama_tt;

    //         // 5. Menyimpan atau Memperbarui data di tabel 'bayi'
    //         $oldBedId = $isNewBaby ? null : $bayi->getOriginal('bed_id');
    //         $newBedId = (int)$request->bed_id;

    //         Log::info('Bed management:', [
    //             'oldBedId' => $oldBedId,
    //             'newBedId' => $newBedId,
    //         ]);

    //         if ($isNewBaby) {
    //             $bayi = Bayi::create($validatedData);
    //             $bayi->update([
    //                 'tgl_reg' => now(),
    //                 'tgl_jam_registrasi' => now(),
    //                 'no_label' => now()->format('ymd') . '-' . str_pad($bayi->id, 4, '0', STR_PAD_LEFT)
    //             ]);
    //         } else {
    //             $bayi->update($validatedData);
    //         }

    //         // 6. Logika Manajemen Bed/Kamar
    //         if ($isNewBaby || $oldBedId !== $newBedId) {
    //             // Kosongkan bed lama
    //             if ($oldBedId && $oldBedId !== $newBedId) {
    //                 DB::table('bed_patient')
    //                     ->where('bed_id', $oldBedId)
    //                     ->where('patient_id', $patientBayi->id)
    //                     ->whereNull('tanggal_keluar')
    //                     ->update([
    //                         'status' => 'kosong',
    //                         'tanggal_keluar' => now(),
    //                         'updated_at' => now()
    //                     ]);

    //                 DB::table('beds')
    //                     ->where('id', $oldBedId)
    //                     ->where('patient_id', $patientBayi->id)
    //                     ->update([
    //                         'patient_id' => null,
    //                         'updated_at' => now()
    //                     ]);
    //             }

    //             // Isi bed baru
    //             $isBedActivelyOccupied = DB::table('bed_patient')
    //                 ->where('bed_id', $newBedId)
    //                 ->whereNull('tanggal_keluar')
    //                 ->where('patient_id', '!=', $patientBayi->id)
    //                 ->exists();

    //             Log::info('isBedActivelyOccupied:', ['value' => $isBedActivelyOccupied]);

    //             if ($isBedActivelyOccupied) {
    //                 throw ValidationException::withMessages([
    //                     'bed_id' => 'Kamar/Bed yang dipilih sudah terisi oleh pasien lain. Silakan pilih yang lain.'
    //                 ]);
    //             }

    //             if ($oldBedId !== $newBedId) {
    //                 DB::table('bed_patient')->insert([
    //                     'patient_id' => $patientBayi->id,
    //                     'bed_id' => $newBedId,
    //                     'status' => 'terisi',
    //                     'tanggal_masuk' => now(),
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);

    //                 DB::table('beds')
    //                     ->where('id', $newBedId)
    //                     ->update([
    //                         'patient_id' => $patientBayi->id,
    //                         'updated_at' => now()
    //                     ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => $isNewBaby ? 'Data bayi berhasil disimpan!' : 'Data bayi berhasil diperbarui!',
    //             'bayi' => $bayi->fresh()
    //         ]);
    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         Log::error('Validation error saat menyimpan bayi: ' . json_encode($e->errors()));
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->validator->errors()->first(),
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Gagal menyimpan data bayi: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //             'request' => $request->all(),
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function store(Request $request)
    {
        // 1. Validasi Input dari Form
        $validatedData = $request->validate([
            'order_persalinan_id'   => 'nullable|exists:order_persalinan,id',
            'order_operasi_id'      => 'nullable|exists:order_operasi,id',
            'bayi_id'               => 'nullable|exists:bayi,id',
            'doctor_id'             => 'required|exists:doctors,id',
            'bed_id'                => 'required|exists:beds,id',
            'kelas_rawat_id'        => 'required|exists:kelas_rawat,id',
            'nama_bayi'             => 'required|string|max:255',
            'tempat_lahir'          => 'required|string|max:255',
            'tgl_lahir'             => 'required|date',
            'jenis_kelamin'         => 'required|in:Laki-laki,Perempuan',
            'berat'                 => 'required|numeric|min:0',
            'panjang'               => 'required|numeric|min:0',
            'status_lahir'          => 'required|in:Hidup,Meninggal',
            'jenis_kelahiran'       => 'required|in:Tunggal,Kembar',
            'nama_keluarga'         => 'nullable|string|max:255',
            'lingkar_kepala'        => 'nullable|numeric|min:0',
            'lingkar_dada'          => 'nullable|numeric|min:0',
            'kelahiran_ke'          => 'nullable|integer|min:0',
            'kelainan_fisik'        => 'nullable|string',
            'kelahiran_normal'      => 'nullable|string',
            'kelahiran_dgn_tindakan' => 'nullable|string',
            'apgar_score_1_minute'  => 'nullable|integer|min:0|max:10',
            'apgar_score_5_minutes' => 'nullable|integer|min:0|max:10',
            'gestasi'               => 'nullable|integer|min:0',
            'pregnant_g'            => 'nullable|string|max:50',
            'pregnant_p'            => 'nullable|string|max:50',
            'pregnant_a'            => 'nullable|string|max:50',
            'placenta_weight'       => 'nullable|string|max:50',
            'placenta_measure'      => 'nullable|string|max:50',
            'placenta_anomaly'      => 'nullable|string|max:50',
            'pregnant_complication' => 'nullable|string',
            'partus'                => 'nullable|string',
            'partus_complication'   => 'nullable|string',
        ], [
            'bed_id.required'          => 'Kelas / Kamar Rawat untuk bayi wajib dipilih.',
            'kelas_rawat_id.required'  => 'Kelas Rawat ID tidak boleh kosong.',
            'doctor_id.required'       => 'Nama Dokter wajib dipilih.',
            'nama_bayi.required'       => 'Nama Bayi wajib diisi.',
            'tgl_lahir.date'           => 'Format Tanggal & Jam Lahir tidak valid.',
        ]);

        // Validasi tambahan: memastikan salah satu order_id ada
        if (!$request->order_persalinan_id && !$request->order_operasi_id) {
            throw ValidationException::withMessages(['order' => 'Referensi order persalinan atau operasi tidak ditemukan.']);
        }

        DB::beginTransaction();
        try {
            // 2. Ambil data order (persalinan atau operasi)
            $order = null;
            if ($request->order_persalinan_id) {
                $order = OrderPersalinan::with('registration.patient')->findOrFail($request->order_persalinan_id);
            } elseif ($request->order_operasi_id) {
                $order = OrderOperasi::with('registration.patient')->findOrFail($request->order_operasi_id);
            }

            if (!$order || !$order->registration?->patient) {
                throw new \Exception('Data registrasi atau pasien ibu tidak ditemukan.');
            }
            $ibu = $order->registration->patient;

            // ======================= PERBAIKAN UTAMA & FINAL =======================
            // Ambil string tanggal dari request asli, parse SEKALI, lalu format ke string standar 'Y-m-d H:i:s'.
            // String ini 100% aman untuk diberikan ke model manapun.
            $birthDateString = Carbon::parse($request->input('tgl_lahir'))->format('Y-m-d H:i:s');
            // =========================================================================

            // 3. Logika Pasien Bayi (Create / Update)
            $patientBayi = null;
            $bayi = null;
            $isNewBaby = empty($request->bayi_id);

            if ($isNewBaby) {
                // =============== MEMBUAT RECORD PATIENT BARU UNTUK BAYI ===============
                $patientBayi = Patient::create([
                    'medical_record_number' => MedicalRecordHelper::generateMedicalRecordNumber(),
                    'name'                  => $validatedData['nama_bayi'],
                    'place'                 => $validatedData['tempat_lahir'],
                    'date_of_birth'         => $birthDateString, // GUNAKAN STRING AMAN
                    'gender'                => ($validatedData['jenis_kelamin'] == 'Laki-laki') ? 'L' : 'P',
                    'title'                 => 'By.',
                    'nickname'              => 'By. ' . explode(' ', $validatedData['nama_bayi'])[0],
                    'married_status'        => 'Belum Kawin',
                    'language'              => 'Indonesia',
                    'last_education'        => 'Belum Sekolah',
                    'job'                   => 'Belum Bekerja',
                    'religion'              => $ibu->religion ?? 'Islam',
                    'address'               => $ibu->address ?? 'TIDAK DIKETAHUI',
                    'ward'                  => $ibu->ward ?? 'TIDAK DIKETAHUI',
                    'subdistrict'           => $ibu->subdistrict ?? 'TIDAK DIKETAHUI',
                    'regency'               => $ibu->regency ?? 'TIDAK DIKETAHUI',
                    'province'              => $ibu->province ?? '',
                    'mobile_phone_number'   => $ibu->mobile_phone_number ?? '0000',
                    'ethnic'                => $ibu->ethnic ?? 'TIDAK DIKETAHUI',
                    'citizenship'           => $ibu->citizenship ?? 'WNI',
                ]);
            } else {
                // =============== MEMPERBARUI RECORD PATIENT YANG SUDAH ADA ===============
                $bayi = Bayi::findOrFail($request->bayi_id);
                $patientBayi = Patient::findOrFail($bayi->patient_id);
                $patientBayi->update([
                    'name'          => $validatedData['nama_bayi'],
                    'gender'        => ($validatedData['jenis_kelamin'] == 'Laki-laki') ? 'L' : 'P',
                    'date_of_birth' => $birthDateString, // GUNAKAN STRING AMAN
                    'place'         => $validatedData['tempat_lahir'],
                ]);
            }

            // 4. Menyiapkan data untuk tabel 'bayi'
            $dataForBayi = $validatedData;
            $dataForBayi['patient_id'] = $patientBayi->id;
            $dataForBayi['no_rm'] = $patientBayi->medical_record_number;
            $dataForBayi['registration_id'] = $order->registration_id;
            $dataForBayi['tgl_lahir'] = $birthDateString; // TIMPA DENGAN STRING AMAN

            $bedInfo = Bed::with('room.kelas_rawat')->findOrFail($request->bed_id);
            if (!$bedInfo->room?->kelas_rawat) {
                throw new \Exception('Data bed, ruangan, atau kelas rawat tidak lengkap.');
            }
            $dataForBayi['kelas_kamar'] = $bedInfo->room->kelas_rawat->kelas . ' / ' . $bedInfo->room->ruangan . ' - ' . $bedInfo->nama_tt;

            // 5. Menyimpan atau Memperbarui data di tabel 'bayi'
            $oldBedId = $isNewBaby ? null : $bayi->getOriginal('bed_id');
            $newBedId = (int)$request->bed_id;

            if ($isNewBaby) {
                $bayi = Bayi::create($dataForBayi);
                $bayi->update([
                    'tgl_reg' => now(),
                    'tgl_jam_registrasi' => now(),
                    'no_label' => now()->format('ymd') . '-' . str_pad($bayi->id, 4, '0', STR_PAD_LEFT)
                ]);
            } else {
                $bayi->update($dataForBayi);
            }

            // 6. Logika Manajemen Bed/Kamar
            if ($isNewBaby || $oldBedId !== $newBedId) {
                if ($oldBedId && $oldBedId !== $newBedId) {
                    DB::table('bed_patient')
                        ->where('bed_id', $oldBedId)->where('patient_id', $patientBayi->id)->whereNull('tanggal_keluar')
                        ->update(['status' => 'kosong', 'tanggal_keluar' => now(), 'updated_at' => now()]);

                    DB::table('beds')
                        ->where('id', $oldBedId)->where('patient_id', $patientBayi->id)
                        ->update(['patient_id' => null, 'updated_at' => now()]);
                }

                $isBedActivelyOccupied = DB::table('bed_patient')
                    ->where('bed_id', $newBedId)->whereNull('tanggal_keluar')
                    ->where('patient_id', '!=', $patientBayi->id)
                    ->exists();

                if ($isBedActivelyOccupied) {
                    throw ValidationException::withMessages([
                        'bed_id' => 'Kamar/Bed yang dipilih sudah terisi oleh pasien lain.'
                    ]);
                }

                if ($oldBedId !== $newBedId) {
                    DB::table('bed_patient')->insert([
                        'patient_id' => $patientBayi->id,
                        'bed_id' => $newBedId,
                        'status' => 'terisi',
                        'tanggal_masuk' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('beds')
                        ->where('id', $newBedId)
                        ->update(['patient_id' => $patientBayi->id, 'updated_at' => now()]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isNewBaby ? 'Data bayi berhasil disimpan!' : 'Data bayi berhasil diperbarui!',
                'bayi' => $bayi->fresh()
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error saat menyimpan bayi: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data bayi: ' . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 2000),
                'request' => $request->except(['_token']),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Menampilkan data bayi beserta relasinya untuk form edit.
     *
     * @param  \App\Models\SIMRS\Persalinan\Bayi $bayi
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Bayi $bayi)
    {
        // Load semua relasi yang diperlukan untuk form edit, termasuk relasi bertingkat
        // untuk mendapatkan informasi bed, ruangan, dan kelas rawat.
        $bayi->load([
            'doctor.employee', // Untuk nama dokter
            'patient',         // Untuk data pasien bayi
            'bed.room.kelas_rawat' // Penting untuk mendapatkan Kelas / Kamar Rawat
        ]);

        // Tambahkan log untuk show bayi data
        Log::info('Show bayi data', ['bayi' => $bayi->toArray()]);

        return response()->json($bayi);
    }

    /**
     * Menghapus data bayi beserta data terkait di tabel patients, beds, dan bed_patient.
     * Menggunakan Route Model Binding untuk mengambil instance Bayi.
     *
     * @param  \App\Models\SIMRS\Persalinan\Bayi $bayi
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Bayi $bayi)
    {
        DB::beginTransaction();
        try {
            // Route Model Binding otomatis akan melempar 404 jika bayi tidak ditemukan,
            // jadi validasi `$bayi` secara eksplisit tidak diperlukan di sini.

            $patientId = $bayi->patient_id;
            // Simpan bed_id bayi saat ini sebelum record bayi dihapus
            $currentBedId = $bayi->bed_id;

            // 1. Kosongkan Bed di tabel 'beds' jika bayi menempati bed
            if ($currentBedId && $patientId) {
                DB::table('beds')
                    ->where('id', $currentBedId)
                    ->where('patient_id', $patientId) // Pengaman tambahan: pastikan patient_id di bed masih sesuai
                    ->update([
                        'patient_id' => null,
                        'updated_at' => now()
                    ]);
            }

            // 2. Hapus semua record riwayat di 'bed_patient' untuk pasien bayi ini
            // Ini akan menghapus semua entri riwayat bed yang terkait dengan patient_id bayi.
            if ($patientId) {
                DB::table('bed_patient')
                    ->where('patient_id', $patientId)
                    ->delete(); // Hapus permanen dari tabel bed_patient
            }

            // 3. Hapus record Pasien yang berelasi secara permanen
            // Jika model Patient menggunakan SoftDeletes, gunakan forceDelete().
            // Jika tidak, delete() sudah cukup.
            if ($patientId) {
                $patient = Patient::find($patientId);
                if ($patient) {
                    $patient->forceDelete(); // Menghapus pasien secara permanen
                }
            }

            // 4. Hapus record bayi itu sendiri secara permanen
            // Sama seperti Patient, jika model Bayi menggunakan SoftDeletes, gunakan forceDelete().
            $bayi->forceDelete(); // Menghapus record bayi secara permanen

            DB::commit();

            return response()->json(['message' => 'Data bayi dan data pasien terkait berhasil dihapus, kamar telah dikosongkan.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus data bayi: ' . $e->getMessage() . ' di baris ' . $e->getLine());
            return response()->json(['message' => 'Terjadi kesalahan pada server saat menghapus data.'], 500);
        }
    }

    public function getKelasRawat()
    {
        $kelas = KelasRawat::where('kelas', 'not like', '%Rawat Jalan%')
            ->select('id', 'kelas as text')
            ->orderBy('kelas', 'asc')
            ->get();
        return response()->json($kelas);
    }

    public function getDataBed(Request $request)
    {
        $query = Bed::with(['room.kelas_rawat'])
            ->leftJoin('bed_patient', function ($join) {
                $join->on('beds.id', '=', 'bed_patient.bed_id')
                    ->whereNull('bed_patient.tanggal_keluar'); // Hanya record yang masih aktif
            })
            ->leftJoin('patients', 'bed_patient.patient_id', '=', 'patients.id') // Join ke tabel pasien untuk mendapatkan info pasien aktif
            ->select(
                'beds.*',
                'bed_patient.patient_id as active_occupant_patient_id',
                'patients.name as active_occupant_name',
                'patients.medical_record_number as active_occupant_medical_record_number'
            )
            ->when($request->kelas_rawat_id, function ($q) use ($request) {
                return $q->whereHas('room', function ($subQ) use ($request) {
                    $subQ->where('kelas_rawat_id', $request->kelas_rawat_id);
                });
            });

        return DataTables::of($query)
            ->addColumn('ruangan', function ($bed) {
                return optional($bed->room)->ruangan . ' - ' . optional($bed->room)->no_ruang;
            })
            ->addColumn('kelas', function ($bed) {
                return optional(optional($bed->room)->kelas_rawat)->kelas;
            })
            ->addColumn('pasien', function ($bed) {
                // Gunakan active_occupant_patient_id dari join
                if ($bed->active_occupant_patient_id) {
                    return $bed->active_occupant_name . ' (RM: ' . $bed->active_occupant_medical_record_number . ')';
                } else {
                    return '<span class="badge badge-success">Kosong</span>';
                }
            })
            ->addColumn('fungsi', function ($bed) {
                $roomInfo = optional(optional($bed->room)->kelas_rawat)->kelas . ' / ' .
                    optional($bed->room)->ruangan . ' - ' . $bed->nama_tt;

                // Gunakan active_occupant_patient_id untuk menentukan fungsi
                if ($bed->active_occupant_patient_id) {
                    return '<span class="badge badge-danger">Terisi</span>';
                } else {
                    return '<button type="button" class="btn btn-sm btn-info pilih-bed-bayi"
                        data-kelas-id="' . optional(optional($bed->room)->kelas_rawat)->id . '"
                        data-bed-id="' . $bed->id . '"
                        data-room-info="' . htmlspecialchars($roomInfo) . '">Pilih</button>';
                }
            })
            ->rawColumns(['pasien', 'fungsi'])
            ->make(true);
    }

    public function showBayiPopup(Request $request, $orderId, $type = 'persalinan')
    {
        $order = null;
        if ($type === 'persalinan') {
            $order = OrderPersalinan::with('registration.patient')->findOrFail($orderId);
        } elseif ($type === 'operasi') {
            $order = OrderOperasi::with('registration.patient')->findOrFail($orderId);
        } else {
            abort(400, 'Tipe order tidak valid.');
        }

        // Pass both $order and $type to the view
        return view('pages.simrs.bayi.bayi_popup', compact('order', 'type'));
    }

    /**
     * Menampilkan dan mencetak surat keterangan lahir untuk bayi.
     * Menggunakan Route Model Binding untuk mengambil instance Bayi.
     *
     * @param  \App\Models\SIMRS\Persalinan\Bayi $bayi
     * @return \Illuminate\View\View
     */
    public function printCertificate(Bayi $bayi)
    {
        // Eager load relationships needed for the certificate view
        // 1. doctor.employee for the doctor's fullname
        // 2. registration.patient for the mother's name and address
        $bayi->load('doctor.employee', 'registration.patient');

        // Return the view with the loaded Bayi data
        return view('pages.simrs.persalinan.partials.print', compact('bayi'));
    }
}
