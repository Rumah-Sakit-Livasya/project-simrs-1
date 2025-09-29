<?php

namespace App\Http\Controllers;

use App\Models\OrderParameterLaboratorium;
use App\Models\RegistrationOTC;
use App\Models\RelasiParameterLaboratorium;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderLaboratoriumController extends Controller
{
    public function verificate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'verifikator_id' => 'required|integer',
            'verifikasi_date' => 'required|date',
        ]);

        OrderParameterLaboratorium::where('id', $validatedData['id'])
            ->update([
                'verifikator_id' => $validatedData['verifikator_id'],
                'verifikasi_date' => $validatedData['verifikasi_date'],
            ]);

        return response('ok');
    }

    public function deleteParameter(Request $request)
    {
        $validatedData = $request->validate([
            'order_parameter_id' => 'required|integer',
            'order_id' => 'required|integer',
        ]);

        // first, get the order
        $order = OrderLaboratorium::findOrFail($validatedData['order_id']);

        // second, get all parameters of the order
        $parameters = $order->order_parameter_laboratorium;

        // third, get the parameter of the deleted parameter order
        $parameter_to_delete = OrderParameterLaboratorium::where('id', $validatedData['order_parameter_id'])
            ->where('order_laboratorium_id', $validatedData['order_id'])
            ->first();

        // finally, get relation of the deleted parameter
        $relations = RelasiParameterLaboratorium::where('main_parameter_id', $parameter_to_delete->parameter_laboratorium->id)->get();

        foreach ($parameters as $parameter) {
            // don't delete parameters with id that's smaller
            // than id of parameter that's going to be deleted
            if ($parameter->id <= $parameter_to_delete->id) {
                continue;
            }

            // if the next parameter can be ordered
            // it means there's no more sub parameter
            if ($parameter->parameter_laboratorium->is_order) {
                break;
            }

            // check if this parameter, as "sub_parameter_id"
            // is related to the parameter to delete, as "main_parameter_id"
            // from $relations
            $isRelated = $relations->contains(function ($relation) use ($parameter) {
                return $relation->sub_parameter_id === $parameter->parameter_laboratorium->id;
            });

            if ($isRelated) {
                $parameter->delete();
            }
        }

        $parameter_to_delete->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    private function generate_order_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = OrderLaboratorium::withTrashed()->whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return 'LAB' . $year . $month . $day . $count;
    }

    public function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::withTrashed()->whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return 'OTC' . $year . $month . $day . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Dekode 'parameters' jika dikirim sebagai string JSON
        $parameters = $request->input('parameters', []);
        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        }
        $request->merge(['parameters' => $parameters]);

        // 2. Tentukan aturan validasi
        $isOtc = $request->boolean('is_otc');
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'order_type' => ['required', Rule::in(['normal', 'cito'])],
            'diagnosa_awal' => 'required|string|max:255',
            'parameters' => 'required|array|min:1',
            'parameters.*.id' => 'required|integer|exists:parameter_laboratorium,id',
            'parameters.*.qty' => 'required|integer|min:1',
            'parameters.*.price' => 'required|numeric|min:0',
            'registration_id' => [Rule::requiredIf(!$isOtc), 'nullable', 'integer', 'exists:registrations,id'],
            'is_otc' => 'nullable|boolean',
            'nama_pasien' => ['nullable', Rule::requiredIf($isOtc), 'string', 'max:255'],
            'date_of_birth' => ['nullable', Rule::requiredIf($isOtc), 'date'],
            'jenis_kelamin' => ['nullable', Rule::requiredIf($isOtc), Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            // patient_id mungkin tidak dikirim dari form OTC, jadi buat opsional
            'patient_id' => 'nullable|integer|exists:patients,id',
            // medical_record_number bisa digunakan untuk mencari patient_id jika patient_id tidak ada
            'medical_record_number' => 'nullable|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation error in OrderLaboratoriumController@store', ['errors' => $validator->errors(), 'request' => $request->all()]);
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // Jika tidak ada patient_id tapi ada medical_record_number, cari patient_id
        if (
            (!array_key_exists('patient_id', $validatedData) || empty($validatedData['patient_id']))
            && !empty($validatedData['medical_record_number'])
        ) {
            $patient = Patient::where('medical_record_number', $validatedData['medical_record_number'])->first();
            $validatedData['patient_id'] = $patient ? $patient->id : null;
        }

        // Pastikan patient_id selalu ada di $validatedData, set null jika tidak ada
        if (!array_key_exists('patient_id', $validatedData)) {
            $validatedData['patient_id'] = null;
        }

        // 3. Proses penyimpanan menggunakan DB Transaction
        try {
            $orderLaboratorium = DB::transaction(function () use ($validatedData, $isOtc, $request) {
                // Cari employee_id dari doctor_id yang dikirim
                $dokterLab = Doctor::find($validatedData['doctor_id']);
                if (!$dokterLab || !$dokterLab->employee_id) {
                    throw new \Exception("Data Employee untuk Dokter Laboratorium tidak ditemukan.");
                }
                $dokterLaboratoriumId = $dokterLab->id;

                $no_order = $this->generate_order_number();
                $orderData = [];

                if ($isOtc) {
                    $tipePasien = '3'; // OTC

                    $department = Departement::where('name', 'like', '%LAB%')->firstOrFail();
                    $penjamin = Penjamin::where('nama_perusahaan', 'Standar')->firstOrFail();

                    $registrationOTC = RegistrationOTC::create([
                        'user_id' => $validatedData['user_id'],
                        'employee_id' => $validatedData['employee_id'],
                        'penjamin_id' => $penjamin->id,
                        'departement_id' => $department->id,
                        'tipe_pasien' => 'otc',
                        'nama_pasien' => $validatedData['nama_pasien'],
                        'date_of_birth' => $validatedData['date_of_birth'],
                        'no_telp' => $validatedData['no_telp'] ?? null,
                        'poly_ruang' => 'LABORATORIUM',
                        'jenis_kelamin' => $validatedData['jenis_kelamin'],
                        'order_date' => Carbon::now()->toDateTimeString(),
                        'registration_number' => $this->generate_otc_registration_number(),
                        'order_lab' => $no_order,
                        'order_type' => $validatedData['order_type'],
                        'doctor_id' => $validatedData['doctor_id'],
                        'alamat' => $validatedData['alamat'] ?? null,
                        'diagnosa_klinis' => $validatedData['diagnosa_awal'],
                        'patient_id' => $validatedData['patient_id'],
                    ]);

                    $orderData['otc_id'] = $registrationOTC->id;
                } else { // Pasien terdaftar
                    $registration = Registration::find($validatedData['registration_id']);
                    $tipePasien = ($registration->registration_type === 'rawat-inap') ? '2' : '1';

                    $orderData['registration_id'] = $validatedData['registration_id'];
                }

                // Siapkan data untuk dimasukkan ke tabel 'order_laboratorium'
                $orderData += [
                    'user_id' => $validatedData['user_id'],
                    'patient_id' => $validatedData['patient_id'],
                    'doctor_id' => $dokterLaboratoriumId,
                    'order_date' => Carbon::now(),
                    'no_order' => $no_order,
                    'tipe_order' => $validatedData['order_type'],
                    'tipe_pasien' => $tipePasien,
                    'diagnosa_klinis' => $validatedData['diagnosa_awal'],
                    'status_isi_hasil' => 0,
                    'status_billed' => false,
                ];

                $orderLaboratorium = OrderLaboratorium::create($orderData);

                // Proses detail parameter (tidak berubah)
                foreach ($validatedData['parameters'] as $parameter) {
                    for ($i = 0; $i < $parameter['qty']; $i++) {
                        OrderParameterLaboratorium::create([
                            'order_laboratorium_id' => $orderLaboratorium->id,
                            'parameter_laboratorium_id' => $parameter['id'],
                            'nominal_rupiah' => $parameter['price'],
                        ]);

                        $relasiParameters = RelasiParameterLaboratorium::where('main_parameter_id', $parameter['id'])->get();
                        foreach ($relasiParameters as $relasi) {
                            OrderParameterLaboratorium::create([
                                'order_laboratorium_id' => $orderLaboratorium->id,
                                'parameter_laboratorium_id' => $relasi->sub_parameter_id,
                                'nominal_rupiah' => 0,
                            ]);
                        }
                    }
                }

                return $orderLaboratorium;
            });

            return response()->json(['success' => true, 'order_id' => $orderLaboratorium->id]);
        } catch (\Exception $e) {
            Log::error('Exception in OrderLaboratoriumController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $order = OrderLaboratorium::with([
            'registration',
            'order_parameter_laboratorium.parameter_laboratorium',
        ])->findOrFail($validatedData['id']);

        $order->update(['is_konfirmasi' => 1]);

        $success = true;
        $message = 'Konfirmasi pembayaran berhasil.';

        if (! $order->otc_id && $order->registration_id) {
            $billing = Bilingan::firstOrCreate(
                ['registration_id' => $order->registration_id],
                [
                    'patient_id' => $order->registration->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => 0,
                ]
            );

            $totalAmount = 0;

            foreach ($order->order_parameter_laboratorium as $parameter) {
                if (! $parameter->parameter_laboratorium || $parameter->nominal_rupiah <= 0) {
                    continue;
                }

                $tagihan = TagihanPasien::create([
                    'user_id' => auth()->id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $order->registration_id,
                    'date' => Carbon::now(),
                    'tagihan' => '[Biaya Laboratorium] ' . $parameter->parameter_laboratorium->parameter,
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

            // Update the total amount in the billing if we have items
            if ($totalAmount > 0) {
                $billing->update(['wajib_bayar' => $totalAmount]);
            }

            // Update the billing_id in the order if needed
            if ($order->bilingan_id !== $billing->id) {
                $order->update(['bilingan_id' => $billing->id]);
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function editOrderLaboratorium(Request $request)
    {
        // Gunakan Validator facade untuk kontrol pesan error yang lebih baik
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:order_laboratorium,id',
            'diagnosa_klinis' => 'nullable|string|max:255',
            'inspection_date' => 'nullable|date',
            'result_datetime' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            // Jika menggunakan AJAX, kembalikan JSON. Jika tidak, redirect dengan error.
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        // Gunakan DB Transaction untuk memastikan semua query berhasil atau tidak sama sekali
        DB::beginTransaction();
        try {
            // Ambil order beserta relasi parameternya untuk efisiensi
            $order = OrderLaboratorium::with('order_parameter_laboratorium')->findOrFail($validatedData['order_id']);

            // Update data utama order
            $order->update([
                'diagnosa_klinis' => $validatedData['diagnosa_klinis'],
                'inspection_date' => $validatedData['inspection_date'],
                'result_datetime'     => $validatedData['result_datetime'],
            ]);

            // Loop melalui parameter yang ada dan update jika ada input baru
            foreach ($order->order_parameter_laboratorium as $parameter) {
                $id = $parameter->id;

                // Ambil nilai dari request
                $catatan = $request->input('catatan_' . $id);
                $hasil = $request->input('hasil_' . $id);

                // Buat array untuk menampung data yang akan diupdate
                $updateData = [];

                // Cek apakah ada input 'catatan' untuk parameter ini
                if ($request->has('catatan_' . $id)) {
                    $updateData['catatan'] = $catatan;
                }

                // Cek apakah ada input 'hasil' untuk parameter ini
                if ($request->has('hasil_' . $id)) {
                    $updateData['hasil'] = $hasil;
                }

                // Jika ada data yang perlu diupdate, jalankan query
                if (!empty($updateData)) {
                    // Update sekali jalan, lebih efisien
                    $parameter->update($updateData);
                }
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            // --- INI BAGIAN UTAMA PERUBAHAN ---

            // **Opsi 1: Jika menggunakan form submit biasa (Full page reload)**
            // Ganti 'nama.route.order.list' dengan nama route Anda yang sebenarnya.
            return redirect()->route('laboratorium.list-order')->with('success', 'Order Laboratorium berhasil diperbarui!');

            // **Opsi 2: Jika menggunakan AJAX/Fetch API**
            /*
            return response()->json([
                'success' => true,
                'message' => 'Order Laboratorium berhasil diperbarui!',
                'redirect_url' => route('nama.route.order.list') // Kirim URL redirect ke frontend
            ]);
            */
        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan
            DB::rollBack();

            // Catat error untuk debugging
            Log::error('Gagal update order laboratorium: ' . $e->getMessage() . ' di baris ' . $e->getLine());

            // --- INI JUGA BERUBAH ---

            // **Opsi 1: Untuk form submit biasa**
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui order.')->withInput();

            // **Opsi 2: Untuk AJAX/Fetch API**
            /*
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
            ], 500);
            */
        }
    }
}
