<?php

namespace App\Http\Controllers\SIMRS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderParameterRadiologi;
use App\Models\OrderRadiologi;
use Carbon\Carbon;

class OrderRadiologiController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        // Code to list all radiology orders
    }

    // Show the form for creating a new resource.
    public function create()
    {
        // Code to show form for creating a new radiology order
    }

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

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->merge(['parameters' => json_decode($request->parameters, true)]);

        $validatedData = $request->validate([
            'patient_id' => 'required|integer',
            'user_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'registration_type' => 'required|string',
            'poliklinik' => 'required|string',
            'registration_date' => 'required|date',
            'registration_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'order_type' => 'required|string',
            'diagnosa_awal' => 'required|string',
            'parameters' => 'required|array',
        ]);


        try {
            $orderRadiologi = OrderRadiologi::create([
                'user_id' => $validatedData['user_id'],
                'registration_id' => $validatedData['registration_id'],
                'dokter_radiologi_id' => $validatedData['doctor_id'],
                'order_date' => Carbon::now(),
                'no_order' => $this->generate_order_number(),
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

        $orderRadiologiId = $orderRadiologi->id;

        foreach ($validatedData['parameters'] as $parameter) {
            OrderParameterRadiologi::create([
                'order_radiologi_id' => $orderRadiologiId,
                'parameter_radiologi_id' => $parameter['id'],
                'qty' => $parameter['qty'],
                'nominal_rupiah' => $parameter['price'],
            ]);
        }

        return response("ok");
    }

    // Display the specified resource.
    public function show($id)
    {
        // Code to display a specific radiology order
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // Code to show form for editing a specific radiology order
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // Code to update a specific radiology order
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // Code to delete a specific radiology order
    }
}
