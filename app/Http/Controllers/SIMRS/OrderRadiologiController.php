<?php

namespace App\Http\Controllers\SIMRS;

use Illuminate\Http\Request;
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

    function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::withTrashed()
            ->whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "OTC" . $year . $month . $day . $count;
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
                $department = Departement::where('name', 'RADIOLOGI')->first();
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
                    'poly_ruang' => "RADIOLOGI",
                    'jenis_kelamin' => $request->get('jenis_kelamin'),
                    'order_date' => Carbon::now(),
                    'registration_number' => $this->generate_otc_registration_number(),
                    'order_rad' => $no_order,
                    'order_type' => $validatedData['order_type'],
                    'doctor' => $request->get('doctor'),
                    'doctor_id' => $validatedData['doctor_id'],
                    'alamat' => $request->get('alamat'),
                    'diagnosa_klinis' => $validatedData['diagnosa_awal']
                ])->id;

                $orderRadiologi = OrderRadiologi::create([
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
                    'is_konfirmasi' => 0
                ]);
            } catch (\Exception $e) {
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
                    'status_billed' => 0
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
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
            'id' => 'required|integer'
        ]);

        // Find the radiology order with relationships
        $order = OrderRadiologi::with([
            'registration',
            'order_parameter_radiologi.parameter_radiologi' // Using the correct relationship name
        ])->findOrFail($validatedData['id']);

        // Update confirmation status
        $order->update(['is_konfirmasi' => 1]);

        // Only create billing if this is not an OTC order and has registration
        if (!$order->otc_id && $order->registration_id) {
            // Find or create billing for the registration
            $billing = Bilingan::firstOrCreate(
                ['registration_id' => $order->registration_id],
                [
                    'patient_id' => $order->registration->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => 0
                ]
            );

            $totalAmount = 0;

            // Create billing items for each radiology parameter
            foreach ($order->order_parameter_radiologi as $parameter) {
                // Skip if parameter relation doesn't exist
                if (!$parameter->parameter_radiologi) {
                    continue;
                }

                $tagihan = TagihanPasien::create([
                    'user_id' => auth()->id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $order->registration_id,
                    'date' => Carbon::now(),
                    'tagihan' => '[Biaya Radiologi] ' . $parameter->parameter_radiologi->parameter,
                    'quantity' => 1,
                    'nominal' => $parameter->nominal_rupiah,
                    'harga' => $parameter->nominal_rupiah,
                    'wajib_bayar' => $parameter->nominal_rupiah
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

        return response("ok");
    }

    public function verificate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'verifikator_id' => 'required|integer',
            'verifikasi_date' => 'required|date'
        ]);

        OrderParameterRadiologi::where('id', $validatedData['id'])
            ->update([
                'verifikator_id' => $validatedData['verifikator_id'],
                'verifikasi_date' => $validatedData['verifikasi_date']
            ]);

        return response('ok');
    }

    public function parameterCheckUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'parameter_id' => 'required|integer',
            'user_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'catatan' => 'required'
        ]);

        OrderParameterRadiologi::where('id', $validatedData['parameter_id'])
            ->update([
                'catatan' => $validatedData['catatan'],
            ]);

        return response("<script>window.close();</script>");
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
                    if (!file_exists($storagePath)) {
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
                "success" => $filePaths,
                "fails" => $fails
            ]);
        }

        return response(null, 500)->json([
            "success" => null,
            "fails" => null,
            "error" => "Unknown error"
        ]);
    }

    public function editOrderRadiologi(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer',
            'diagnosa_klinis' => 'required|string',
            'inspection_date' => 'required|date',
            'pickup_date' => 'required|date',
        ]);

        try {
            $order = OrderRadiologi::find($validatedData['order_id']);
            $order->update([
                'diagnosa_klinis' => $validatedData['diagnosa_klinis'],
                'inspection_date' => $validatedData['inspection_date'],
                'pickup_date' => $validatedData['pickup_date'],
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

            return "<script>window.close()</script>";
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getLine()
            ]);
        }
    }
}
