<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\BPJS\BpjsListDataFingerprint;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WsBPJSController extends Controller
{
    /**
     * Referensi Poli
     */
    public function referensiPoli()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.referensi-poli');
    }

    /**
     * Referensi Dokter
     */
    public function referensiDokter()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.referensi-dokter');
    }

    /**
     * Monitoring Antrian
     */
    public function monitoringAntrian()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.monitoring-antrian');
    }

    /**
     * Dashboard Pertanggal
     */
    public function dashboardPertanggal()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.dashboard-pertanggal');
    }

    /**
     * Dashboard Perbulan
     */
    public function dashboardPerbulan()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.dashboard-perbulan');
    }

    /**
     * Antrian Pertanggal
     */
    public function antrianPertanggal()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.antrian-pertanggal');
    }

    /**
     * Antrian Belum Dilayani
     */
    public function antrianBelumDilayani()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.antrian-belum-dilayani');
    }

    /**
     * Menampilkan halaman Get Fingerprint Peserta.
     */
    public function getFingerprintPeserta()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.get-fingerprint-peserta');
    }

    /**
     * Mengambil data fingerprint dari API BPJS VClaim.
     */
    public function getDataFingerprint(Request $request)
    {
        $request->validate([
            'noka' => 'required',
            'tgl_pelayanan' => 'required|date_format:d-m-Y',
        ]);

        $tglPelayanan = Carbon::createFromFormat('d-m-Y', $request->tgl_pelayanan)->format('Y-m-d');
        $nomorKartu = $request->noka;

        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS
        // menggunakan Guzzle HTTP.
        // Endpoint: Referensi/FingerPrint/Peserta/{nomorKartu}/TglPelayanan/{tglPelayanan}
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // =================================================================================

        // Untuk tujuan pengembangan, kita simulasi respons dari API
        // Contoh respons SUKSES
        $dummyApiResponseSuccess = [
            "response" => ["kode" => "1"],
            "metaData" => ["code" => "200", "message" => "OK"]
        ];

        // Contoh respons GAGAL
        $dummyApiResponseFail = [
            "response" => null,
            "metaData" => ["code" => "201", "message" => "Peserta Belum Melakukan Finger Print"]
        ];

        // Ganti ini dengan respons asli dari API Anda
        $apiResponse = $dummyApiResponseSuccess;

        $metaData = $apiResponse['metaData'];
        $response = $apiResponse['response'];

        $isSuccess = $metaData['code'] == '200' && isset($response['kode']) && $response['kode'] == '1';

        return response()->json([
            'success' => $isSuccess,
            'code' => $metaData['code'],
            'message' => $metaData['message'],
            'status' => $isSuccess ? 'Finger Print ditemukan.' : 'Finger Print tidak ditemukan.',
        ]);
    }

    public function listFingerprint()
    {
        return view('app-type.simrs.bpjs.ws-bpjs.list-data-fingerprint');
    }

    public function getListFingerprintData(Request $request)
    {
        $filters = [
            'tgl_sep' => Carbon::createFromFormat('d-m-Y', $request->input('tgl_sep'))->format('Y-m-d'),
            'layanan' => $request->input('layanan'),
        ];

        $apiData = BpjsListDataFingerprint::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        $paginatedData = array_slice($apiData, $request->start, $request->length);

        $data = [];
        foreach ($paginatedData as $item) {
            $data[] = [
                'noKartu' => $item['noKartu'],
                'noSep'   => $item['noSep'] ?? '-', // Beri nilai default jika null
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
