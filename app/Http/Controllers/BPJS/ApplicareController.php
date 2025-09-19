<?php

namespace App\Http\Controllers\BPJS;

use App\Http\Controllers\Controller;
use App\Services\BPJS\ApplicareService;
use Illuminate\Http\Request;

class ApplicareController extends Controller
{
    protected $applicareService;

    // Gunakan dependency injection agar service otomatis tersedia
    public function __construct(ApplicareService $applicareService)
    {
        $this->applicareService = $applicareService;
    }

    /**
     * Contoh halaman untuk menampilkan daftar kamar
     */
    public function daftarKamar()
    {
        $kamar = $this->applicareService->getKetersediaanKamar(1, 100); // Ambil 100 kamar pertama

        // Cek jika response sukses
        if (isset($kamar['metadata']['code']) && $kamar['metadata']['code'] == 1) {
            $listKamar = $kamar['response']['list'];
        } else {
            $listKamar = [];
            // Handle error, mungkin tampilkan pesan dari $kamar['metadata']['message']
        }

        return view('pages.simrs.applicare.daftar-kamar', compact('listKamar'));
    }

    /**
     * Contoh fungsi untuk mengupdate data kamar
     */
    public function updateKamar(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'kodekelas' => 'required|string',
            'koderuang' => 'required|string',
            'namaruang' => 'required|string',
            'kapasitas' => 'required|integer',
            'tersedia' => 'required|integer',
        ]);

        // Kirim data ke BPJS
        $response = $this->applicareService->updateKetersediaanBed($validated);

        // Redirect kembali dengan pesan sukses atau error
        if (isset($response['metadata']['code']) && $response['metadata']['code'] == 1) {
            return back()->with('success', 'Data kamar berhasil diupdate ke BPJS.');
        } else {
            $errorMessage = $response['metadata']['message'] ?? 'Terjadi kesalahan.';
            return back()->with('error', 'Gagal update: ' . $errorMessage);
        }
    }
}
