<?php

namespace App\Http\Controllers;

use App\Models\OrderLaboratorium;
use App\Models\OrderParameterLaboratorium;
use App\Models\RegistrationOTC;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Penjamin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderLaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    private function generate_order_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = OrderLaboratorium::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return 'LAB' . $year . $month . $day . $count;
    }

    function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::whereDate('created_at', $date->toDateString())->count() + 1;
        $count = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "OTC" . $year . $month . $day . $count;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['parameters' => json_decode($request->parameters, true)]);

        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'employee_id' => 'required|integer',
                'registration_type' => 'string',
                'catatan' => 'nullable|string',
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

                // get department id with department name "LABORATORIUM"
                $department = Departement::where('name', 'LABORATORIUM')->first();
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
                    'poly_ruang' => "LABORATORIUM",
                    'jenis_kelamin' => $request->get('jenis_kelamin'),
                    'order_date' => Carbon::now(),
                    'registration_number' => $this->generate_otc_registration_number(),
                    'order_lab' => $no_order,
                    'order_type' => $validatedData['order_type'],
                    'doctor' => $request->get('doctor'),
                    'doctor_id' => $validatedData['doctor_id'],
                    'alamat' => $request->get('alamat'),
                    'diagnosa_klinis' => $validatedData['diagnosa_awal']
                ])->id;

                $orderLaboratorium = OrderLaboratorium::create([
                    'user_id' => $validatedData['user_id'],
                    'otc_id' => $registrationOTCid,
                    'dokter_laboratorium_id' => $validatedData['doctor_id'],
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
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else { // normal / not OTC
            try {
                $orderLaboratorium = OrderLaboratorium::create([
                    'user_id' => $validatedData['user_id'],
                    'registration_id' => $validatedData['registration_id'],
                    'dokter_laboratorium_id' => $validatedData['doctor_id'],
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


        $orderLaboratoriumId = $orderLaboratorium->id;
        foreach ($validatedData['parameters'] as $parameter) {
            for ($i = 0; $i < $parameter['qty']; $i++) {
                OrderParameterLaboratorium::create([
                    'order_laboratorium_id' => $orderLaboratoriumId,
                    'parameter_laboratorium_id' => $parameter['id'],
                    'nominal_rupiah' => $parameter['price'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderLaboratorium $orderLaboratorium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderLaboratorium $orderLaboratorium)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderLaboratorium $orderLaboratorium)
    {
        //
    }
}
