<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BridgingEclaimController extends Controller
{
    /**
     * Setup Jaminan Eclaim
     */
    public function setupJaminan()
    {
        return view('app-type.simrs.bpjs.bridging-eclaim.setup-jaminan');
    }

    /**
     * Setup Tarif Eclaim
     */
    public function setupTarif()
    {
        return view('app-type.simrs.bpjs.bridging-eclaim.setup-tarif');
    }

    /**
     * Setup COB
     */
    public function setupCob()
    {
        return view('app-type.simrs.bpjs.bridging-eclaim.setup-cob');
    }

    /**
     * Update Data Pasien
     */
    public function updateDataPasien()
    {
        return view('app-type.simrs.bpjs.bridging-eclaim.update-data-pasien');
    }

    /**
     * Grouping Eclaim
     */
    public function groupingEclaim()
    {
        return view('app-type.simrs.bpjs.bridging-eclaim.grouping-eclaim');
    }
}
