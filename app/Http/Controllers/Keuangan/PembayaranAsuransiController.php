<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\PembayaranAsuransi;
use App\Models\Keuangan\PembayaranAsuransiDetail;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\Bank;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use App\Models\TransactionCounter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PDF;

class PembayaranAsuransiController extends Controller
{
    /**
     * Display a list of insurance payments
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $penjamins = Penjamin::all();

        // Ambil data pembayaran yang punya detail (sudah dibayar)
        $query = PembayaranAsuransi::with(['penjamin', 'bank', 'details'])
            ->has('details');

        $hasFilters = false;

        // Filter tanggal
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay();

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay();

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid (d-m-Y).']);
            }
        }

        // Filter berdasarkan penjamin
        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $hasFilters = true;
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter berdasarkan no. invoice
        if ($request->has('invoice') && $request->invoice != '') {
            $hasFilters = true;
            $query->whereHas('details.konfirmasiAsuransi', function ($q) use ($request) {
                $q->where('invoice', 'like', '%' . $request->invoice . '%');
            });
        }

        $pembayaranAsuransi = $hasFilters
            ? $query->orderBy('tanggal', 'desc')->paginate(20)
            : $query->orderBy('tanggal', 'desc')->limit(20)->get();

        return view('app-type.keuangan.pembayaran-asuransi.index', [
            'pembayaranAsuransi' => $pembayaranAsuransi,
            'penjamins' => $penjamins,
            'hasFilters' => $hasFilters
        ]);
    }


    public function create(Request $request)
    {
        $penjamins = Penjamin::all();
        $banks = Bank::all();

        // Tagihan yang belum lunas dan juga yang sebagian dibayar
        $query = KonfirmasiAsuransi::with(['registration.patient', 'penjamin'])
            ->where('status', 'Sudah Di Buat Tagihan')
            ->where(function ($q) {
                $q->whereNull('status_pembayaran')
                    ->orWhere('status_pembayaran', '!=', 'Lunas');
            })
            ->whereNotNull('jatuh_tempo') // Pastikan ada jatuh tempo
            ->when($request->filled('penjamin_id'), fn($q) => $q->where('penjamin_id', $request->penjamin_id))
            ->when($request->filled('tanggal_awal') || $request->filled('tanggal_akhir'), function ($q) use ($request) {
                $start = $request->filled('tanggal_awal')
                    ? Carbon::parse($request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(5);
                $end = $request->filled('tanggal_akhir')
                    ? Carbon::parse($request->tanggal_akhir)->endOfDay()
                    : Carbon::now();
                return $q->whereBetween('tanggal', [$start, $end]);
            })
            ->when($request->filled('invoice'), fn($q) => $q->where('invoice', 'like', "%{$request->invoice}%"))
            ->get();

        // Hitung umur piutang untuk setiap item
        foreach ($query as $item) {
            $sisa = ($item->sisa_tagihan === null || $item->sisa_tagihan == 0) &&
                ($item->total_dibayar == null || $item->total_dibayar == 0)
                ? $item->jumlah
                : $item->sisa_tagihan;

            // Hitung umur tagihan
            if ($item->jatuh_tempo) {
                $jatuhTempo = Carbon::parse($item->jatuh_tempo);
                $now = Carbon::now();
                $daysDifference = $now->diffInDays($jatuhTempo, false); // false untuk mendapatkan nilai negatif jika sudah lewat

                // Inisialisasi semua kategori umur dengan 0
                $item->umur_0 = 0;
                $item->umur_15 = 0;
                $item->umur_30 = 0;
                $item->umur_60 = 0;
                $item->umur_60_plus = 0;

                if ($daysDifference >= 0) {
                    // Belum jatuh tempo (termasuk hari jatuh tempo)
                    $item->umur_0 = $sisa;
                } else {
                    // Sudah jatuh tempo
                    $daysOverdue = abs($daysDifference);

                    if ($daysOverdue <= 15) {
                        $item->umur_15 = $sisa;
                    } elseif ($daysOverdue <= 30) {
                        $item->umur_30 = $sisa;
                    } elseif ($daysOverdue <= 60) {
                        $item->umur_60 = $sisa;
                    } else {
                        $item->umur_60_plus = $sisa;
                    }
                }
            } else {
                // Jika tidak ada jatuh tempo, default ke ≤0
                $item->umur_0 = $sisa;
            }
        }

        if ($request->ajax()) {
            return response()->json($query);
        }

        return view('app-type.keuangan.pembayaran-asuransi.create', compact('penjamins', 'banks', 'query'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal_jurnal' => 'required|date_format:d-m-Y',
            'bank_account_id' => 'required|exists:banks,id',
            'total_penerimaan_hidden' => 'required|numeric|min:1',
            'payment_details' => 'required|json',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat prefix berdasarkan tahun & jenis transaksi
            $tahun = date('y'); // ex: 25
            $kodeTransaksi = '33'; // ex: kode pembayaran asuransi
            $prefix = "{$tahun}-{$kodeTransaksi}";

            // 2. Ambil atau buat record counter berdasarkan prefix (dengan kunci transaksi)
            $counter = TransactionCounter::where('prefix', $prefix)->lockForUpdate()->first();
            if (!$counter) {
                $counter = TransactionCounter::create([
                    'prefix' => $prefix,
                    'last_number' => 1
                ]);
            } else {
                $counter->last_number += 1;
                $counter->save();
            }

            // 3. Format nomor transaksi sesuai: 25-33-000040
            $nomorUrut = str_pad($counter->last_number, 6, '0', STR_PAD_LEFT);
            $nomorTransaksi = "{$prefix}-{$nomorUrut}";

            // 4. Proses data pembayaran
            $tanggal = now();
            $tanggalJurnal = Carbon::createFromFormat('d-m-Y', $request->tanggal_jurnal);
            $paymentDetails = json_decode($request->payment_details, true);

            if (empty($paymentDetails)) {
                throw new \Exception('Tidak ada detail pembayaran yang valid.');
            }

            $firstInvoiceId = $paymentDetails[0]['invoice_id'] ?? null;
            $firstTagihan = KonfirmasiAsuransi::findOrFail($firstInvoiceId);
            $penjaminId = $firstTagihan->penjamin_id;

            $totalPembayaran = $request->total_penerimaan_hidden;

            $pembayaran = PembayaranAsuransi::create([
                'nomor_transaksi' => $nomorTransaksi,
                'tanggal' => $tanggal,
                'tanggal_jurnal' => $tanggalJurnal,
                'penjamin_id' => $penjaminId,
                'bank_id' => $request->bank_account_id,
                'jumlah' => $totalPembayaran,
                'status' => 'completed',
                'created_by' => Auth::id(),
                'keterangan' => $request->keterangan,
            ]);

            foreach ($paymentDetails as $detail) {
                $invoiceId = $detail['invoice_id'];
                $amount = $detail['amount'];
                $dibayar = is_numeric($amount) ? floatval($amount) : floatval(preg_replace('/[^\d]/', '', $amount));

                $tagihan = KonfirmasiAsuransi::findOrFail($invoiceId);

                $pembayaran->details()->create([
                    'konfirmasi_asuransi_id' => $invoiceId,
                    'dibayar' => $dibayar,
                ]);

                $tagihan->total_dibayar += $dibayar;
                $tagihan->sisa_tagihan = max(0, $tagihan->jumlah - $tagihan->total_dibayar);
                $tagihan->is_lunas = $tagihan->sisa_tagihan <= 0;
                $tagihan->status_pembayaran = $tagihan->is_lunas ? 'Lunas' : 'Sebagian';
                $tagihan->tanggal_pembayaran = now();
                $tagihan->pembayaran_id = $pembayaran->id;
                $tagihan->last_pembayaran_id = $pembayaran->id;
                $tagihan->save();
            }

            DB::commit();

            return redirect()->route('keuangan.pembayaran-asuransi.index')
                ->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Retrieve unpaid invoices based on filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagihan(Request $request)
    {
        $request->validate([
            'penjamin_id' => 'required|exists:penjamins,id',
            'tanggal_awal' => 'nullable|date_format:d-m-Y',
            'tanggal_akhir' => 'nullable|date_format:d-m-Y',
            'invoice' => 'nullable|string'
        ]);

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])
            ->where('penjamin_id', $request->penjamin_id)
            ->where(function ($q) {
                $q->whereNull('status_pembayaran')
                    ->orWhere('status_pembayaran', '!=', 'Lunas');
            })
            ->orderBy('tanggal', 'desc');

        // Filter by date range
        if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
            try {
                $startDate = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
                $endDate = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Format tanggal tidak valid'], 400);
            }
        }

        // Filter by invoice number
        if (!empty($request->invoice)) {
            $query->where('invoice', 'like', '%' . $request->invoice . '%');
        }

        $tagihan = $query->get();

        foreach ($tagihan as $item) {
            $now = Carbon::now();
            $jatuhTempo = $item->jatuh_tempo ? Carbon::parse($item->jatuh_tempo) : null;

            // Hitung sisa tagihan
            $item->sisa_tagihan = max(0, $item->jumlah - ($item->total_dibayar ?? 0));

            // Hitung umur tagihan
            if ($jatuhTempo) {
                $days = $now->diffInDays($jatuhTempo, false);
                $daysOverdue = $days < 0 ? abs($days) : 0;
                $item->days_overdue = $daysOverdue;

                // Kategori umur tagihan
                if ($daysOverdue <= 30) {
                    $item->due_date_category = "≤30";
                } elseif ($daysOverdue <= 60) {
                    $item->due_date_category = "31–60";
                } elseif ($daysOverdue <= 90) {
                    $item->due_date_category = "61–90";
                } else {
                    $item->due_date_category = ">90";
                }
            } else {
                $item->days_overdue = null;
                $item->due_date_category = null;
            }
        }

        return response()->json($tagihan);
    }


    /**
     * Show details of a specific insurance payment
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pembayaran = PembayaranAsuransi::with([
            'penjamin',
            'bank',
            'details.konfirmasiAsuransi.registration.patient',
            'createdBy',
            'updatedBy'
        ])->findOrFail($id);

        // Gunakan kolom jumlah dari tabel pembayaran_asuransi sebagai total pembayaran
        $pembayaran->total_pembayaran = $pembayaran->jumlah;

        return view('app-type.keuangan.pembayaran-asuransi.show', compact('pembayaran'));
    }

    /**
     * Delete an insurance payment
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pembayaran = PembayaranAsuransi::findOrFail($id);

            // Ambil semua tagihan terkait pembayaran ini
            $konfirmasiList = KonfirmasiAsuransi::where('pembayaran_id', $id)->get();

            foreach ($konfirmasiList as $konfirmasi) {
                $konfirmasi->update([
                    'pembayaran_id'       => null,
                    'last_pembayaran_id'  => null,
                    'status_pembayaran'   => null,
                    'tanggal_pembayaran'  => null,
                    'total_dibayar'       => 0,                    // reset ke awal
                    'sisa_tagihan'        => $konfirmasi->jumlah,  // kembali full tagihan
                    'is_lunas'            => 0,                    // reset lunas
                    'updated_by'          => Auth::id()
                ]);
            }

            // Hapus detail pembayaran
            PembayaranAsuransiDetail::where('pembayaran_asuransi_id', $id)->delete();

            // Hapus record pembayaran
            $pembayaran->delete();

            DB::commit();

            return redirect()
                ->route('keuangan.pembayaran-asuransi.index')
                ->with('success', 'Pembayaran berhasil dihapus dan tagihan terkait telah direset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Print payment receipt
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cetakBuktiPembayaran($id)
    {
        $pembayaran = PembayaranAsuransi::with([
            'penjamin',
            'bank',
            'details.konfirmasiAsuransi.registration.patient'
        ])->findOrFail($id);

        // Gunakan kolom jumlah dari tabel pembayaran_asuransi sebagai total pembayaran
        $pembayaran->total_pembayaran = $pembayaran->jumlah;

        return view('app-type.keuangan.pembayaran-asuransi.cetak.bukti-pembayaran', compact('pembayaran'));
        return $pdf->stream("bukti-pembayaran-{$pembayaran->nomor_transaksi}.pdf");
    }
}
