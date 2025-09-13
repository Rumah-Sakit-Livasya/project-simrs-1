<?php

namespace App\Models\BPJS;

// Model ini tidak menggunakan Eloquent, berfungsi sebagai service class.
class BpjsDataPrb
{
    /**
     * Mengambil daftar PRB (Program Rujuk Balik) dari API BPJS VClaim.
     *
     * @param array $filters Filter pencarian seperti tgl_awal dan tgl_akhir.
     * @return array
     */
    public static function fetchData(array $filters = [])
    {
        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS.
        // Endpoint: PRB/tglMulai/{tglMulai}/tglAkhir/{tglAkhir}
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['prb']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY
        $dummyApiResponse = [
            [
                "noSrb" => "0126R0050925B000001",
                "tglSrb" => "2025-09-15",
                "noSep" => "0126R0050925V001450",
                "peserta" => [
                    "noKartu" => "0001112223334",
                    "nama" => "JANE DOE",
                ],
                "programPrb" => [
                    "kode" => "0301R001",
                    "nama" => "DM TIPE 2",
                ],
                "keterangan" => "Kondisi stabil, lanjutkan terapi",
                "saran" => "Kontrol kembali setelah obat habis",
            ],
            [
                "noSrb" => "0126R0050925B000002",
                "tglSrb" => "2025-09-14",
                "noSep" => "0126R0050925V001431",
                "peserta" => [
                    "noKartu" => "0003109209546",
                    "nama" => "ADE VERA HERMAWATI",
                ],
                "programPrb" => [
                    "kode" => "0302R002",
                    "nama" => "HIPERTENSI",
                ],
                "keterangan" => "Tekanan darah terkontrol",
                "saran" => "Lanjutkan obat, kontrol 1 bulan lagi.",
            ],
        ];

        return $dummyApiResponse;
    }
}
