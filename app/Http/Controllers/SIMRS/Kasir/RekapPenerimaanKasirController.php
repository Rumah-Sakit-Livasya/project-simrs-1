<?php


namespace App\Http\Controllers\SIMRS\Kasir;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\PembayaranTagihan; // Ganti dengan path model Anda
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RekapPenerimaanKasirController extends Controller
{
    /**
     * Display the search form for the Rekap report.
     */
    public function index()
    {
        $periodeAwalInput = Carbon::now()->startOfDay()->format('Y-m-d H:i');
        $periodeAkhirInput = Carbon::now()->endOfDay()->format('Y-m-d H:i');

        return view('pages.simrs.keuangan.kasir.laporan.rekap-penerimaan-kasir.index', compact(
            'periodeAwalInput',
            'periodeAkhirInput'
        ));
    }

    /**
     * Generate the HTML report for the popup window.
     */
    public function report(Request $request)
    {
        $paymentMethods = [
            'CASH',
            'Mandiri',
            'BCA',
            'BNI',
            'Transfer',
            'ASURANSI',
            'BPJS'
        ];

        // Membangun dan mengeksekusi query menggunakan Eloquent
        $query = $this->buildRekapQueryEloquent($request, $paymentMethods);
        $hasilLaporan = $query->get();

        $totals = $this->calculateTotals($hasilLaporan, $paymentMethods);

        $periodeAwal = Carbon::parse($request->input('periode_awal'))->format('d-m-Y H:i');
        $periodeAkhir = Carbon::parse($request->input('periode_akhir'))->format('d-m-Y H:i');

        return view('pages.simrs.keuangan.kasir.laporan.rekap-penerimaan-kasir.report', compact(
            'hasilLaporan',
            'totals',
            'paymentMethods',
            'periodeAwal',
            'periodeAkhir'
        ));
    }

    /**
     * Membangun query pivot menggunakan Eloquent.
     */
    private function buildRekapQueryEloquent(Request $request, array $paymentMethods)
    {
        // ===================================================================
        // === BAGIAN UTAMA YANG DIUBAH ===
        // ===================================================================

        // 1. Mulai dari Model PembayaranTagihan
        $query = PembayaranTagihan::query();

        // 2. Buat klausa SELECT secara dinamis
        $selects = [
            // Menggunakan relasi untuk mendapatkan tipe registrasi.
            // Klausa 'registrations.registration_type' secara otomatis akan membuat JOIN yang diperlukan.
            DB::raw("COALESCE(registrations.registration_type, 'NON PASIEN') as revenue_center")
        ];

        foreach ($paymentMethods as $method) {
            $alias = 'total_' . strtolower(str_replace(' ', '_', $method));
            // Menggunakan relasi untuk mendapatkan nama penjamin
            $selects[] = DB::raw("SUM(IF(penjamins.nama_perusahaan = '{$method}', pembayaran_tagihan.jumlah_terbayar, 0)) as `{$alias}`");
        }
        $selects[] = DB::raw('SUM(pembayaran_tagihan.jumlah_terbayar) as subtotal');

        // 3. Terapkan klausa SELECT
        $query->select($selects);

        // 4. Lakukan JOIN eksplisit melalui relasi. Ini lebih jelas daripada JOIN manual.
        // Eloquent akan otomatis memberi alias yang benar jika diperlukan.
        $query->join('bilingan', 'pembayaran_tagihan.bilingan_id', '=', 'bilingan.id');
        $query->join('registrations', 'bilingan.registration_id', '=', 'registrations.id');
        $query->leftJoin('penjamins', 'registrations.penjamin_id', '=', 'penjamins.id');


        // 5. Terapkan filter tanggal PADA TABEL UTAMA
        if ($request->filled('periode_awal') && $request->filled('periode_akhir')) {
            $start = Carbon::parse($request->periode_awal)->format('Y-m-d H:i:s');
            $end = Carbon::parse($request->periode_akhir)->format('Y-m-d H:i:s');
            // Menentukan tabel secara eksplisit untuk menghindari ambiguitas
            $query->whereBetween('pembayaran_tagihan.created_at', [$start, $end]);
        }

        // 6. Kelompokkan dan urutkan
        $query->groupBy('revenue_center')->orderBy('revenue_center');

        return $query;
    }

    /**
     * Menghitung total untuk footer laporan.
     */
    private function calculateTotals($collection, array $paymentMethods)
    {
        $totals = [];
        foreach ($paymentMethods as $method) {
            $alias = 'total_' . strtolower(str_replace(' ', '_', $method));
            $totals[$alias] = $collection->sum($alias);
        }
        $totals['grand_total'] = $collection->sum('subtotal');
        return (object) $totals;
    }
}
