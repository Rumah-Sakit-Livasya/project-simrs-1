<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\DownPayment;
use App\Models\SIMRS\Penjamin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanSisaDpController extends Controller
{
    public function index()
    {
        // Data untuk form dropdowns
        $layanans = ['ALL', 'RAWAT JALAN', 'RAWAT INAP', 'IGD'];
        $polikliniks = Departement::orderBy('name')->get(); // <-- TAMBAHKAN INI
        $penjamins = Penjamin::orderBy('nama_perusahaan')->get(); // <-- TAMBAHKAN INI

        // Nilai default untuk form
        $sdTanggalInput = Carbon::now()->format('Y-m-d');

        return view('pages.simrs.keuangan.kasir.laporan.sisa-dp.index', compact(
            'layanans',
            'polikliniks', // <-- TAMBAHKAN INI
            'penjamins', // <-- TAMBAHKAN INI
            'sdTanggalInput'
        ));
    }

    /**
     * Membuat laporan untuk ditampilkan di popup window.
     */
    public function report(Request $request)
    {
        // --- LOGIKA QUERY SEBENARNYA ---
        $query = DownPayment::query()
            // Eager load relasi yang dibutuhkan untuk efisiensi
            ->with([
                'bilingan.registration.patient',
                'bilingan.registration.departement',
            ])
            // CORE LOGIC: Hanya ambil DP yang Bilingan-nya BELUM LUNAS
            ->whereHas('bilingan', function ($q_bil) {
                $q_bil->where('is_paid', 0);
            });

        // Terapkan filter berdasarkan tanggal input "Sd Tanggal"
        if ($request->filled('sd_tanggal')) {
            $endDate = Carbon::parse($request->sd_tanggal)->endOfDay();
            // Filter DP yang dibuat pada atau sebelum tanggal yang dipilih
            $query->where('created_at', '<=', $endDate);
        }

        // Terapkan filter berdasarkan tipe layanan/kunjungan
        if ($request->filled('layanan') && $request->layanan != 'ALL') {
            $query->whereHas('bilingan.registration', function ($q_reg) use ($request) {
                $q_reg->where('type', $request->layanan);
            });
        }

        $hasilLaporan = $query->latest('created_at')->get();

        // Kirim filter ke view untuk ditampilkan di header
        $filters = $request->all();

        return view('pages.simrs.keuangan.kasir.laporan.sisa-dp.report', compact('hasilLaporan', 'filters'));
    }
}
