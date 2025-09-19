<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
// Import model-model Anda, contoh:
// use App\Models\Antrean;
// use App\Models\Poli;
// use App\Models\Dokter;
// use App\Models\JadwalDokter;
// use App\Models\Pasien;

class MjknAntreanController extends Controller
{
    // Helper untuk respons sukses
    private function sendSuccess($data, $message = "Ok")
    {
        return response()->json([
            'response' => $data,
            'metadata' => ['message' => $message, 'code' => 200]
        ]);
    }

    // Helper untuk respons error
    private function sendError($message, $code = 201)
    {
        return response()->json([
            'metadata' => ['message' => $message, 'code' => $code]
        ]);
    }

    /**
     * 1. Token
     */
    public function generateToken()
    {
        $newToken = Str::random(64);
        $validity = config('mjkn.token_validity_minutes');

        // Simpan token di cache
        Cache::put('mjkn_token_' . $newToken, true, now()->addMinutes($validity));

        return $this->sendSuccess(['token' => $newToken]);
    }

    /**
     * 2. Status Antrean (Menggunakan Model Registration)
     */
    public function statusAntrean(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kodepoli' => 'required|string',
            'kodedokter' => 'required',
            'tanggalperiksa' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            $kodePoliBpjs = $request->kodepoli;
            $kodeDokterBpjs = $request->kodedokter;
            $tanggalPeriksa = $request->tanggalperiksa;

            $poli = Departement::where('kode_poli', $kodePoliBpjs)->first();
            $dokter = Doctor::where('kode_dpjp', $kodeDokterBpjs)->first();

            if (!$poli || !$dokter) {
                return $this->sendError("Poli atau Dokter tidak ditemukan.");
            }

            // PENYESUAIAN: Query ke tabel pendaftaran (Registration)
            $registrationQuery = Registration::where('departement_id', $poli->id)
                ->where('doctor_id', $dokter->id)
                ->whereDate('registration_date', $tanggalPeriksa); // Sesuaikan 'registration_date'

            // Hitung metrik dari data pendaftaran
            $totalAntrean = (clone $registrationQuery)->count();

            // Asumsi status 'DIPANGGIL' atau 'DALAM PEMERIKSAAN'
            $antreanPanggil = (clone $registrationQuery)->where('status_panggilan', 'calling')->orderBy('no_urut', 'asc')->first();

            // Asumsi status 'MENUNGGU' dan 'DIPANGGIL' dihitung sebagai sisa antrean
            $sisaAntrean = (clone $registrationQuery)->whereIn('status_panggilan', ['waiting', 'calling'])->count();

            // Logika kuota tetap sama
            $jadwal = JadwalDokter::where('doctor_id', $dokter->id)
                ->where('hari', date('N', strtotime($tanggalPeriksa)))
                ->first();

            $kuotaJkn = $jadwal->kuota_jkn ?? 30;
            $kuotaNonJkn = $jadwal->kuota_nonjkn ?? 30;

            // Sesuaikan 'jenis_pasien' dengan nama kolom di tabel Registration
            $terpakaiJkn = (clone $registrationQuery)->where('jenis_pasien', 'JKN')->count();
            $terpakaiNonJkn = (clone $registrationQuery)->where('jenis_pasien', 'NON-JKN')->count();

            $data = [
                "namapoli" => $poli->name,
                "namadokter" => $dokter->employee->fullname,
                "totalantrean" => $totalAntrean,
                "sisaantrean" => $sisaAntrean,
                "antreanpanggil" => $antreanPanggil ? $antreanPanggil->nomor_antrean : "Belum ada",
                "sisakuotajkn" => max(0, $kuotaJkn - $terpakaiJkn),
                "kuotajkn" => $kuotaJkn,
                "sisakuotanonjkn" => max(0, $kuotaNonJkn - $terpakaiNonJkn),
                "kuotanonjkn" => $kuotaNonJkn,
                "keterangan" => "Pasien diharapkan hadir 30 menit sebelum jadwal."
            ];

            return $this->sendSuccess($data);
        } catch (\Exception $e) {
            Log::error('Error di MJKN Status Antrean: ' . $e->getMessage());
            return $this->sendError("Terjadi kesalahan di server kami.");
        }
    }

    /**
     * 3. Ambil Antrean
     */
    public function ambilAntrean(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required',
            'nohp' => 'required',
            'kodepoli' => 'required',
            'norm' => 'nullable', // Boleh kosong untuk pasien baru
            'tanggalperiksa' => 'required|date',
            'kodedokter' => 'required',
            'jeniskunjungan' => 'required|in:1,2,3,4',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        // Cek apakah ini pasien baru (norm tidak ada/tidak ditemukan)
        // $pasien = Pasien::where('norm', $request->norm)->first();
        // if (!$pasien) {
        //     return $this->sendError("Pasien baru, harap mendaftar", 202);
        // }

        // TODO: Implementasikan logika untuk membuat antrean baru di database Anda

        // Contoh data respons (hardcoded)
        $data = [
            "nomorantrean" => "A-12",
            "angkaantrean" => 12,
            "kodebooking" => "16032021A001",
            "norm" => $request->norm ?? "123345",
            "namapoli" => "Anak",
            "namadokter" => "Dr. Hendra",
            "estimasidilayani" => now()->addMinutes(30)->valueOf(), // now() + estimasi dalam milidetik
            "sisakuotajkn" => 5,
            "kuotajkn" => 30,
            "sisakuotanonjkn" => 5,
            "kuotanonjkn" => 30,
            "keterangan" => "Peserta harap 60 menit lebih awal."
        ];

        return $this->sendSuccess($data);
    }

    // Implementasikan fungsi-fungsi lain dengan pola yang sama:
    // public function sisaAntrean(Request $request) { ... }
    // public function batalAntrean(Request $request) { ... }
    // public function checkIn(Request $request) { ... }
    // public function infoPasienBaru(Request $request) { ... }
    // public function jadwalOperasiRs(Request $request) { ... }
    // public function jadwalOperasiPasien(Request $request) { ... }
}
