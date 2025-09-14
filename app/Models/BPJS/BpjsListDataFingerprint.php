<?php

namespace App\Models\BPJS;

// CATATAN: Model ini tidak menggunakan Eloquent.
class BpjsListDataFingerprint
{
    /**
     * Mengambil daftar Fingerprint dari API BPJS VClaim.
     *
     * @param array $filters Filter pencarian seperti tgl_sep dan layanan.
     * @return array
     */
    public static function fetchData(array $filters = [])
    {
        // =================================================================================
        // !! PENTING !!
        // Di sinilah Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS
        // menggunakan Guzzle HTTP.
        // Endpoint: Referensi/FingerPrint/List/TglPelayanan/{tglPelayanan}/JnsPelayanan/{jnsPelayanan}
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY
        $dummyApiResponse = [
            [
                "noKartu" => "0003109209546",
                "noSep"   => "0126R0050925V001431", // Contoh jika SEP sudah ada
            ],
            [
                "noKartu" => "0003324915685",
                "noSep"   => null, // Contoh jika SEP belum ada
            ],
            [
                "noKartu" => "0002922950722",
                "noSep"   => "0126R0050925V001430",
            ],
            [
                "noKartu" => "0001234567890",
                "noSep"   => null,
            ],
        ];

        return $dummyApiResponse;
    }
}
