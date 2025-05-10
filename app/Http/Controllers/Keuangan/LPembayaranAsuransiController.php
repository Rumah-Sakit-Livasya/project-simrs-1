<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use App\models\User;
use Carbon\Carbon;

class LPembayaranAsuransiController extends Controller
{
    public function belumProsesInvoice(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter by insurance provider
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter by invoice number
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }


        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-belum-proses-invoice', compact('penjamins', 'query'));
    }
    public function prosesInvoice(Request $request)
    {
        $penjamins = Penjamin::all();

        // Ambil daftar tipe kunjungan yang unik dari tabel registrations
        $tipe_kunjungan_list = \App\Models\SIMRS\Registration::select('registration_type')
            ->distinct()
            ->whereNotNull('registration_type')
            ->get();

        // Query utama data konfirmasi
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Filter: Tanggal
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
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter: Penjamin
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter: No Invoice
        if ($request->filled('invoice')) {
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter: Tipe Kunjungan
        if ($request->filled('tipe_kunjungan')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_kunjungan);
            });
        }

        // Kirim ke view
        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-proses-invoice', [
            'penjamins' => $penjamins,
            'query' => $query->get(), // Jangan lupa panggil ->get()
            'tipe_kunjungan_list' => $tipe_kunjungan_list,
        ]);
    }


    public function umurPiutangPenjamin(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter by insurance provider
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter by invoice number
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-umur-piutang-penjamin', [
            'penjamins' => $penjamins,
            'query' => $query->get()

        ]);
    }

    public function pembayaranAsuransi(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter by insurance provider
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter by invoice number
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-pembayaran-asuransi', compact('penjamins', 'query'));
    }

    public function rekapPembayaranAsuransi(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter by insurance provider
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter by invoice number
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-rekap-pembayaran-asuransi', compact('penjamins', 'query'));
    }

    public function rekapLaporanPiutangPenjamin(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        // Filter by insurance provider
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter by invoice number
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        // Filter by registration number
        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.laporan.l-rekap-piutang-penjamin', compact('penjamins', 'query'));
    }

    public function printBelumProsesInvoice(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $hasFilters = false;

        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();
                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.belum-proses-invoice', [
            'penjamins' => $penjamins,
            'query' => $query->get(),
            'period_start' => $period_start,
            'period_end' => $period_end,
        ]);
    }

    public function printProsesInvoice(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

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
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->filled('invoice')) {
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->filled('no_registrasi')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        // 🔥 Tambahkan filter berdasarkan tipe_kunjungan
        if ($request->filled('tipe_kunjungan')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_kunjungan);
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.proses-invoice', [
            'penjamins' => $penjamins,
            'query' => $query->get(),
            'period_start' => $period_start,
            'period_end' => $period_end,
        ]);
    }


    public function printUmurPiutangPenjamin(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $hasFilters = false;

        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();
                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.umur-piutang-penjamin', [
            'penjamins' => $penjamins,
            'query' => $query->get(), // <- ini penting! baru bisa pakai isNotEmpty()
            'period_start' => $period_start,
            'period_end' => $period_end
        ]);
    }

    public function printPembayaranAsuransi(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $hasFilters = false;

        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();
                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.pembayaran-asuransi', compact(
            'penjamins',
            'query',
            'period_start',
            'period_end'
        ));
    }

    public function printRekapPembayaranAsuransi(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $hasFilters = false;

        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();
                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.rekap-pembayaran-asuransi', compact('penjamins', 'query', 'period_start', 'period_end'));
    }

    public function printRekapPiutangPenjamin(Request $request)
    {
        $period_start = $request->tanggal_awal;
        $period_end = $request->tanggal_akhir;
        $penjamins = Penjamin::all();
        $query = KonfirmasiAsuransi::with(['penjamin', 'registration', 'registration.patient']);

        $hasFilters = false;

        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;
            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();
                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format Y-m-d.']);
            }
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        if ($request->has('no_registrasi') && $request->no_registrasi != '') {
            $hasFilters = true;
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('no_registrasi', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        return view('app-type.keuangan.pembayaran-asuransi.print.rekap-piutang-penjamin', compact('penjamins', 'query', 'period_start', 'period_end'));
    }
}
