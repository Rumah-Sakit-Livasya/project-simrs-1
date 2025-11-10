<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\TagihanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BiayaLainController extends Controller
{
    public function index()
    {
        // Data untuk dropdown filter
        $tipeKunjungan = ['All', 'RAWAT JALAN', 'RAWAT INAP', 'IGD'];
        $statusKunjungan = [
            'All' => 'Semua',
            'tutup_kunjungan' => 'Telah Tutup Kunjungan',
            'aktif' => 'Masih Dirawat'
        ];

        // Nilai default untuk form
        $periodeAwalInput = Carbon::now()->startOfMonth()->format('Y-m-d');
        $periodeAkhirInput = Carbon::now()->endOfMonth()->format('Y-m-d');

        return view('pages.simrs.keuangan.kasir.laporan.biaya-lain.index', compact(
            'tipeKunjungan',
            'statusKunjungan',
            'periodeAwalInput',
            'periodeAkhirInput'
        ));
    }

    /**
     * Menghasilkan laporan untuk popup window.
     */
    public function report(Request $request)
    {
        // --- LOGIKA QUERY SEBENARNYA ---
        $query = TagihanPasien::query()
            ->with([
                'registration.patient',
                'registration.departement',
                'registration.penjamin',
                'user'
            ])
            ->whereNotIn('type', ['Tindakan Medis', 'Farmasi', 'Laboratorium', 'Radiologi']);

        $query->whereHas('registration', function ($q_reg) use ($request) {
            $start = Carbon::parse($request->periode_awal)->startOfDay();
            $end = Carbon::parse($request->periode_akhir)->endOfDay();
            $q_reg->whereBetween('created_at', [$start, $end]);

            if ($request->filled('tipe_kunjungan') && $request->tipe_kunjungan != 'All') {
                $q_reg->where('type', $request->tipe_kunjungan);
            }
            if ($request->filled('status_kunjungan') && $request->status_kunjungan != 'All') {
                $q_reg->where('status', $request->status_kunjungan);
            }
            if ($request->filled('no_registrasi')) {
                $q_reg->where('registration_number', $request->no_registrasi);
            }
        });

        $query->whereHas('registration.patient', function ($q_px) use ($request) {
            if ($request->filled('no_rm')) {
                $q_px->where('medical_record_number', $request->no_rm);
            }
            if ($request->filled('nama_pasien')) {
                $q_px->where('name', 'LIKE', '%' . $request->nama_pasien . '%');
            }
        });

        $hasilLaporan = $query->latest('date')->get();

        // Kirim filter ke view untuk ditampilkan di header
        $filters = $request->all();

        return view('pages.simrs.keuangan.kasir.laporan.biaya-lain.report', compact('hasilLaporan', 'filters'));
    }
}
