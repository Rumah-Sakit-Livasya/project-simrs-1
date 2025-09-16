<?php

namespace App\Models\BPJS;

// CATATAN: Model ini tidak menggunakan Eloquent karena data diambil dari API eksternal.
class BpjsRujukanKhusus
{
    /**
     * Mengambil data Rujukan Khusus dari API BPJS VClaim.
     *
     * @param array $filters Filter pencarian seperti tgl_awal, tgl_akhir, no_rujukan.
     * @return array
     */
    public static function fetchData(array $filters = [])
    {
        // =================================================================================
        // !! PENTING !!
        // Di sinilah Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS
        // menggunakan Guzzle HTTP atau library lainnya.
        //
        // Contoh:
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', 'URL_API_BPJS/Rujukan/Khusus/...', [
        //     'headers' => [...],
        //     'query' => $filters
        // ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY yang strukturnya
        // mirip dengan respons asli dari API VClaim.
        $dummyApiResponse = [
            [
                "idRujukan" => "12345",
                "noRujukan" => "0126R0050925Y000111",
                "peserta" => [
                    "nama" => "BUDI SANTOSO",
                    "noKartu" => "0001234567890",
                ],
                "ppkRujukan" => [
                    "diagnosa" => [
                        "nama" => "J18.9 - Pneumonia, unspecified"
                    ]
                ],
                "tglMulaiRujukan" => "2025-09-01",
                "tglAkhirRujukan" => "2025-11-30",
            ],
            [
                "idRujukan" => "67890",
                "noRujukan" => "0126R0050925Y000222",
                "peserta" => [
                    "nama" => "SITI AMINAH",
                    "noKartu" => "0009876543210",
                ],
                "ppkRujukan" => [
                    "diagnosa" => [
                        "nama" => "N18.5 - Chronic kidney disease, stage 5"
                    ]
                ],
                "tglMulaiRujukan" => "2025-08-15",
                "tglAkhirRujukan" => "2025-11-13",
            ],
        ];

        return $dummyApiResponse;
    }
}
