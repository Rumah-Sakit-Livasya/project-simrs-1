<?php

namespace App\Http\Controllers;

use App\Models\OrderParameterLaboratorium;
use App\Models\RegistrationOTC;
use App\Models\RelasiParameterLaboratorium;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderLaboratoriumController extends Controller
{
    public function verificate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'verifikator_id' => 'required|integer',
            'verifikasi_date' => 'required|date'
        ]);

        OrderParameterLaboratorium::where('id', $validatedData['id'])
            ->update([
                'verifikator_id' => $validatedData['verifikator_id'],
                'verifikasi_date' => $validatedData['verifikasi_date']
            ]);

        return response('ok');
    }

    public function deleteParameter(Request $request)
    {
        $validatedData = $request->validate([
            'order_parameter_id' => 'required|integer',
            'order_id' => 'required|integer'
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
            if ($parameter->id <= $parameter_to_delete->id) continue;

            // if the next parameter can be ordered
            // it means there's no more sub parameter
            if ($parameter->parameter_laboratorium->is_order) break;

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

    function generate_otc_registration_number()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $count = RegistrationOTC::withTrashed()->whereDate('created_at', $date->toDateString())->count() + 1;
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

                // from table relasi_parameter_laboratorium, get all columns
                // where main_parameter_id equals parameter['id']
                $parameterLaboratorium = RelasiParameterLaboratorium::where('main_parameter_id', $parameter['id'])->get();

                // check if $parameterLaboratorium has length
                if (count($parameterLaboratorium) > 0) {
                    foreach ($parameterLaboratorium as $relasi) {
                        OrderParameterLaboratorium::create([
                            'order_laboratorium_id' => $orderLaboratoriumId,
                            'parameter_laboratorium_id' => $relasi->sub_parameter_id,
                            'nominal_rupiah' => 0,
                        ]);
                    }
                }
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

        $order = OrderLaboratorium::with([
            'registration',
            'order_parameter_laboratorium.parameter_laboratorium'
        ])->findOrFail($validatedData['id']);

        $order->update(['is_konfirmasi' => 1]);

        if (!$order->otc_id && $order->registration_id) {
            $billing = Bilingan::firstOrCreate(

                ['registration_id' => $order->registration_id],
                [
                    'patient_id' => $order->registration->patient_id,
                    'status' => 'belum final',
                    'wajib_bayar' => 0
                ]
            );

            $totalAmount = 0;

            foreach ($order->order_parameter_laboratorium as $parameter) {
                if (!$parameter->parameter_laboratorium || $parameter->nominal_rupiah <= 0) {
                    continue;
                }

                $tagihan = TagihanPasien::create([
                    'user_id' => auth()->id(),
                    'bilingan_id' => $billing->id,
                    'registration_id' => $order->registration_id,
                    'date' => Carbon::now(),
                    'tagihan' => '[Biaya Laboratorium] ' . $parameter->parameter_laboratorium->parameter,
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

    public function editOrderLaboratorium(Request $request)
    {
        // BELUM READY
        $validatedData = $request->validate([
            'order_id' => 'required|integer',
            'diagnosa_klinis' => 'required|string',
            'inspection_date' => 'required|date',
            'result_date' => 'required|date',
        ]);

        try {
            $order = OrderLaboratorium::find($validatedData['order_id']);
            $order->update([
                'diagnosa_klinis' => $validatedData['diagnosa_klinis'],
                'inspection_date' => $validatedData['inspection_date'],
                'result_date' => $validatedData['result_date'],
            ]);

            foreach ($order->order_parameter_laboratorium as $parameter) {
                $id = $parameter->id;
                if ($request->get('catatan_' . $id)) {
                    OrderParameterLaboratorium::find($id)
                        ->update([
                            'catatan' => $request->get('catatan_' . $id),
                        ]);
                }
                if ($request->get('hasil_' . $id)) {
                    OrderParameterLaboratorium::find($id)
                        ->update([
                            'hasil' => $request->get('hasil_' . $id),
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
