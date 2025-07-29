<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;

class FarmasiResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.farmasi.transaksi-resep.index", [
            'gudangs' => WarehouseMasterGudang::where("apotek", 1)->where("warehouse", 0)->get()
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmasiResep $farmasiResep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiResep $farmasiResep)
    {
        //
    }
}
