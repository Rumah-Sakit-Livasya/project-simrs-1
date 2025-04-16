<?php

namespace App\Http\Controllers;

use App\Models\OrderLaboratorium;
use Illuminate\Http\Request;

class OrderLaboratoriumController extends Controller
{
    public function store(Request $request)
    {
        return response()->json($request->all());
        $validatedData = $request->validate([
            'tanggal_order' => 'required|date',
            'registration_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'patient_id' => 'required|integer',
            'is_billed' => 'required|integer',
            'is_cito' => 'required|integer',
            'nama_pasien' => 'required|string',
            'diagnosa_klinis' => 'required|string',
            'tipe_pasien' => 'required|string'
        ]);

        try {
            OrderLaboratorium::create($validatedData);
            return response()->json([
                'message' => 'Order Laboratorium berhasil dibuat'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Order Laboratorium gagal dibuat',
                'error' => $e->getMessage()
            ], 500);
        }
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
