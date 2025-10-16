<?php

namespace App\Services\BPJS;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
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
        $this->baseUrl   = trim(config('bpjs.aplicare.base_url'));
    }

    /**
     * Membuat HTTP client terpusat dengan header otentikasi.
     */
    protected function httpClient(): PendingRequest
    {
        $timestamp = now()->timestamp;
        $signature = base64_encode(hash_hmac('sha256', "{$this->consId}&{$timestamp}", $this->secretKey, true));

        return Http::withHeaders([
            'X-Cons-ID'   => $this->consId,
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
            'user_key'    => $this->userKey,
            'Accept'      => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
        ])
            ->withoutVerifying()
            ->baseUrl($this->baseUrl)
            ->timeout(60);
    }

    /**
     * FUNGSI: Referensi Kamar
     * @return array|null
     */
    public function getReferensiKamar(): ?array
    {
        try {
            $response = $this->httpClient()->get('/ref/kelas');
            if ($response->failed()) {
                Log::error('Aplicare Get Referensi Kamar Gagal: ' . $response->body());
                return null;
            }
            return $response->json();
        } catch (ConnectionException $e) {
            Log::error('Aplicare Connection Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FUNGSI: Melihat Data Ketersediaan Kamar RS
     * @param int $start
     * @param int $limit
     * @return array|null
     */
    public function getKetersediaanKamar(int $start = 1, int $limit = 10): ?array
    {
        try {
            $response = $this->httpClient()->get("/bed/read/{$this->kodePPK}/{$start}/{$limit}");
            if ($response->failed()) {
                Log::error('Aplicare Get Ketersediaan Kamar Gagal: ' . $response->body());
                return null;
            }
            return $response->json();
        } catch (ConnectionException $e) {
            Log::error('Aplicare Connection Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FUNGSI: Insert Ruangan Baru
     * @param array $data
     * @return array
     */
    public function createRuangan(array $data): array
    {
        $response = $this->httpClient()->post("/bed/create/{$this->kodePPK}", $data);
        return $response->json();
    }

    /**
     * Update Ketersediaan Tempat Tidur di BPJS Aplicare.
     *
     * @param array{
     *     kodekelas: string,
     *     koderuang: string,
     *     namaruang: string,
     *     kapasitas: int|string,
     *     tersedia: int|string,
     *     tersediapria?: int|string,
     *     tersediawanita?: int|string,
     *     tersediapriawanita?: int|string
     * } $data
     * @return array
     */
    public function updateKetersediaanBed(array $data): array
    {
        // ================== PERUBAHAN 1: Buat Payload yang Kaku ==================
        // Pastikan SEMUA key ada, sesuai ekspektasi BPJS. Beri nilai default "0".
        $payload = [
            'kodekelas'           => (string)($data['kodekelas'] ?? ''),
            'koderuang'           => (string)($data['koderuang'] ?? ''),
            'namaruang'           => (string)($data['namaruang'] ?? ''),
            'kapasitas'           => (string)($data['kapasitas'] ?? '0'),
            'tersedia'            => (string)($data['tersedia'] ?? '0'),
            'tersediapria'        => (string)($data['tersediapria'] ?? '0'),
            'tersediawanita'      => (string)($data['tersediawanita'] ?? '0'),
            'tersediapriawanita'  => (string)($data['tersediapriawanita'] ?? '0'),
        ];

        try {
            // ================== PERUBAHAN 2: Paksa Pengiriman sebagai JSON ==================
            // Gunakan ->asJson() untuk secara otomatis set Content-Type dan format body.
            // Ini adalah cara paling benar di Laravel 8+.
            $response = $this->httpClient()->post("/bed/update/{$this->kodePPK}", $payload);

            if ($response->failed()) {
                // Log sudah bagus, tidak perlu diubah
                logger()->error('Aplicare updateKetersediaanBed gagal', [
                    'endpoint'        => $this->baseUrl . "/bed/update/{$this->kodePPK}",
                    'payload'         => $payload,
                    'response_status' => $response->status(),
                    'response_body'   => $response->body(),
                ]);
            }

            dd($payload);

            // Jika response body bukan json (seperti kasus error HTML), json() akan return null.
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Aplicare updateKetersediaanBed exception', [
                'endpoint'           => $this->baseUrl . "/bed/update/{$this->kodePPK}",
                'payload'            => $payload,
                'exception_message'  => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * FUNGSI: Hapus Ruangan
     * @param string $kodeKelas
     * @param string $kodeRuang
     * @return array
     */
    public function deleteRuangan(string $kodeKelas, string $kodeRuang): array
    {
        $payload = [
            'kodekelas' => (string) $kodeKelas,
            'koderuang' => (string) $kodeRuang,
        ];

        try {
            // Pastikan pengiriman dalam format JSON (Content-Type: application/json)
            $response = $this->httpClient()->post(
                "/bed/delete/{$this->kodePPK}",
                $payload
            );

            if ($response->failed()) {
                logger()->error('Aplicare deleteRuangan gagal', [
                    'endpoint'        => $this->baseUrl . "/bed/delete/{$this->kodePPK}",
                    'payload'         => $payload,
                    'response_status' => $response->status(),
                    'response_body'   => $response->body(),
                ]);
            }

            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Aplicare deleteRuangan exception', [
                'endpoint'           => $this->baseUrl . "/bed/delete/{$this->kodePPK}",
                'payload'            => $payload,
                'exception_message'  => $e->getMessage(),
            ]);
            return [];
        }
    }
}
