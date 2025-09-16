<?php

namespace App\Models\BPJS;

// Model ini tidak menggunakan Eloquent, berfungsi sebagai service class.
class BpjsPasienBaruMjkn
{
    /**
     * Mengambil daftar Pasien Baru dari API MJKN.
     *
     * @param array $filters Filter pencarian seperti tgl_awal dan tgl_akhir.
     * @return array
     */
    public static function fetchData(array $filters = [])
    {
        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS MJKN.
        // Endpointnya mungkin terkait dengan pendaftaran online atau pasien baru.
        //
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "URL_API_BPJS/...", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY
        $dummyApiResponse = [
            [
                "nomorkartu" => "0001234567890",
                "nik" => "3210101010100001",
                "nomorkk" => "3210101010100001",
                "nama" => "BUDI SANTOSO",
                "jeniskelamin" => "L",
                "tanggallahir" => "1990-01-15",
                "nohp" => "081234567890",
                "alamat" => "JL. MERDEKA NO. 1",
                "kodeprop" => "32",
                "namaprop" => "JAWA BARAT",
                "kodedati2" => "3210",
                "namadati2" => "KAB. MAJALENGKA",
                "kodekec" => "321001",
                "namakec" => "MAJALENGKA",
                "kodekel" => "32100101",
                "namakel" => "MAJALENGKA WETAN",
                "rtrw" => "001/002",
            ],
            [
                "nomorkartu" => "0009876543210",
                "nik" => "3210102020200002",
                "nomorkk" => "3210102020200002",
                "nama" => "SITI AMINAH",
                "jeniskelamin" => "P",
                "tanggallahir" => "1992-05-20",
                "nohp" => "089876543210",
                "alamat" => "JL. PAHLAWAN NO. 20",
                "kodeprop" => "32",
                "namaprop" => "JAWA BARAT",
                "kodedati2" => "3210",
                "namadati2" => "KAB. MAJALENGKA",
                "kodekec" => "321002",
                "namakec" => "PANYINGKIRAN",
                "kodekel" => "32100201",
                "namakel" => "PANYINGKIRAN",
                "rtrw" => "003/004",
            ]
        ];

        return $dummyApiResponse;
    }
}
