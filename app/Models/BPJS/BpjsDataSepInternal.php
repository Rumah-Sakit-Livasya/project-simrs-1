<?php

namespace App\Models\BPJS;

// Model ini tidak menggunakan Eloquent, berfungsi sebagai service class.
class BpjsDataSepInternal
{
    /**
     * Mengambil daftar SEP Internal dari API BPJS VClaim.
     *
     * @param string $nomorSep Nomor SEP yang akan dicari.
     * @return array
     */
    public static function fetchData(string $nomorSep)
    {
        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS.
        // Endpoint: SEP/Internal/{noSEP}
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY
        $dummyApiResponse = [
            "response" => [
                "list" => [
                    [
                        "tglsep" => "2025-09-15 10:00:00.0",
                        "nmtujuanrujuk" => "PENYAKIT DALAM",
                        "nmpoliasal" => "UMUM",
                        "tglrujukinternal" => "2025-09-15 10:05:00.0",
                        "ppkpelsep" => "123456 - RS HARAPAN SEHAT",
                        "nmpenunjang" => "-",
                        "nmdiag" => "R51 - Headache",
                        "nmdokter" => "DR. BUDIMAN",
                        "fuser" => "ADMIN_RS",
                        "fdate" => "2025-09-15 10:05:00.0",
                        "nosep" => "0126R0050925V001500",
                        "idrujuk_internal" => "INT123456" // ID unik untuk hapus
                    ],
                    [
                        "tglsep" => "2025-09-15 10:00:00.0",
                        "nmtujuanrujuk" => "LABORATORIUM",
                        "nmpoliasal" => "PENYAKIT DALAM",
                        "tglrujukinternal" => "2025-09-15 11:30:00.0",
                        "ppkpelsep" => "123456 - RS HARAPAN SEHAT",
                        "nmpenunjang" => "LABORATORIUM",
                        "nmdiag" => "R51 - Headache",
                        "nmdokter" => "DR. BUDIMAN",
                        "fuser" => "ADMIN_RS",
                        "fdate" => "2025-09-15 11:30:00.0",
                        "nosep" => "0126R0050925V001500",
                        "idrujuk_internal" => "INT654321"
                    ]
                ]
            ],
            "metaData" => ["code" => "200", "message" => "Sukses"]
        ];

        return $dummyApiResponse;
    }
}
