<?php

namespace App\Http\Controllers;

use App\Models\SIMRS\Departement;
use App\Models\PlasmaDisplayRawatJalan;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlasmaDisplayRawatJalanController extends Controller
{
    public function index()
    {
        $plasmas = PlasmaDisplayRawatJalan::latest()->get();
        return view('pages.simrs.plasma.rawat-jalan.index', compact('plasmas'));
    }

    /**
     * Menampilkan form untuk membuat plasma antrian baru.
     */
    public function create()
    {
        $allDepartements = Departement::orderBy('name')->get();
        return view('pages.simrs.plasma.rawat-jalan.create', compact('allDepartements'));
    }

    /**
     * Menyimpan data plasma antrian baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_loket' => 'required|string|max:255|unique:plasma_display_rawat_jalans,name',
            'did' => 'nullable|array',
        ]);

        // Buat instance baru
        $plasma = new PlasmaDisplayRawatJalan();
        $plasma->name = $request->nama_loket;
        $plasma->is_active = $request->has('is_del');
        $plasma->save();

        // Lampirkan departemen yang dipilih
        if ($request->has('did')) {
            $plasma->departements()->attach($request->did);
        }

        return redirect()->route('poliklinik.antrian-poli.index')
            ->with('success', 'Plasma Antrian baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $plasmaRawatJalan = PlasmaDisplayRawatJalan::with('departements')->findOrFail($id);
        $allDepartements = Departement::orderBy('name')->get();
        return view('pages.simrs.plasma.rawat-jalan.edit', compact('plasmaRawatJalan', 'allDepartements'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'departements' => 'nullable|array',
            'departements.*' => 'exists:departements,id'
        ]);

        try {
            $plasma = PlasmaDisplayRawatJalan::findOrFail($id);

            // Update basic information
            $plasma->name = $request->name;
            $plasma->is_active = $request->status;
            $plasma->save();

            // Sync departments
            if ($request->has('departements')) {
                $plasma->departements()->sync($request->departements);
            } else {
                $plasma->departements()->detach();
            }

            return redirect()
                ->route('poliklinik.antrian-poli.index')
                ->with('success', 'Plasma Antrian berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $plasmaRawatJalan = PlasmaDisplayRawatJalan::with('departements')->findOrFail($id);
        return view('pages.simrs.plasma.rawat-jalan.show', compact('plasmaRawatJalan'));
    }

    // app/Http/Controllers/PlasmaDisplayRawatJalanController.php
    public function getStatus(PlasmaDisplayRawatJalan $plasmaDisplayRawatJalan)
    {
        $plasmaDisplayRawatJalan->load('departements');

        // TIDAK PERLU LAGI MENCARI 'now_calling' DI SINI

        // Hanya ambil nomor antrian terakhir untuk setiap poli sebagai data awal
        $departementQueues = [];
        foreach ($plasmaDisplayRawatJalan->departements as $departement) {
            $lastCalledNumber = DB::table('registrations')
                ->where('departement_id', $departement->id)
                ->whereNotNull('waktu_panggil')
                ->whereDate('date', today())
                ->orderBy('waktu_panggil', 'desc')
                ->value('no_urut');

            $departementQueues[] = [
                'id' => $departement->id,
                'current_number' => $lastCalledNumber ? str_pad($lastCalledNumber, 2, '0', STR_PAD_LEFT) : '00'
            ];
        }

        return response()->json([
            'now_calling' => null, // Selalu null, karena ini akan di-handle oleh Echo
            'departement_queues' => $departementQueues,
        ]);
    }
}
