<?php

namespace App\Http\Controllers;

use App\Models\FarmasiSigna;
use Illuminate\Http\Request;

class FarmasiSignaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signas = FarmasiSigna::all();
        return view('pages.simrs.master-data.penunjang-medis.farmasi.signa', compact('signas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "kata" => "required|string"
        ]);

        FarmasiSigna::create([
            "kata" => trim($request->kata)
        ]);

        // response in json
        return response()->json([
            "message" => "Data berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiSigna $farmasiSigna)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiSigna $farmasiSigna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiSigna $farmasiSigna)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiSigna $farmasiSigna, $id)
    {
        FarmasiSigna::destroy($id);
        // response with json
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
