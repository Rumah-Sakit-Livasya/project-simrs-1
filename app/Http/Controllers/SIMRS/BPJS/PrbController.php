<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\BPJS\BpjsDataPrb;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PrbController extends Controller
{
    /**
     * Menampilkan halaman Data PRB.
     */
    public function index()
    {
        return view('app-type.simrs.bpjs.prb.data-prb');
    }

    /**
     * Menyediakan data untuk DataTables Data PRB dari Webservice.
     */
    public function listData(Request $request)
    {
        $filters = [
            'tgl_awal'  => Carbon::createFromFormat('d-m-Y', $request->input('tgl_awal'))->format('Y-m-d'),
            'tgl_akhir' => Carbon::createFromFormat('d-m-Y', $request->input('tgl_akhir'))->format('Y-m-d'),
        ];

        $apiData = BpjsDataPrb::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        $paginatedData = array_slice($apiData, $request->start, $request->length);

        $data = [];
        foreach ($paginatedData as $item) {
            $data[] = [
                'noKartupeserta' => $item['peserta']['noKartu'],
                'noSRB'          => $item['noSrb'],
                'kodeprogramPRB' => $item['programPrb']['nama'], // Tampilkan nama program
                'tglSRB'         => Carbon::parse($item['tglSrb'])->format('d M Y'),
                'keterangan'     => $item['keterangan'],
                'saran'          => $item['saran'],
                'noSEP'          => $item['noSep'], // Untuk parameter tombol
            ];
        }

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }


    /**
     * Menampilkan halaman Detail PRB.
     */
    public function detailPrb()
    {
        return view('app-type.simrs.bpjs.prb.detail-prb');
    }

    /**
     * Mengambil data detail PRB dari API VClaim.
     */
    public function getDetailPrbData(Request $request)
    {
        $request->validate([
            'srb' => 'required',
            'sep' => 'required',
        ]);

        $noSrb = $request->srb;
        $noSep = $request->sep;

        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS.
        // Endpoint: PRB/noSRB/{noSRB}/noSEP/{noSEP}
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // =================================================================================

        // Simulasi respons API SUKSES
        $apiResponse = [
            "response" => [
                "prb" => [
                    "noSrb" => $noSrb,
                    "tglSrb" => "2025-09-15",
                    "noSep" => $noSep,
                    "peserta" => [
                        "noKartu" => "0001112223334",
                        "nama" => "JANE DOE",
                    ],
                    "programPrb" => ["kode" => "0301R001", "nama" => "DM TIPE 2",],
                    "dpjp" => ["kode" => "12345", "nama" => "DR. BUDIMAN",],
                    "keterangan" => "Kondisi stabil, lanjutkan terapi",
                    "saran" => "Kontrol kembali setelah obat habis",
                    "obat" => [
                        "obat" => [
                            ["kodeObat" => "123", "namaObat" => "METFORMIN 500MG TAB", "signa" => "3x1", "jumlah" => "90"],
                            ["kodeObat" => "456", "namaObat" => "GLIMEPIRIDE 2MG TAB", "signa" => "1x1", "jumlah" => "30"]
                        ]
                    ]
                ]
            ],
            "metaData" => ["code" => "200", "message" => "Sukses"]
        ];

        if ($apiResponse['metaData']['code'] == '200') {
            return response()->json($apiResponse);
        } else {
            return response()->json($apiResponse, 400);
        }
    }
}
