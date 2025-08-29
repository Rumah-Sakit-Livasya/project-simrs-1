<?php

namespace App\Http\Controllers;

use App\Models\FarmasiAntrian;

class FarmasiPlasma extends Controller
{
    public function index()
    {
        return view('pages.simrs.farmasi.antrian-farmasi.index');
    }

    public function plasma()
    {
        return view('pages.simrs.farmasi.antrian-farmasi.plasma');
    }

    public function updateCallStatus($id)
    {
        $antrian = FarmasiAntrian::findOrFail($id);

        $antrian->update([
            'dipanggil' => 1,
        ]);

        $antrian->save();

        // return 200
        return response()->json([
            'message' => 'Status updated successfully',
        ], 200);
    }

    public function updateGiveStatus($id)
    {
        $antrian = FarmasiAntrian::findOrFail($id);

        $antrian->update([
            'penyerahan' => 1,
        ]);

        $antrian->resep->update([
            'handed' => 1,
        ]);

        $antrian->save();

        // return 200
        return response()->json([
            'message' => 'Status updated successfully',
        ], 200);
    }

    public function getAntrian($letter)
    {
        $query = FarmasiAntrian::query()->with([
            're',
            'resep',
            're.registration',
            're.registration.doctor',
            're.registration.doctor.employee',
            're.registration.patient',
            're.registration.departement',
        ]);

        // query only entries made today
        $query->whereDate('created_at', today());

        // query only entires where 'penyerahan' == false
        $query->where('penyerahan', false);

        switch (strtoupper($letter)) {
            case 'A':
                // Umum / Asuransi Non Racikan
                $query->where('tipe', 'umum');
                $query->where('racikan', false);
                break;

            case 'B':
                // Umum / Asuransi Racikan
                $query->where('tipe', 'umum');
                $query->where('racikan', true);
                break;

            case 'C':
                // BPJS Non Racikan
                $query->where('tipe', 'bpjs');
                $query->where('racikan', false);
                break;

            case 'D':
                // BPJS Racikan
                $query->where('tipe', 'bpjs');
                $query->where('racikan', true);
                break;
        }

        $results = $query->get()->all();

        return base64_encode(json_encode($results));
    }
}
