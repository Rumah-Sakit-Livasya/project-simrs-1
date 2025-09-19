<?php

namespace App\Services\BPJS;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest; // <-- Import ini
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApplicareService
{
    protected $consId;
    protected $secretKey;
    protected $userKey;
    protected $kodePPK;
    protected $baseUrl;

    public function __construct()
    {
        $this->consId    = trim(config('bpjs.cons_id'));
        $this->secretKey = trim(config('bpjs.secret_key'));
        $this->userKey   = trim(config('bpjs.user_key'));
        $this->kodePPK   = trim(config('bpjs.kode_ppk'));
        $this->baseUrl   = trim(config('bpjs.applicare.base_url'));
    }

    /**
     * 1. Buat satu method terpusat untuk client HTTP.
     * Method ini akan menyiapkan base URL dan semua header otentikasi.
     */
    protected function httpClient(): PendingRequest
    {
        $timestamp = now()->timestamp;
        $signature = base64_encode(hash_hmac('sha256', "{$this->consId}&{$timestamp}", $this->secretKey, true));


        return Http::withHeaders([
            'X-Cons-ID'   => $this->consId,
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
            'user_key'    => $this->userKey, // PERBAIKAN 1: Tambahkan header user_key
            'Accept'      => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
        ])
            ->withoutVerifying() // PERBAIKAN 2: Matikan verifikasi SSL (sama seperti CURLOPT_SSL_VERIFYPEER, false)
            ->baseUrl($this->baseUrl);
    }

    /**
     * FUNGSI: Referensi Kamar
     */
    public function getReferensiKamar()
    {
        try {
            $response = $this->httpClient()->get('/ref/kelas');

            if ($response->failed()) {
                Log::error('Applicare Get Referensi Kamar Gagal: ' . $response->body());
                return null;
            }
            return $response->json();
        } catch (ConnectionException $e) {
            Log::error('Applicare Connection Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FUNGSI: Melihat Data Ketersediaan Kamar RS
     */
    public function getKetersediaanKamar(int $start = 1, int $limit = 10)
    {
        $response = $this->httpClient()->get("/bed/read/{$this->kodePPK}/{$start}/{$limit}");
        return $response->json();
    }

    /**
     * FUNGSI: Insert Ruangan Baru
     */
    public function createRuangan(array $data)
    {
        $response = $this->httpClient()->post("/bed/create/{$this->kodePPK}", $data);
        return $response->json();
    }

    /**
     * FUNGSI: Update Ketersediaan Tempat Tidur
     */
    public function updateKetersediaanBed(array $data)
    {
        $response = $this->httpClient()->post("/bed/update/{$this->kodePPK}", $data);
        return $response->json();
    }

    /**
     * FUNGSI: Hapus Ruangan
     */
    public function deleteRuangan(string $kodeKelas, string $kodeRuang)
    {
        $data = ["kodekelas" => $kodeKelas, "koderuang" => $kodeRuang];
        // Dokumentasi BPJS terkadang tidak konsisten, beberapa endpoint membungkus payload
        // Jika gagal, coba tanpa dibungkus: $this->httpClient()->post(..., $data);
        $response = $this->httpClient()->post("/bed/delete/{$this->kodePPK}", ['request' => ['data' => $data]]);
        return $response->json();
    }
}
