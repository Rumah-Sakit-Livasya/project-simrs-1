<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Penjamin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function penerimaanKasir(Request $request)
    {
        // === 1. Ambil Data Master untuk Filter ===
        $layanans = ['ALL', 'RAWAT JALAN', 'RAWAT INAP', 'IGD', 'FARMASI', 'LABORATORIUM', 'RADIOLOGI'];
        $polikliniks = Departement::orderBy('name')->get();
        $penjamins = Penjamin::orderBy('nama_perusahaan')->get();
        // Ambil user yang memiliki role 'Kasir' atau sesuai kebutuhan
        $kasirs = User::whereHas('roles', function ($query) {
            $query->where('name', 'Kasir');
        })->orderBy('name')->get();
        // === 2. Siapkan Nilai Default untuk Filter ===
        // Periode Awal: Hari ini jam 00:00:00
        $periodeAwal = $request->input('periode_awal', Carbon::today()->format('Y-m-d H:i:s'));
        // Periode Akhir: Waktu saat ini
        $periodeAkhir = $request->input('periode_akhir', Carbon::now()->format('Y-m-d H:i:s'));

        // Konversi format untuk input datetime-local
        $periodeAwalInput = Carbon::parse($periodeAwal)->format('Y-m-d\TH:i');
        $periodeAkhirInput = Carbon::parse($periodeAkhir)->format('Y-m-d\TH:i');

        // === 3. Logika Query (jika form di-submit) ===
        $hasilLaporan = []; // Defaultnya array kosong

        // Hanya jalankan query jika ada request (misal, saat tombol "Cari" diklik)
        // Kita bisa cek dengan parameter apa saja, misal 'jenis_report'
        if ($request->has('jenis_report')) {
            // Tulis logika query Anda di sini
            // Contoh query sederhana (sesuaikan dengan struktur tabel pembayaran Anda)
            // Asumsi ada tabel 'pembayarans'

            // $query = DB::table('pembayarans as p')
            //     ->join('registrations as r', 'p.registration_id', '=', 'r.id')
            //     ->join('patients as ps', 'r.patient_id', '=', 'ps.id')
            //     ->join('users as u', 'p.user_id', '=', 'u.id')
            //     ->join('departements as d', 'r.departement_id', '=', 'd.id')
            //     ->join('penjamins as pj', 'r.penjamin_id', '=', 'pj.id')
            //     ->select(
            //         'p.created_at as tgl_bayar', 
            //         'ps.medical_record_number as no_rm',
            //         'ps.name as nama_pasien',
            //         'p.no_kwitansi',
            //         'p.total_bayar',
            //         'u.name as nama_kasir',
            //         'd.name as nama_poli',
            //         'pj.nama_perusahaan as nama_penjamin'
            //     )
            //     ->whereBetween('p.created_at', [$periodeAwal, $periodeAkhir]);

            // // Terapkan filter tambahan
            // if ($request->filled('layanan') && $request->layanan != 'ALL') {
            //     // Sesuaikan dengan kolom yang relevan, misal r.registration_type
            // }
            // if ($request->filled('poliklinik')) {
            //     $query->where('r.departement_id', $request->poliklinik);
            // }
            // if ($request->filled('penjamin')) {
            //     $query->where('r.penjamin_id', $request->penjamin);
            // }
            // if ($request->filled('petugas_kasir') && !in_array('ALL', $request->petugas_kasir)) {
            //     $query->whereIn('p.user_id', $request->petugas_kasir);
            // }

            // $hasilLaporan = $query->orderBy('p.created_at')->get();

            // Placeholder data untuk demonstrasi
            $hasilLaporan = collect([
                // Anda bisa mengisi ini dengan hasil query di atas
            ]);
        }


        // === 4. Kirim Semua Data ke View ===
        return view('pages.simrs.keuangan.kasir.laporan.penerimaan-kasir', compact(
            'layanans',
            'polikliniks',
            'penjamins',
            'kasirs',
            'periodeAwalInput',
            'periodeAkhirInput',
            'hasilLaporan'
        ));
    }




    public function penerimaanKasirReport(Request $request)
    {
        // Ambil data waktu periode
        $periodeAwal = Carbon::parse($request->input('periode_awal'))->startOfDay();
        $periodeAkhir = Carbon::parse($request->input('periode_akhir'))->endOfDay();

        // Inisialisasi query pembayaran dengan relasi-relasi yang diperlukan
        $query = \DB::table('pembayarans_tagihan as p')
            ->join('registrasis as r', 'p.registrasi_id', '=', 'r.id')
            ->join('pasiens as ps', 'r.pasien_id', '=', 'ps.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('departements as d', 'r.departement_id', '=', 'd.id')
            ->leftJoin('penjamins as pj', 'r.penjamin_id', '=', 'pj.id')
            ->select(
                'p.created_at as tgl_bayar',
                'ps.medical_record_number as no_rm',
                'ps.name as nama_pasien',
                'p.no_kwitansi',
                'p.total_bayar',
                'u.name as nama_kasir',
                'd.name as nama_poli',
                'pj.nama_perusahaan as nama_penjamin'
            )
            ->whereBetween('p.created_at', [$periodeAwal, $periodeAkhir]);

        // Terapkan filter tambahan
        if ($request->filled('layanan') && $request->layanan !== 'ALL') {
            // Sesuaikan filter jika filtering layanan diperlukan (contoh: r.registration_type)
            $query->where('r.registration_type', $request->input('layanan'));
        }
        if ($request->filled('poliklinik')) {
            $query->where('r.departement_id', $request->input('poliklinik'));
        }
        if ($request->filled('penjamin')) {
            $query->where('r.penjamin_id', $request->input('penjamin'));
        }
        if ($request->filled('petugas_kasir') && !in_array('ALL', $request->input('petugas_kasir', []))) {
            $query->whereIn('p.user_id', $request->input('petugas_kasir'));
        }

        $results = $query->orderBy('p.created_at')->get();

        // Data digrouping per kasir, lalu per penjamin
        $groupedData = $results->groupBy('nama_kasir')->map(function ($kasirItems) {
            return $kasirItems->groupBy('nama_penjamin');
        });

        $jenisReport = $request->input('jenis_report', 'detail');
        $periodeAwalFormatted = $periodeAwal->format('d M Y H:i');
        $periodeAkhirFormatted = $periodeAkhir->format('d M Y H:i');

        // Ambil nama kasir terpilih untuk header laporan
        $selectedKasirIds = $request->input('petugas_kasir', []);
        $namaKasirTerpilih = 'SEMUA KASIR';
        if (!empty($selectedKasirIds) && !in_array('ALL', $selectedKasirIds)) {
            $namaKasirTerpilih = User::whereIn('id', $selectedKasirIds)->pluck('name')->implode(', ');
        }

        return view('laporan.penerimaan-kasir-report', [
            'groupedData' => $groupedData,
            'jenisReport' => $jenisReport,
            'periodeAwal' => $periodeAwalFormatted,
            'periodeAkhir' => $periodeAkhirFormatted,
            'namaKasirTerpilih' => $namaKasirTerpilih,
        ]);
    }
}
