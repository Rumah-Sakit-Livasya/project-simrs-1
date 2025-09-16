<?php

namespace App\Models\BPJS;

// CATATAN: Model ini tidak menggunakan Eloquent.
class BpjsDataSuratKontrol
{
    /**
     * Mengambil daftar Surat Kontrol/SPRI dari API BPJS VClaim.
     *
     * @param array $filters Filter pencarian seperti bulan, tahun, noka, format_filter.
     * @return array
     */
    public static function fetchData(array $filters = [])
    {
        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS
        // menggunakan Guzzle HTTP. URL-nya akan berbeda tergantung filter yang dipilih.
        //
        // Contoh Logika:
        // $client = new \GuzzleHttp\Client();
        // $endpoint = "RencanaKontrol/ListRencanaKontrol";
        //
        // if ($filters['format_filter'] == '2') { // Berdasarkan Noka
        //     $endpoint = "RencanaKontrol/ListRencanaKontrol/Bulan/{$filters['bulan']}/Tahun/{$filters['tahun']}/Nokartu/{$filters['noka']}/filter/2";
        // } else { // Berdasarkan Tanggal
        //     $endpoint = "RencanaKontrol/ListRencanaKontrol/tglAwal/{$filters['tgl_awal']}/tglAkhir/{$filters['tgl_akhir']}/filter/1";
        // }
        //
        // $response = $client->request('GET', "URL_API_BPJS/{$endpoint}", [ ... headers ... ]);
        // $data = json_decode($response->getBody()->getContents(), true);
        // return $data['response']['list'] ?? [];
        // =================================================================================

        // Untuk tujuan pengembangan, kita kembalikan data DUMMY
        $dummyApiResponse = [
            [
                "noSuratKontrol" => "0126R0050925K001091",
                "jnsPelayanan" => "Rawat Inap",
                "namaJnsKontrol" => "Surat Kontrol",
                "tglRencanaKontrol" => "2025-09-22",
                "tglTerbitKontrol" => "2025-09-14",
                "namaDokter" => "RATIH EKA PUJASARI",
                "noKartu" => "0003779437094",
                "nama" => "BAYI NYONYA MIMAH",
                "noSepAsalKontrol" => "0126R0050925V001137",
                "namaPoliAsal" => "-",
                "namaPoliTujuan" => "ANAK",
                "tglSEP" => "2025-09-10",
            ],
            [
                "noSuratKontrol" => "0126R0050925K001092",
                "jnsPelayanan" => "Rawat Inap",
                "namaJnsKontrol" => "Surat Kontrol",
                "tglRencanaKontrol" => "2025-09-22",
                "tglTerbitKontrol" => "2025-09-14",
                "namaDokter" => "DINDADIKUSUMA",
                "noKartu" => "0000461065678",
                "nama" => "YASINTA SRI PRATIWI",
                "noSepAsalKontrol" => "0126R0050925V001319",
                "namaPoliAsal" => "-",
                "namaPoliTujuan" => "OBGYN",
                "tglSEP" => "2025-09-12",
            ],
            [
                "noSuratKontrol" => "0126R0050925K001096",
                "jnsPelayanan" => "Rawat Inap",
                "namaJnsKontrol" => "SPRI",
                "tglRencanaKontrol" => "2025-09-14",
                "tglTerbitKontrol" => "2025-09-14",
                "namaDokter" => "DINDADIKUSUMA",
                "noKartu" => "0003109209546",
                "nama" => "ADE VERA HERMAWATI",
                "noSepAsalKontrol" => "",
                "namaPoliAsal" => "-",
                "namaPoliTujuan" => "OBGYN",
                "tglSEP" => "",
            ],
        ];

        return $dummyApiResponse;
    }

    public function dataSuratKontrol()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.data-surat-kontrol');
    }

    public function listDataSuratKontrol(Request $request)
    {
        $filters = [
            'format_filter' => $request->input('filtnoka') ? '2' : '1', // 1=Tgl Entri/Kontrol, 2=No.Kartu
            'tgl_awal'  => $request->input('tgl_awal') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl_awal'))->format('Y-m-d') : null,
            'tgl_akhir' => $request->input('tgl_akhir') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl_akhir'))->format('Y-m-d') : null,
            'bulan'     => $request->input('bulan'),
            'tahun'     => $request->input('tahun'),
            'noka'      => $request->input('noka'),
        ];

        $apiData = BpjsDataSuratKontrol::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        $paginatedData = array_slice($apiData, $request->start, $request->length);

        $data = [];
        foreach ($paginatedData as $item) {
            $data[] = [
                'noSuratKontrol' => $item['noSuratKontrol'],
                'jnsPelayanan' => $item['jnsPelayanan'],
                'namaJnsKontrol' => $item['namaJnsKontrol'],
                'tglRencanaKontrol' => Carbon::parse($item['tglRencanaKontrol'])->format('d M Y'),
                'tglTerbitKontrol' => Carbon::parse($item['tglTerbitKontrol'])->format('d M Y'),
                'namaDokter' => $item['namaDokter'],
                'noKartu' => $item['noKartu'],
                'nama' => $item['nama'],
                'noSepAsalKontrol' => $item['noSepAsalKontrol'],
                'namaPoliAsal' => $item['namaPoliAsal'],
                'namaPoliTujuan' => $item['namaPoliTujuan'],
                'tglSEP' => $item['tglSEP'] ? Carbon::parse($item['tglSEP'])->format('d M Y') : '-',
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
