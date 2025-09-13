<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WsBPJSController extends Controller
{
    /**
     * Referensi Poli
     */
    public function referensiPoli()
    {
        return view('app-type.simrs.bpjs.wsbpjs.referensi-poli');
    }

    /**
     * Referensi Dokter
     */
    public function referensiDokter()
    {
        return view('app-type.simrs.bpjs.wsbpjs.referensi-dokter');
    }

    /**
     * Monitoring Antrian
     */
    public function monitoringAntrian()
    {
        return view('app-type.simrs.bpjs.wsbpjs.monitoring-antrian');
    }

    /**
     * Dashboard Pertanggal
     */
    public function dashboardPertanggal()
    {
        return view('app-type.simrs.bpjs.wsbpjs.dashboard-pertanggal');
    }

    /**
     * Dashboard Perbulan
     */
    public function dashboardPerbulan()
    {
        return view('app-type.simrs.bpjs.wsbpjs.dashboard-perbulan');
    }

    /**
     * Antrian Pertanggal
     */
    public function antrianPertanggal()
    {
        return view('app-type.simrs.bpjs.wsbpjs.antrian-pertanggal');
    }

    /**
     * Antrian Belum Dilayani
     */
    public function antrianBelumDilayani()
    {
        return view('app-type.simrs.bpjs.wsbpjs.antrian-belum-dilayani');
    }
}
