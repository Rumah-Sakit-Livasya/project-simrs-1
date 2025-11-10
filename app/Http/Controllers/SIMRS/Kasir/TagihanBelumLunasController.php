<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TagihanBelumLunasController extends Controller
{
    public function index(Request $request)
    {
        $tipeKunjungan = ['All', 'RAWAT JALAN', 'RAWAT INAP', 'IGD'];
        $hasilLaporan = null;
        $tglBillingInput = $request->input(
            'tgl_billing',
            Carbon::now()->startOfMonth()->format('Y-m-d') . ' - ' . Carbon::now()->endOfMonth()->format('Y-m-d')
        );

        if ($request->has('action')) {

            $query = Bilingan::query()
                ->with([
                    'registration.patient',
                    'registration.departement',
                    'tagihanPasien.user'
                ])
                ->where('is_paid', 0);

            if ($request->filled('tgl_billing')) {
                $dates = explode(' - ', $request->tgl_billing);
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }

            // --- BAGIAN YANG DIPERBAIKI ---
            if ($request->filled('tipe_kunjungan') && $request->tipe_kunjungan != 'All') {
                $query->whereHas('registration', function ($q) use ($request) {
                    // 1. Mengubah input agar cocok dengan format database ('RAWAT JALAN' -> 'rawat-jalan')
                    $tipeKunjunganDbFormat = Str::slug($request->tipe_kunjungan);

                    // 2. Menggunakan nama kolom yang benar: 'registration_type'
                    $q->where('registration_type', $tipeKunjunganDbFormat);
                });
            }
            // --- AKHIR BAGIAN YANG DIPERBAIKI ---

            if ($request->filled('nama_pasien')) {
                $query->whereHas('registration.patient', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->nama_pasien . '%');
                });
            }

            if ($request->filled('no_rm')) {
                $query->whereHas('registration.patient', function ($q) use ($request) {
                    $q->where('medical_record_number', $request->no_rm);
                });
            }

            $hasilLaporan = $query->latest('created_at')->get();
        }

        return view('pages.simrs.keuangan.kasir.laporan.tagihan-belum-lunas.index', compact(
            'tipeKunjungan',
            'tglBillingInput',
            'hasilLaporan'
        ));
    }
}
