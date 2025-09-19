<?php

namespace App\Services\BPJS;

use Illuminate\Support\Facades\Http;

class ApplicareService
{
    protected $baseUrl;
    protected $consId;
    protected $secretKey;
    protected $kodePPK;

    public function __construct()
    {
        $this->baseUrl   = config('bpjs.applicare.base_url');
        $this->consId    = config('bpjs.cons_id');
        $this->secretKey = config('bpjs.secret_key');
        $this->kodePPK   = config('bpjs.kode_ppk');
    }

    /**
     * Membuat header otentikasi yang dibutuhkan oleh API BPJS.
     */
    private function generateHeaders(): array
    {
        $timestamp = now()->timestamp;
        $signature = base64_encode(hash_hmac('sha256', "{$this->consId}&{$timestamp}", $this->secretKey, true));

        return [
            'X-Cons-ID'   => $this->consId,
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
            'Accept'      => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * FUNGSI: Referensi Kamar
     * GET /ref/kelas
     */
    public function getReferensiKamar()
    {
        $url = "{$this->baseUrl}/ref/kelas";
        $response = Http::withHeaders($this->generateHeaders())->get($url);
        return $response->json();
    }

    /**
     * FUNGSI: Melihat Data Ketersediaan Kamar RS
     * GET /bed/read/{kodeppk}/{start}/{limit}
     */
    public function getKetersediaanKamar(int $start = 1, int $limit = 10)
    {
        $url = "{$this->baseUrl}/bed/read/{$this->kodePPK}/{$start}/{$limit}";
        $response = Http::withHeaders($this->generateHeaders())->get($url);
        return $response->json();
    }

    /**
     * FUNGSI: Insert Ruangan Baru
     * POST /bed/create/{kodeppk}
     */
    public function createRuangan(array $data)
    {
        // Contoh $data:
        // [
        //     "kodekelas" => "VIP",
        //     "koderuang" => "RG01",
        //     "namaruang" => "Ruang Anggrek VIP",
        //     "kapasitas" => "20",
        //     "tersedia" => "10",
        //     "tersediapria" => "0",
        //     "tersediawanita" => "0",
        //     "tersediapriawanita" => "0"
        // ]
        $url = "{$this->baseUrl}/bed/create/{$this->kodePPK}";
        $response = Http::withHeaders($this->generateHeaders())->post($url, $data);
        return $response->json();
    }

    /**
     * FUNGSI: Update Ketersediaan Tempat Tidur
     * POST /bed/update/{kodeppk}
     */
    public function updateKetersediaanBed(array $data)
    {
        // $data sama seperti createRuangan
        $url = "{$this->baseUrl}/bed/update/{$this->kodePPK}";
        $response = Http::withHeaders($this->generateHeaders())->post($url, $data);
        return $response->json();
    }

    /**
     * FUNGSI: Hapus Ruangan
     * POST /bed/delete/{kodeppk}
     */
    public function deleteRuangan(string $kodeKelas, string $kodeRuang)
    {
        $data = [
            "kodekelas" => $kodeKelas,
            "koderuang" => $kodeRuang
        ];
        $url = "{$this->baseUrl}/bed/delete/{$this->kodePPK}";
        $response = Http::withHeaders($this->generateHeaders())->post($url, ['faskes' => $data]); // BPJS kadang membungkus payload
        return $response->json();
    }
}
