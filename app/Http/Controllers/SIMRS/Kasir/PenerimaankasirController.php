<?php

namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Penjamin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenerimaanKasirExport;

class PenerimaanKasirController extends Controller
{
    /**
     * Display the search form for the Cashier Receipt Report.
     */
    public function index()
    {
        $layanans = ['ALL', 'RAWAT JALAN', 'RAWAT INAP', 'IGD', 'FARMASI', 'LABORATORIUM', 'RADIOLOGI'];
        $polikliniks = Departement::orderBy('name')->get();
        $penjamins = Penjamin::orderBy('nama_perusahaan')->get();
        $kasirs = User::whereHas('roles', function ($query) {
            $query->where('name', 'Kasir');
        })->orderBy('name')->get();

        $periodeAwalInput = Carbon::now()->startOfDay()->format('Y-m-d H:i');
        $periodeAkhirInput = Carbon::now()->endOfDay()->format('Y-m-d H:i');

        return view('pages.simrs.keuangan.kasir.laporan.penerimaan-kasir.index', compact(
            'layanans',
            'polikliniks',
            'penjamins',
            'kasirs',
            'periodeAwalInput',
            'periodeAkhirInput'
        ));
    }

    /**
     * Generate the HTML report for the popup window.
     */
    public function report(Request $request)
    {
        $query = $this->buildReportQuery($request);
        $results = $query->get();

        // Mengelompokkan data berdasarkan kasir, lalu penjamin
        $groupedData = $results->groupBy('nama_kasir')->map(function ($kasirItems) {
            return $kasirItems->groupBy('nama_penjamin');
        });

        $jenisReport = $request->input('jenis_report', 'detail');
        $periodeAwal = Carbon::parse($request->input('periode_awal'))->format('d M Y H:i');
        $periodeAkhir = Carbon::parse($request->input('periode_akhir'))->format('d M Y H:i');

        return view('pages.simrs.keuangan.kasir.laporan.penerimaan-kasir.report', compact(
            'groupedData',
            'jenisReport',
            'periodeAwal',
            'periodeAkhir'
        ));
    }

    /**
     * Generate and download an Excel export of the report.
     */
    public function export(Request $request)
    {
        $query = $this->buildReportQuery($request);
        $data = $query->get();

        $jenisReport = $request->input('jenis_report', 'detail');
        $periodeAwal = Carbon::parse($request->input('periode_awal'))->format('d-m-Y');
        $periodeAkhir = Carbon::parse($request->input('periode_akhir'))->format('d-m-Y');

        $fileName = "Laporan_Penerimaan_Kasir_{$jenisReport}_{$periodeAwal}_sd_{$periodeAkhir}.xlsx";

        return Excel::download(new PenerimaanKasirExport($data, $jenisReport, $periodeAwal, $periodeAkhir), $fileName);
    }

    /**
     * Build the base query for the report based on request filters.
     * This method is reusable for both HTML report and Excel export.
     */
    private function buildReportQuery(Request $request)
    {
        // ===================================================================
        // === BAGIAN UTAMA YANG DIPERBAIKI ===
        // ===================================================================

        // 1. Nama tabel utama diubah menjadi 'pembayaran_tagihan'
        $query = DB::table('pembayaran_tagihan as p')
            // 2. Tambahkan JOIN ke 'bilingan' terlebih dahulu
            ->join('bilingan as b', 'p.bilingan_id', '=', 'b.id')
            // 3. Baru JOIN ke 'registrations' dari 'bilingan'
            ->join('registrations as r', 'b.registration_id', '=', 'r.id')
            // 4. Perbaiki nama tabel 'patients', 'users', 'departements', 'penjamins' agar konsisten
            ->join('patients as ps', 'r.patient_id', '=', 'ps.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('departements as d', 'r.departement_id', '=', 'd.id')
            ->leftJoin('penjamins as pj', 'r.penjamin_id', '=', 'pj.id')
            ->select(
                'p.created_at as tgl_bayar',
                'ps.medical_record_number as no_rm',
                'ps.name as nama_pasien',
                'p.no_transaksi as no_kwitansi', // Menggunakan no_transaksi
                'p.jumlah_terbayar as total_bayar', // Menggunakan jumlah_terbayar
                'u.name as nama_kasir',
                'd.name as nama_poli',
                'pj.nama_perusahaan as nama_penjamin'
            );

        if ($request->filled('periode_awal') && $request->filled('periode_akhir')) {
            $start = Carbon::parse($request->periode_awal)->format('Y-m-d H:i:s');
            $end = Carbon::parse($request->periode_akhir)->format('Y-m-d H:i:s');
            $query->whereBetween('p.created_at', [$start, $end]);
        }

        // Filter by Service Type (Layanan), kolom yang benar adalah 'registration_type'
        if ($request->filled('layanan') && $request->layanan != 'ALL') {
            // Mengubah 'RAWAT JALAN' menjadi 'rawat-jalan'
            $layananDbFormat = \Illuminate\Support\Str::slug($request->layanan);
            $query->where('r.registration_type', $layananDbFormat);
        }

        // Filter by Polyclinic (Departement)
        if ($request->filled('poliklinik')) {
            $query->where('r.departement_id', $request->poliklinik);
        }

        // Filter by Guarantor (Penjamin)
        if ($request->filled('penjamin')) {
            $query->where('r.penjamin_id', $request->penjamin);
        }

        // Filter by Cashier (Petugas Kasir)
        $selectedKasirs = $request->input('petugas_kasir', []);
        if (!empty($selectedKasirs) && !in_array('ALL', $selectedKasirs)) {
            $query->whereIn('p.user_id', $selectedKasirs);
        }

        $query->orderBy('p.created_at', 'asc');

        return $query;
    }
}
