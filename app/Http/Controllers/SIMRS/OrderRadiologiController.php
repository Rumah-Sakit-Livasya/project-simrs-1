<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\OrderParameterRadiologi;
use App\Models\OrderRadiologi;
use App\Models\RegistrationOTC;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderRadiologiController extends Controller
{
    private function generate_order_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = OrderRadiologi::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return 'RAD' . $year . $month . $day . $count;
    }

    public function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::withTrashed()
            ->whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return 'OTC' . $year . $month . $day . $count;
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->merge(['parameters' => json_decode($request->parameters, true)]);

        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'employee_id' => 'required|integer',
                'registration_type' => 'string',
                'registration_id' => 'integer',
                'doctor_id' => 'required|integer',
                'order_type' => 'required|string',
                'diagnosa_awal' => 'required|string',
                'parameters' => 'required|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }

        $no_order = $this->generate_order_number();

        if ($request->filled('is_otc')) {
            try {
                $validatedData['registration_type'] = 'otc';

                // get department id with department name "RADIOLOGI"
                $department = Departement::where('name', 'like', '%RADIOLOGI%')->first();
                $validatedData['departement_id'] = $department->id;

                // get penjamin id with nama_perusahaan "Standar"
                $penjamin = Penjamin::where('nama_perusahaan', 'Standar')->first();
                $validatedData['penjamin_id'] = $penjamin->id;

                $registrationOTCid = RegistrationOTC::create([
                    'user_id' => $validatedData['user_id'],
                    'employee_id' => $validatedData['employee_id'],
                    'penjamin_id' => $validatedData['penjamin_id'],
                    'departement_id' => $validatedData['departement_id'],
                    'tipe_pasien' => $validatedData['registration_type'],
                    'nama_pasien' => $request->get('nama_pasien'),
                    'date_of_birth' => $request->get('date_of_birth'),
                    'no_telp' => $request->get('no_telp'),
                    'poly_ruang' => 'RADIOLOGI',
                    'jenis_kelamin' => $request->get('jenis_kelamin'),
                    'order_date' => $request->get('order_date'),
                    'registration_date' => $request->get('order_date'),
                    'registration_number' => $this->generate_otc_registration_number(),
                    'order_rad' => $no_order,
                    'order_type' => $validatedData['order_type'],
                    'doctor' => $request->get('doctor'),
                    'doctor_id' => $validatedData['doctor_id'],
                    'alamat' => $request->get('alamat'),
                    'diagnosa_klinis' => $validatedData['diagnosa_awal'],
                ])->id;

                $orderRadiologi = OrderRadiologi::create([
                    'registration_id' => $registrationOTCid,
                    'user_id' => $validatedData['user_id'],
                    'otc_id' => $registrationOTCid,
                    'dokter_radiologi_id' => $validatedData['doctor_id'],
                    'order_date' => Carbon::now(),
                    'no_order' => $no_order,
                    'tipe_order' => $validatedData['order_type'],
                    'tipe_pasien' => $validatedData['registration_type'],
                    'diagnosa_klinis' => $validatedData['diagnosa_awal'],
                    'status_isi_hasil' => 0,
                    'status_billed' => 0,
                    'is_konfirmasi' => 0,
                ]);
            } catch (\Exception $e) {
                \Log::error('OrderRadiologiController OTC Error: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else { // normal / not OTC
            try {
                $orderRadiologi = OrderRadiologi::create([
                    'user_id' => $validatedData['user_id'],
                    'registration_id' => $validatedData['registration_id'],
                    'dokter_radiologi_id' => $validatedData['doctor_id'],
                    'order_date' => Carbon::now(),
                    'no_order' => $no_order,
                    'tipe_order' => $validatedData['order_type'],
                    'tipe_pasien' => $validatedData['registration_type'],
                    'diagnosa_klinis' => $validatedData['diagnosa_awal'],
                    'status_isi_hasil' => 0,
                    'status_billed' => 0,
                ]);
            } catch (\Exception $e) {
                \Log::error('OrderRadiologiController Error: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $orderRadiologiId = $orderRadiologi->id;
        foreach ($validatedData['parameters'] as $parameter) {
            for ($i = 0; $i < $parameter['qty']; $i++) {
                OrderParameterRadiologi::create([
                    'order_radiologi_id' => $orderRadiologiId,
                    'parameter_radiologi_id' => $parameter['id'],
                    'nominal_rupiah' => $parameter['price'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        // Eager load semua relasi yang mungkin dibutuhkan
        $order = OrderRadiologi::with([
            'registration.patient', // Untuk pasien terdaftar
            'registration_otc',     // Untuk pasien OTC
            'order_parameter_radiologi.parameter_radiologi',
        ])->findOrFail($validatedData['id']);

        // 1. Update status konfirmasi, berlaku untuk semua jenis order
        $order->update(['is_konfirmasi' => 1]);

        $billing = null;
        $patientId = null;
        $registrationId = null; // Diisi hanya untuk pasien terdaftar

        // 2. Logika untuk menentukan Bilingan
        // Kasus 1: Pasien terdaftar (punya registration_id dan BUKAN OTC)
        if (!$order->otc_id && $order->registration_id && $order->registration) {
            $registrationId = $order->registration_id;
            $patientId = $order->registration->patient_id;

            $billing = Bilingan::firstOrCreate(
                ['registration_id' => $registrationId],
                [
                    'patient_id' => $patientId,
                    'status' => 'belum final',
                    'wajib_bayar' => 0, // Inisialisasi wajib bayar, nanti di-increment
                ]
            );
        }
        // Kasus 2: Pasien OTC (punya otc_id)
        elseif ($order->otc_id && $order->registration_otc) {
            // AMBIL patient_id DARI RELASI registration_otc
            // Asumsi: tabel registration_otcs memiliki kolom patient_id
            $patientId = $order->registration_otc->patient_id;

            // Jika tidak ada patient_id di registration_otc, maka proses billing untuk OTC tidak bisa lanjut
            if (!$patientId) {
                // Opsional: Log error bahwa data pasien OTC tidak lengkap
                \Log::warning('Billing untuk Order Radiologi OTC #' . $order->id . ' gagal dibuat karena patient_id tidak ditemukan.');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfirmasi berhasil, namun billing gagal dibuat karena data pasien OTC tidak lengkap.',
                ], 422);
            }

            // Cek apakah bilingan sudah pernah dibuat untuk order ini
            if ($order->bilingan_id) {
                $billing = Bilingan::find($order->bilingan_id);
            }

            // Jika belum ada, buat bilingan baru
            if (!$billing) {
                $billing = Bilingan::create([
                    'patient_id' => $patientId,
                    'registration_id' => null, // OTC tidak punya registration_id
                    'status' => 'belum final',
                    'wajib_bayar' => 0,
                ]);

                // Simpan bilingan_id ke order agar tidak duplikat
                $order->update(['bilingan_id' => $billing->id]);
            }
        }

        // 3. Proses pembuatan item tagihan jika Bilingan berhasil didapatkan
        if ($billing) {
            $totalAmount = 0;

            foreach ($order->order_parameter_radiologi as $parameter) {
                if (!$parameter->parameter_radiologi || $parameter->nominal_rupiah <= 0) {
                    continue;
                }

                $tagihan = TagihanPasien::create([
                    'user_id' => auth()->id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $registrationId, // Akan berisi ID untuk pasien terdaftar, dan NULL untuk OTC
                    'date' => Carbon::now(),
                    'tagihan' => '[Biaya Radiologi] ' . $parameter->parameter_radiologi->parameter,
                    'quantity' => 1,
                    'nominal_awal' => $parameter->nominal_rupiah,
                    'nominal' => $parameter->nominal_rupiah,
                    'harga' => $parameter->nominal_rupiah,
                    'wajib_bayar' => $parameter->nominal_rupiah,
                ]);

                BilinganTagihanPasien::create([
                    'tagihan_pasien_id' => $tagihan->id,
                    'bilingan_id' => $billing->id,
                ]);

                $totalAmount += $parameter->nominal_rupiah;
            }

            // Gunakan increment untuk menambah total tagihan, ini lebih aman
            if ($totalAmount > 0) {
                $billing->increment('wajib_bayar', $totalAmount);
            }
        }

        // Mengubah response agar lebih informatif
        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi pembayaran dan pembuatan tagihan berhasil.',
        ]);
    }

    public function verificate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'verifikator_id' => 'required|integer',
            'verifikasi_date' => 'required|date',
        ]);

        OrderParameterRadiologi::where('id', $validatedData['id'])
            ->update([
                'verifikator_id' => $validatedData['verifikator_id'],
                'verifikasi_date' => $validatedData['verifikasi_date'],
            ]);

        return response('ok');
    }

    public function parameterCheckUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'parameter_id' => 'required|integer',
            'user_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'catatan' => 'required',
        ]);

        OrderParameterRadiologi::where('id', $validatedData['parameter_id'])
            ->update([
                'catatan' => $validatedData['catatan'],
            ]);

        return response('<script>window.close();</script>');
    }

    public function uploadPhotoParameter(Request $request)
    {
        $validatedData = $request->validate([
            'parameter_id' => 'required|integer',
            'user_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'photo.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Check if files are uploaded
        if ($request->hasFile('photo')) {
            $filePaths = [];
            $fails = [];

            foreach ($request->file('photo') as $file) {
                if ($file->isValid()) {
                    $fileName = 'rad-param-' . $validatedData['parameter_id'] . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $directory = 'radiologi/parameter-photo/' . now()->format('m-Y') . '/' . now()->format('d-m-Y');

                    $storagePath = storage_path('app/public/' . $directory);
                    if (! file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }

                    $file->move($storagePath, $fileName);
                    $uploadedFilePath = $directory . '/' . $fileName;
                    $filePaths[] = $uploadedFilePath; // Store file paths in an array
                } else {
                    $fails[] = $file->getClientOriginalName();
                }
            }

            $currentFilePaths = OrderParameterRadiologi::where('id', $validatedData['parameter_id'])->value('foto');
            if ($currentFilePaths) {
                $currentFilePaths = json_decode($currentFilePaths, true);
                $filePaths = array_merge($currentFilePaths, $filePaths);
            }

            OrderParameterRadiologi::where('id', $validatedData['parameter_id'])
                ->update([
                    'foto' => json_encode($filePaths),
                ]);

            // Optionally, return success with all file paths
            return response()->json([
                'success' => $filePaths,
                'fails' => $fails,
            ]);
        }

        return response(null, 500)->json([
            'success' => null,
            'fails' => null,
            'error' => 'Unknown error',
        ]);
    }

    public function editOrderRadiologi(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer',
            'diagnosa_klinis' => 'required|string',
            'inspection_date' => 'required|date',
            'status_isi_hasil' => 'required|in:0,1',
        ]);

        try {
            $order = OrderRadiologi::find($validatedData['order_id']);
            $order->update([
                'diagnosa_klinis' => $validatedData['diagnosa_klinis'],
                'inspection_date' => $validatedData['inspection_date'],
                'status_isi_hasil' => $validatedData['status_isi_hasil'],
            ]);

            foreach ($order->order_parameter_radiologi as $parameter) {
                $id = $parameter->id;
                if ($request->get('radiografer_' . $id)) {
                    OrderParameterRadiologi::find($id)
                        ->update([
                            'radiografer_id' => $request->get('radiografer_' . $id),
                        ]);
                }
                if ($request->get('jumlah_film_' . $id)) {
                    OrderParameterRadiologi::find($id)
                        ->update([
                            'film_qty' => $request->get('jumlah_film_' . $id),
                        ]);
                }
            }

            return '<script>window.close()</script>';
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getLine(),
            ]);
        }
    }
}
