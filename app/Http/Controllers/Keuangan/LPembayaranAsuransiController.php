<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\Keuangan\PembayaranAsuransi;
use App\Models\Keuangan\PembayaranAsuransiDetail;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use App\models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LPembayaranAsuransiController extends Controller
{
    /**
     * Common function to apply filters to query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, Request $request)
    {
        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Log error but continue
                \Log::error('Format tanggal tidak valid: ' . $e->getMessage());
            }
        }

        // Filter by insurance provider
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->filled('departement_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('departement_id', $request->departement_id);
            });
        }

        // Filter by invoice number
        if ($request->filled('invoice')) {
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->filled('no_registrasi')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        // Filter by patient name if provided
        if ($request->filled('nama_pasien')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_pasien . '%');
            });
        }

        // Filter by visit type if provided
        if ($request->filled('tipe_kunjungan')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_kunjungan);
            });
        }

        return $query;
    }

    /**
     * View report for unprocessed invoices
     */
    public function belumProsesInvoice(Request $request)
    {
        $penjamins = Penjamin::all();
        $departments = Departement::all(); // Fetch all departments

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Belum Di Buat Tagihan');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-belum-proses-invoice', [
            'penjamins' => $penjamins,
            'departments' => $departments, // Pass departments to the view
            'query' => $data,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'departement_id', 'invoice', 'no_registrasi', 'nama_pasien'])
        ]);
    }

    /**
     * View report for processed invoices
     */
    public function prosesInvoice(Request $request)
    {
        $penjamins = Penjamin::all();

        // Get unique visit types
        $tipe_kunjungan_list = Registration::select('registration_type')
            ->distinct()
            ->whereNotNull('registration_type')
            ->get();

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Sudah Di Buat Tagihan');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-proses-invoice', [
            'penjamins' => $penjamins,
            'query' => $data,
            'tipe_kunjungan_list' => $tipe_kunjungan_list,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'invoice', 'no_registrasi', 'nama_pasien', 'tipe_kunjungan'])
        ]);
    }

    /**
     * View report for insurance receivables aging
     */
    public function umurPiutangPenjamin(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Sudah Di Buat Tagihan')
            ->whereNotNull('jatuh_tempo');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-umur-piutang-penjamin', [
            'penjamins' => $penjamins,
            'query' => $data,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'invoice', 'no_registrasi', 'nama_pasien'])
        ]);
    }

    /**
     * View report for insurance payments
     */
    public function pembayaranAsuransi(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-pembayaran-asuransi', [
            'penjamins' => $penjamins,
            'query' => $data,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'invoice', 'no_registrasi', 'nama_pasien'])
        ]);
    }

    /**
     * View report for insurance payment summary
     */
    public function rekapPembayaranAsuransi(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-rekap-pembayaran-asuransi', [
            'penjamins' => $penjamins,
            'query' => $data,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'invoice', 'no_registrasi', 'nama_pasien'])
        ]);
    }

    /**
     * View report for insurance receivables summary by provider
     */
    public function rekapLaporanPiutangPenjamin(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-rekap-piutang-penjamin', [
            'penjamins' => $penjamins,
            'query' => $data,
            'hasFilters' => $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'penjamin_id', 'invoice', 'no_registrasi', 'nama_pasien'])
        ]);
    }

    /**
     * Print report for unprocessed invoices
     */
    public function printBelumProsesInvoice(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Belum Di Buat Tagihan');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        // Get provider name if filtered
        $penjamin = null;
        if ($request->filled('penjamin_id')) {
            $penjamin = Penjamin::find($request->penjamin_id);
        }

        // Get current user for report footer
        $user = Auth::user();

        return view('app-type.keuangan.pembayaran-asuransi.print.belum-proses-invoice', [
            'data' => $data,
            'penjamin' => $penjamin,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'total' => $data->sum('jumlah'),
            'user' => $user,
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }

    /**
     * Print report for processed invoices
     */
    public function printProsesInvoice(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient'])
            ->where('status', 'Sudah Di Buat Tagihan');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        // Get provider name if filtered
        $penjamin = null;
        if ($request->filled('penjamin_id')) {
            $penjamin = Penjamin::find($request->penjamin_id);
        }

        // Get visit type if filtered
        $tipe_kunjungan = null;
        if ($request->filled('tipe_kunjungan')) {
            $tipe_kunjungan = $request->tipe_kunjungan;
        }

        // Get current user for report footer
        $user = Auth::user();

        return view('app-type.keuangan.pembayaran-asuransi.print.proses-invoice', [
            'data' => $data,
            'penjamin' => $penjamin,
            'tipe_kunjungan' => $tipe_kunjungan,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'total' => $data->sum('jumlah'),
            'user' => $user,
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }

    /**
     * Print report for insurance receivables aging
     */
    public function printUmurPiutangPenjamin(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;

        $query = KonfirmasiAsuransi::with([
            'penjamin',
            'registration',
            'registration.patient',
            'pembayaran'
        ])
            ->where('status', 'Sudah Di Buat Tagihan')
            ->whereNotNull('jatuh_tempo');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        // Initialize period totals
        $periodTotals = [
            'tagihan' => 0,
            'bayar' => 0,
            'sisa' => 0,
            'umur_30' => 0,
            'umur_60' => 0,
            'umur_90' => 0,
            'umur_over' => 0
        ];

        foreach ($data as $item) {
            // Hitung jumlah bayar
            $item->jumlah_bayar = $item->pembayaran ? $item->pembayaran->jumlah : 0;

            // Hitung sisa tagihan
            $item->sisa_tagihan = $item->jumlah - $item->jumlah_bayar;

            // Update period totals
            $periodTotals['tagihan'] += $item->jumlah;
            $periodTotals['bayar'] += $item->jumlah_bayar;
            $periodTotals['sisa'] += $item->sisa_tagihan;

            // Hitung umur tagihan: jika minus, belum jatuh tempo. jika plus, sudah jatuh tempo
            if ($item->jatuh_tempo) {
                $jatuhTempo = Carbon::parse($item->jatuh_tempo);
                $now = Carbon::now();
                $item->umur_tagihan = $now->diffInDays($jatuhTempo, false);
            } else {
                $item->umur_tagihan = null;
            }

            // Inisialisasi kategori umur
            $item->umur_30 = 0;
            $item->umur_60 = 0;
            $item->umur_90 = 0;
            $item->umur_over = 0;

            // Klasifikasi ke kategori umur sesuai header tabel
            if ($item->umur_tagihan !== null) {
                if ($item->umur_tagihan >= 0) {
                    // Belum jatuh tempo atau jatuh tempo hari ini → masuk ke ≤ 30 Hari
                    $item->umur_30 = $item->sisa_tagihan;
                    $periodTotals['umur_30'] += $item->sisa_tagihan;
                } else {
                    // Sudah jatuh tempo (nilai negatif berarti sudah lewat)
                    $daysOverdue = abs($item->umur_tagihan);

                    if ($daysOverdue <= 30) {
                        // <= 30 hari jatuh tempo
                        $item->umur_30 = $item->sisa_tagihan;
                        $periodTotals['umur_30'] += $item->sisa_tagihan;
                    } elseif ($daysOverdue <= 60) {
                        // 31-60 hari jatuh tempo
                        $item->umur_60 = $item->sisa_tagihan;
                        $periodTotals['umur_60'] += $item->sisa_tagihan;
                    } elseif ($daysOverdue <= 90) {
                        // 61-90 hari jatuh tempo
                        $item->umur_90 = $item->sisa_tagihan;
                        $periodTotals['umur_90'] += $item->sisa_tagihan;
                    } else {
                        // > 90 hari jatuh tempo
                        $item->umur_over = $item->sisa_tagihan;
                        $periodTotals['umur_over'] += $item->sisa_tagihan;
                    }
                }
            }
        }

        // Ambil nama penjamin jika difilter
        $penjamin = $request->filled('penjamin_id')
            ? Penjamin::find($request->penjamin_id)
            : null;

        return view('app-type.keuangan.pembayaran-asuransi.print.umur-piutang-penjamin', [
            'data' => $data,
            'penjamin' => $penjamin,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'periodTotals' => $periodTotals,
            'user' => Auth::user(),
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }





    /**
     * Print report for insurance payments
     */
    public function printPembayaranAsuransi(Request $request)
    {
        $query = PembayaranAsuransi::with([
            'penjamin',
            'bank',
            'details.konfirmasiAsuransi'
        ])->where('status', 'completed');

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        $penjamin = $request->filled('penjamin_id')
            ? Penjamin::find($request->penjamin_id)
            : null;

        return view('app-type.keuangan.pembayaran-asuransi.print.pembayaran-asuransi', [
            'data' => $data,
            'penjamin' => $penjamin,
            'period_start' => $request->tanggal_awal,
            'period_end' => $request->tanggal_akhir,
            'user' => Auth::user(),
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }


    /**
     * Print report for insurance payment summary
     */
    public function printRekapPembayaranAsuransi(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;

        // For summary report, we need both konfirmasi and payment data
        $query = KonfirmasiAsuransi::with([
            'penjamin',
            'registration',
            'registration.patient',
            'pembayaran' // Include the pembayaran relationship
        ]);

        $query = $this->applyFilters($query, $request);
        $data = $query->get();

        // For each konfirmasi, get the jumlah from associated pembayaran
        foreach ($data as $item) {
            if ($item->pembayaran) {
                // Use the jumlah from pembayaran_asuransi directly
                $item->jumlah_bayar = $item->pembayaran->jumlah;
            } else {
                $item->jumlah_bayar = 0;
            }
        }

        // Get provider name if filtered
        $penjamin = null;
        if ($request->filled('penjamin_id')) {
            $penjamin = Penjamin::find($request->penjamin_id);
        }

        // Group data by insurance provider for summary
        $grouped_data = $data->groupBy('penjamin_id');
        $summary = [];

        foreach ($grouped_data as $penjamin_id => $items) {
            $penjamin_name = $items->first()->penjamin->name ?? 'Unknown';
            $summary[] = [
                'penjamin_id' => $penjamin_id,
                'penjamin_name' => $penjamin_name,
                'count' => $items->count(),
                'total' => $items->sum('jumlah'),
                'discount' => $items->sum('discount'),
                'jumlah_bayar' => $items->sum('jumlah_bayar') // Add this line
            ];
        }

        // Get current user for report footer
        $user = Auth::user();

        return view('app-type.keuangan.pembayaran-asuransi.print.rekap-pembayaran-asuransi', [
            'data' => $data,
            'summary' => $summary,
            'penjamin' => $penjamin,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'total' => $data->sum('jumlah'),
            'total_discount' => $data->sum('discount'),
            'grand_total' => $data->sum('jumlah') - $data->sum('discount'),
            'user' => $user,
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }
    /**
     * Print report for insurance receivables summary by provider
     */
    public function printRekapPiutangPenjamin(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $penjamins = Penjamin::query();
        if ($request->filled('penjamin_id')) {
            $penjamins->where('id', $request->penjamin_id);
        }

        $penjamins = $penjamins->get();
        $rekap = [];

        foreach ($penjamins as $penjamin) {
            $dataPerBulan = [];

            // Hitung saldo awal dari tahun sebelumnya
            $startOfYear = Carbon::createFromDate($tahun, 1, 1)->startOfYear();

            $tagihanSebelum = KonfirmasiAsuransi::where('penjamin_id', $penjamin->id)
                ->where('status', 'Sudah Di Buat Tagihan')
                ->where('tanggal', '<', $startOfYear)
                ->sum('jumlah');

            $pembayaranSebelum = PembayaranAsuransi::where('penjamin_id', $penjamin->id)
                ->where('status', 'completed')
                ->where('tanggal', '<', $startOfYear)
                ->sum('jumlah');

            $saldoAwal = $tagihanSebelum - $pembayaranSebelum;

            for ($bulan = 1; $bulan <= 12; $bulan++) {
                // Lewati bulan mendatang jika tahun sama dengan tahun saat ini
                if ($tahun == $currentYear && $bulan > $currentMonth) {
                    $dataPerBulan[$bulan] = [
                        'saldo_awal' => null,
                        'piutang' => null,
                        'pembayaran' => null,
                        'saldo_akhir' => null,
                    ];
                    continue;
                }

                $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $end = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

                $tagihan = KonfirmasiAsuransi::where('penjamin_id', $penjamin->id)
                    ->where('status', 'Sudah Di Buat Tagihan')
                    ->whereBetween('tanggal', [$start, $end])
                    ->sum('jumlah');

                $pembayaran = PembayaranAsuransi::where('penjamin_id', $penjamin->id)
                    ->where('status', 'completed')
                    ->whereBetween('tanggal', [$start, $end])
                    ->sum('jumlah');

                $saldoAkhir = $saldoAwal + $tagihan - $pembayaran;

                $dataPerBulan[$bulan] = [
                    'saldo_awal' => $saldoAwal,
                    'piutang' => $tagihan,
                    'pembayaran' => $pembayaran,
                    'saldo_akhir' => $saldoAkhir,
                ];

                $saldoAwal = $saldoAkhir;
            }

            $rekap[$penjamin->nama_perusahaan] = [
                'saldo_awal' => $dataPerBulan[1]['saldo_awal'],
                'detail' => $dataPerBulan,
                'saldo_akhir' => $dataPerBulan[12]['saldo_akhir'],
            ];
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.rekap-piutang-penjamin', [
            'rekap' => $rekap,
            'tahun' => $tahun,
            'user' => Auth::user(),
            'print_date' => Carbon::now()->format('d/m/Y H:i:s')
        ]);
    }
}
