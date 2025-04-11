<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function getDepartements()
    {
        $departements = Departement::all();
        return response()->json($departements);
    }

    public function index()
    {
        $departements = Departement::orderBy('name', 'asc')->get();
        return view('pages.simrs.master-data.setup.departement.index', compact('departements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambah()
    {
        $doctors = Doctor::all();
        return view('pages.simrs.master-data.setup.departement.create', compact('doctors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'kode' => 'required',
            'keterangan' => 'required|max:255',
            'quota' => 'nullable|max:255',
            'kode_poli' => 'nullable|max:255',
            'default_dokter' => 'nullable|max:255',
            'publish_online' => 'nullable',
            'revenue_and_cost_center' => 'nullable',
            'master_layanan_rl' => 'nullable',
        ]);

        $store = Departement::create($validatedData);
        return redirect()->route('master-data.setup.departemen.index')->with('success', 'Departemen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function show(Departement $departement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Departement $departement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Departement $departement)
    {
        //
    }
}
