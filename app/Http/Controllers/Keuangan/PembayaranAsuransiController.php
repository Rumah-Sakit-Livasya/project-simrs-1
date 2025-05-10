<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\PembayaranAsuransi;
use App\Models\Keuangan\PembayaranAsuransiDetail;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\Keuangan\Bank;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class PembayaranAsuransiController extends Controller
{
    public function index(Request $request)
    {
        $penjamins = Penjamin::all();
        $query = PembayaranAsuransi::with(['penjamin', 'bank']);

        // Initialize flag to check if any filter is applied
        $hasFilters = false;

        // Filter by date range
        if (!empty($request->tanggal_awal) || !empty($request->tanggal_akhir)) {
            $hasFilters = true;

            try {
                // If only one date is provided, use it for both start and end
                $startDate = !empty($request->tanggal_awal)
                    ? Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay()
                    : Carbon::now()->subYears(10)->startOfDay(); // Default to very old date if not provided

                $endDate = !empty($request->tanggal_akhir)
                    ? Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay()
                    : Carbon::now()->endOfDay(); // Default to now if not provided

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['format_tanggal' => 'Format tanggal tidak valid. Harus dalam format d-m-Y.']);
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
            $query->whereHas('details', function ($q) use ($request) {
                $q->whereHas('konfirmasiAsuransi', function ($q2) use ($request) {
                    $q2->where('invoice', 'like', '%' . $request->invoice . '%');
                });
            });
        }

        // Only execute query if filters are applied
        $pembayaranAsuransi = $hasFilters ? $query->orderBy('tanggal', 'desc')->paginate(20) :
            $query->orderBy('tanggal', 'desc')->limit(20)->get();

        return view('app-type.keuangan.pembayaran-asuransi.index', [
            'pembayaranAsuransi' => $pembayaranAsuransi,
            'penjamins' => $penjamins,
            'hasFilters' => $hasFilters
        ]);
    }

    public function create()
    {
        $query = PembayaranAsuransi::with(['penjamin', 'bank'])
            ->orderBy('tanggal', 'desc');
        $penjamins = Penjamin::all();
        $banks = Bank::all();
        return view('app-type.keuangan.pembayaran-asuransi.create', compact('penjamins', 'banks', 'query'));
    }

    public function getTagihan(Request $request)
    {
        $penjaminId = $request->penjamin_id;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $invoice = $request->invoice;

        $query = KonfirmasiAsuransi::with(['penjamin', 'registration.patient'])
            ->where('penjamin_id', $penjaminId)
            ->whereNull('pembayaran_id') // Only show unpaid invoices
            ->orderBy('tanggal', 'desc');

        // Filter by date range
        if (!empty($tanggalAwal) && !empty($tanggalAkhir)) {
            try {
                $startDate = Carbon::createFromFormat('d-m-Y', $tanggalAwal)->startOfDay();
                $endDate = Carbon::createFromFormat('d-m-Y', $tanggalAkhir)->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Format tanggal tidak valid'], 400);
            }
        }

        // Filter by invoice number
        if (!empty($invoice)) {
            $query->where('invoice', 'like', '%' . $invoice . '%');
        }

        $tagihan = $query->get();

        // Calculate due date periods for each tagihan
        foreach ($tagihan as $item) {
            $now = Carbon::now();
            $jatuhTempo = $item->jatuh_tempo ? Carbon::parse($item->jatuh_tempo) : null;

            if ($jatuhTempo) {
                $item->days_overdue = $now->diffInDays($jatuhTempo, false); // Negative if overdue

                // Categorize the due date periods
                if ($item->days_overdue > 0) {
                    $item->due_date_category = "<=0"; // Not yet due
                } elseif ($item->days_overdue >= -15) {
                    $item->due_date_category = "0-15"; // 0-15 days overdue
                } elseif ($item->days_overdue >= -30) {
                    $item->due_date_category = "16-30"; // 16-30 days overdue
                } elseif ($item->days_overdue >= -60) {
                    $item->due_date_category = "31-60"; // 31-60 days overdue
                } else {
                    $item->due_date_category = ">60"; // More than 60 days overdue
                }
            } else {
                $item->days_overdue = null;
                $item->due_date_category = null;
            }
        }

        return response()->json($tagihan);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'penjamin_id' => 'required|exists:penjamin,id',
            'bank_account_id' => 'required|exists:banks,id',
            'tanggal_jurnal' => 'required|date_format:d-m-Y',
            'total_penerimaan' => 'required',
            'selected_invoices' => 'required|array',
            'selected_invoices.*' => 'exists:konfirmasi_asuransi,id'
        ]);

        try {
            DB::beginTransaction();

            // Format tanggal jurnal
            $tanggalJurnal = Carbon::createFromFormat('d-m-Y', $request->tanggal_jurnal)->format('Y-m-d');

            // Clean numeric values
            $totalPenerimaan = str_replace(['Rp ', '.', ','], '', $request->total_penerimaan);

            // Generate nomor transaksi
            $nomorTransaksi = 'AR-' . date('Ymd') . '-' . sprintf('%04d', PembayaranAsuransi::whereDate('created_at', today())->count() + 1);

            // Create pembayaran asuransi
            $pembayaran = PembayaranAsuransi::create([
                'nomor_transaksi' => $nomorTransaksi,
                'tanggal' => $tanggalJurnal,
                'penjamin_id' => $request->penjamin_id,
                'bank_id' => $request->bank_account_id,
                'jumlah' => $totalPenerimaan,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create details and update konfirmasi asuransi
            foreach ($request->selected_invoices as $konfirmasiId) {
                $konfirmasi = KonfirmasiAsuransi::findOrFail($konfirmasiId);

                // Create detail record
                PembayaranAsuransiDetail::create([
                    'pembayaran_asuransi_id' => $pembayaran->id,
                    'konfirmasi_asuransi_id' => $konfirmasi->id,
                    'jumlah' => $konfirmasi->jumlah,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Update konfirmasi asuransi to mark as paid
                $konfirmasi->update([
                    'pembayaran_id' => $pembayaran->id,
                    'status_pembayaran' => 'paid',
                    'tanggal_pembayaran' => $tanggalJurnal,
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.pembayaran-asuransi.index')
                ->with('success', 'Pembayaran asuransi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $pembayaran = PembayaranAsuransi::with([
            'penjamin',
            'bank',
            'details.konfirmasiAsuransi.registration.patient',
            'createdBy',
            'updatedBy'
        ])->findOrFail($id);

        return view('app-type.keuangan.pembayaran-asuransi.show', compact('pembayaran'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pembayaran = PembayaranAsuransi::findOrFail($id);

            // Update all related konfirmasi asuransi records
            KonfirmasiAsuransi::where('pembayaran_id', $id)
                ->update([
                    'pembayaran_id' => null,
                    'status_pembayaran' => null,
                    'tanggal_pembayaran' => null,
                    'updated_by' => Auth::id()
                ]);

            // Delete details first to maintain referential integrity
            PembayaranAsuransiDetail::where('pembayaran_asuransi_id', $id)->delete();

            // Then delete the main record
            $pembayaran->delete();

            DB::commit();

            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function cetakBuktiPembayaran($id)
    {
        $pembayaran = PembayaranAsuransi::with([
            'penjamin',
            'bank',
            'details.konfirmasiAsuransi.registration.patient'
        ])->findOrFail($id);

        $pdf = PDF::loadView('app-type.keuangan.pembayaran-asuransi.cetak.bukti-pembayaran', compact('pembayaran'));
        return $pdf->stream("bukti-pembayaran-{$pembayaran->nomor_transaksi}.pdf");
    }

    public function cetakRekap(Request $request)
    {
        $query = PembayaranAsuransi::with(['penjamin', 'bank', 'details.konfirmasiAsuransi']);

        if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if ($request->has('penjamin_id') && $request->penjamin_id != '') {
            $query->where('penjamin_id', $request->penjamin_id);
            $penjamin = Penjamin::find($request->penjamin_id);
        } else {
            $penjamin = null;
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $pdf = PDF::loadView('app-type.keuangan.pembayaran-asuransi.cetak.rekap', compact('data', 'penjamin'));
        return $pdf->stream('rekap-pembayaran-asuransi.pdf');
    }
}
