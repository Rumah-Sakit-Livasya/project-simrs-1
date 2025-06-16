<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\keuangan\ApSupplierHeader;
use App\Models\keuangan\ApSupplierHeader as KeuanganApSupplierHeader;
use App\Models\keuangan\PenerimaanBarangHeader;
use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log; // Pastikan ini ada untuk logging

class APSupplierController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai query dengan eager loading
        $queryBuilder = ApSupplierHeader::with(['supplier', 'userEntry',  'details.penerimaanBarang.purchasable']);
        // dd($queryBuilder->first()->details->first()->penerimaanBarang->purchasable);
        // 2. Terapkan filter jika ada
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            try {
                // Gunakan format Y-m-d yang lebih standar untuk input form
                $start_date = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay();
                $end_date = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay();
                $queryBuilder->whereBetween('tanggal_ap', [$start_date, $end_date]);
            } catch (\Exception $e) {
                // Abaikan jika format tanggal salah, atau beri pesan error
            }
        }
        if ($request->filled('supplier_id')) {
            $queryBuilder->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('invoice_number')) {
            $queryBuilder->where('no_invoice_supplier', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->filled('grn_number')) {
            $queryBuilder->whereHas('details.penerimaanBarang', function ($q) use ($request) {
                $q->where('no_grn', 'like', '%' . $request->grn_number . '%');
            });
        }
        if ($request->filled('po_number')) {
            // Memastikan relasi ke PO melalui GRN
            $queryBuilder->whereHas('details.penerimaanBarang.purchasable', function ($q) use ($request) {
                // Asumsi kolom di tabel PO adalah 'kode_po'
                $q->where('kode_po', 'like', '%' . $request->po_number . '%');
            });
        }

        // 3. FIX LOGIKA: Selalu ambil data, baik ada filter maupun tidak.
        // Data diurutkan dari yang terbaru, dan dipaginasi.
        // withQueryString() agar parameter filter tetap ada di link pagination.
        $ap_suppliers = $queryBuilder->latest('tanggal_ap')->paginate(20)->withQueryString();

        // 4. Ambil data untuk dropdown filter
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        $newlyCreatedId = $request->session()->get('newly_created_ap_id');
        // 5. Kirim data ke view
        return view('app-type.keuangan.ap-supplier.index', compact('ap_suppliers', 'suppliers', 'newlyCreatedId'));
    }

    public function indexGrn(Request $request)
    {
        \Log::debug('indexGrn Request Parameters:', $request->all());

        $queryBuilder = PenerimaanBarangHeader::with(['supplier', 'purchasable'])
            ->where('status_ap', 'Belum AP');

        $isSearching = $request->hasAny(['supplier_id', 'initial_supplier_id', 'po_number', 'invoice_number', 'grn_number']);

        if ($request->filled('initial_supplier_id')) {
            \Log::debug('Filter by initial supplier_id: ' . $request->initial_supplier_id);
            $queryBuilder->where('supplier_id', $request->initial_supplier_id);
            $activeSupplierId = $request->initial_supplier_id;
        } elseif ($request->filled('supplier_id')) {
            \Log::debug('Filter by supplier_id: ' . $request->supplier_id);
            $queryBuilder->where('supplier_id', $request->supplier_id);
            $activeSupplierId = $request->supplier_id;
        } else {
            $activeSupplierId = null;
        }

        // Filter tambahan
        if ($request->filled('po_number')) {
            $queryBuilder->whereHas('purchasable', function ($q) use ($request) {
                $q->where('no_po', 'like', '%' . $request->po_number . '%');
            });
            \Log::debug('Filter by PO: ' . $request->po_number);
        }

        if ($request->filled('invoice_number')) {
            $queryBuilder->where('no_invoice', 'like', '%' . $request->invoice_number . '%');
            \Log::debug('Filter by Invoice: ' . $request->invoice_number);
        }

        if ($request->filled('grn_number')) {
            $queryBuilder->where('no_grn', 'like', '%' . $request->grn_number . '%');
            \Log::debug('Filter by GRN: ' . $request->grn_number);
        }

        $availableGrns = $isSearching ? $queryBuilder->get() : collect([]);
        \Log::debug('Available GRNs count: ' . $availableGrns->count());

        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        return view('app-type.keuangan.ap-supplier.partials.index-grn', [
            'availableGrns' => $availableGrns,
            'suppliers' => $suppliers,
            'activeSupplierId' => $activeSupplierId,
            'isSearching' => $isSearching,
            'parentWindow' => $request->input('parent_window', '') // Tambahkan ini
        ]);
    }
    // store function
    // File: app/Http/Controllers/Keuangan/APSupplierController.php

    public function store(Request $request)
    {
        // =========================================================================
        // BAGIAN 1: VALIDASI DATA
        // =========================================================================
        // Validasi ini sesuai dengan yang Anda berikan.
        // Ini memastikan data dasar yang dikirim dari form memenuhi aturan.
        // Format tanggal 'd-m-Y' digunakan karena datepicker Anda mengirim format ini.
        $validated = $request->validate([
            'tanggal_ap' => 'required|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y|after_or_equal:tanggal_ap',
            'tanggal_faktur_pajak' => 'nullable|date_format:d-m-Y',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'no_invoice' => 'required|string|max:100', // Sesuai dengan name="no_invoice" di form
            'grn_ids' => 'required|array|min:1',
            'grn_ids.*' => 'required|integer|exists:penerimaan_barang_header,id',
            'diskon_final' => 'required|numeric|min:0',

            // Field-field ini tidak ada di validasi Anda, jadi kita akan mengambilnya langsung dari request.
            // Namun, disarankan untuk memvalidasinya juga di masa depan.
            // 'notes' => 'nullable|string',
            // 'ppn_persen' => 'required|numeric|min:0',
            // 'retur' => 'required|numeric|min:0',
            // 'materai' => 'required|numeric|min:0',
            // 'keterangan_item' => 'nullable|array',
            // 'diskon_item' => 'nullable|array',
            // 'biaya_lainnya_item' => 'nullable|array',
        ]);

        // Inisialisasi variabel $apHeader di luar transaksi agar dapat diakses setelahnya
        $apHeader = null;

        try {
            // =========================================================================
            // BAGIAN 2: TRANSAKSI DATABASE
            // =========================================================================
            // Menggunakan DB::transaction untuk memastikan semua query berhasil atau
            // semua dibatalkan jika terjadi error. Ini menjaga integritas data.
            DB::transaction(function () use ($request, &$apHeader) {

                // Konversi format tanggal dari form ('d-m-Y') ke format database ('Y-m-d')
                $tanggalAp = Carbon::createFromFormat('d-m-Y', $request->tanggal_ap);
                $dueDate = Carbon::createFromFormat('d-m-Y', $request->due_date);
                $tanggalFakturPajak = $request->tanggal_faktur_pajak ? Carbon::createFromFormat('d-m-Y', $request->tanggal_faktur_pajak) : null;

                // Ambil data GRN yang dipilih untuk kalkulasi
                $selectedGrns = PenerimaanBarangHeader::whereIn('id', $request->grn_ids)->get();

                // =========================================================================
                // BAGIAN 3: KALKULASI DI BACKEND
                // =========================================================================
                // Semua kalkulasi dilakukan di backend untuk keamanan dan keakuratan.
                // Jangan pernah mempercayai nilai total yang dikirim dari frontend.
                $subtotal = $selectedGrns->sum('total_nilai_barang');
                $totalDiskonItem = collect($request->input('diskon_item', []))->sum();
                $totalBiayaLainItem = collect($request->input('biaya_lainnya_item', []))->sum();

                $retur = $request->input('retur', 0);
                $diskonFinal = $request->input('diskon_final', 0);
                $materai = $request->input('materai', 0);

                // Dasar Pengenaan Pajak (DPP) dihitung setelah semua diskon dan retur
                $dpp = $subtotal - $totalDiskonItem - $diskonFinal - $retur;
                $ppnNominal = $dpp * ($request->input('ppn_persen', 0) / 100);
                $grandTotal = $dpp + $ppnNominal + $totalBiayaLainItem + $materai;

                // =========================================================================
                // BAGIAN 4: SIMPAN DATA KE DATABASE
                // =========================================================================
                // Membuat record baru di tabel `ap_supplier_header`
                $apHeader = ApSupplierHeader::create([
                    'kode_ap' => $this->generateApCode(),
                    'supplier_id' => $request->supplier_id,
                    'no_invoice_supplier' => $request->no_invoice,
                    'no_faktur_pajak' => $request->no_faktur_pajak,
                    'tanggal_ap' => $tanggalAp,
                    'due_date' => $dueDate,
                    'tanggal_faktur_pajak' => $tanggalFakturPajak,
                    'subtotal' => $subtotal,
                    'diskon_final' => $diskonFinal,
                    'ppn_persen' => $request->input('ppn_persen', 0),
                    'ppn_nominal' => $ppnNominal,
                    'biaya_lainnya' => $totalBiayaLainItem,
                    'retur' => $retur,
                    'materai' => $materai,
                    'grand_total' => $grandTotal,
                    'notes' => $request->notes,
                    'status_pembayaran' => 'Belum Lunas',
                    'user_entry_id' => auth()->id(),
                    'ada_kwitansi' => $request->boolean('ada_kwitansi'),
                    'ada_faktur_pajak' => $request->boolean('ada_faktur_pajak'),
                    'ada_surat_jalan' => $request->boolean('ada_surat_jalan'),
                    'ada_salinan_po' => $request->boolean('ada_salinan_po'),
                    'ada_tanda_terima_barang' => $request->boolean('ada_tanda_terima_barang'),
                    'ada_berita_acara' => $request->boolean('ada_berita_acara'),
                ]);

                // Membuat record detail untuk setiap GRN dan mengupdate status GRN
                foreach ($selectedGrns as $grn) {
                    $apHeader->details()->create([
                        'penerimaan_barang_header_id' => $grn->id,
                        'nominal_grn' => $grn->total_nilai_barang,
                        'keterangan' => $request->keterangan_item[$grn->id] ?? null,
                        'diskon_item' => $request->diskon_item[$grn->id] ?? 0,
                        'biaya_lainnya_item' => $request->biaya_lainnya_item[$grn->id] ?? 0,
                    ]);
                    $grn->update(['status_ap' => 'Sudah AP']);
                }
            });
        } catch (\Exception $e) {
            // Jika terjadi error selama transaksi, catat di log dan kembalikan pesan error ke pengguna
            Log::error('Gagal menyimpan AP Supplier: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }

        // =========================================================================
        // BAGIAN 5: REDIRECT DENGAN FLASH SESSION UNTUK POPUP
        // =========================================================================
        // Jika $apHeader berhasil dibuat (transaksi sukses), redirect ke halaman index.
        if ($apHeader) {
            // `with('newly_created_ap_id', ...)` adalah kunci untuk memicu popup otomatis.
            // Ia mengirim ID AP yang baru dibuat ke request berikutnya (saat halaman index dimuat).
            return redirect()->route('keuangan.ap-supplier.index')
                ->with('success', 'AP Supplier berhasil dibuat.')
                ->with('newly_created_ap_id', $apHeader->id);
        }

        // Fallback jika karena suatu hal $apHeader tidak terisi (meskipun jarang terjadi)
        return redirect()->back()->with('error', 'Gagal mendapatkan data AP setelah penyimpanan.')->withInput();
    }

    public function fetchAvailableGrn(Request $request)
    {
        $request->validate(['supplier_id' => 'required|integer|exists:warehouse_supplier,id']);

        $grns = PenerimaanBarangHeader::with('purchasable')
            ->where('supplier_id', $request->supplier_id)
            ->where('status_ap', 'Belum AP')
            ->latest('tanggal_penerimaan')
            ->get();

        return response()->json($grns);
    }

    /**
     * Contoh fungsi untuk generate kode AP unik.
     */
    private function generateApCode()
    {
        $prefix = 'APS-' . date('ym') . '-';
        $lastAp = ApSupplierHeader::where('kode_ap', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $lastAp ? ((int) substr($lastAp->kode_ap, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function create(Request $request)
    {
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // Initialize selectedGrn as null
        $selectedGrn = null;

        // Check for single GRN selection
        if ($request->has('selected_grn')) {
            $selectedGrn = [
                'id' => $request->selected_grn,
                'no_grn' => $request->grn_no,
                'supplier' => $request->supplier,
                'supplier_id' => $request->supplier_id,
                'po_no' => $request->po_no,
                'total' => $request->total
            ];
        }
        // Check for multiple GRN selection
        elseif ($request->has('selected_grns')) {
            $selectedGrns = explode(',', $request->selected_grns);
            $selectedGrn = ['multiple' => true, 'ids' => $selectedGrns];
        }

        return view('app-type.keuangan.ap-supplier.partials.create', [
            'suppliers' => $suppliers,
            'selectedGrn' => $selectedGrn
        ]);
    }

    public function edit()
    {
        return view('app-type.keuangan.ap-supplier.partials.edit');
    }

    // app/Http/Controllers/ApSupplierController.php
    public function selectGrn(Request $request)
    {
        $request->validate(['supplier_id' => 'required|integer|exists:warehouse_supplier,id']);

        $availableGrns = PenerimaanBarangHeader::with('purchasable')
            ->where('supplier_id', $request->supplier_id)
            ->where('status_ap', 'Belum AP')
            ->latest('tanggal_penerimaan')
            ->get();

        // Path view ini sudah benar sesuai permintaan Anda sebelumnya
        return view('app-type.keuangan.ap-supplier.partials.index-grn', [
            'availableGrns' => $availableGrns,
            'supplierId' => $request->supplier_id
        ]);
    }

    // Add these methods to your APSupplierController

    public function show($id)
    {
        $apSupplier = ApSupplierHeader::with([
            'supplier',
            'details.penerimaanBarang.purchasable', // Pastikan ini ada
            'userEntry'
        ])->findOrFail($id);

        // Hitung ulang semua nilai untuk memastikan konsistensi
        $apSupplier->subtotal = $apSupplier->details->sum('nominal_grn');
        $totalDiskonItem = $apSupplier->details->sum('diskon_item');
        $totalBiayaLainItem = $apSupplier->details->sum('biaya_lainnya_item');

        $dpp = $apSupplier->subtotal - $totalDiskonItem - $apSupplier->diskon_final - $apSupplier->retur;
        $apSupplier->ppn_nominal = $dpp * ($apSupplier->ppn_persen / 100);
        $apSupplier->grand_total = $dpp + $apSupplier->ppn_nominal + $totalBiayaLainItem + $apSupplier->materai;

        return view('app-type.keuangan.ap-supplier.partials.details', compact('apSupplier'));
    }


    public function cancel(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($id) {
                // 1. Temukan AP Supplier yang akan dibatalkan
                $apSupplier = ApSupplierHeader::findOrFail($id);

                // 2. Kembalikan status semua GRN yang terhubung ke 'Belum AP'
                foreach ($apSupplier->details as $detail) {
                    if ($detail->penerimaanBarang) {
                        $detail->penerimaanBarang->update(['status_ap' => 'Belum AP']);
                    }
                }

                // 3. Hapus detail terlebih dahulu untuk menjaga integritas
                $apSupplier->details()->delete();

                // 4. Hapus header-nya (hard delete)
                $apSupplier->forceDelete(); // Gunakan forceDelete untuk menghapus permanen
            });

            return redirect()->route('keuangan.ap-supplier.index')
                ->with('success', "AP Supplier berhasil dibatalkan dan dihapus permanen.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membatalkan AP Supplier #{$id}: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan AP Supplier: ' . $e->getMessage());
        }
    }

    public function print(ApSupplierHeader $apSupplier)
    {
        $apSupplier->load(['supplier', 'details.penerimaanBarang.purchasable']);

        return view('app-type.keuangan.ap-supplier.print.invoice', compact('apSupplier'));
    }
}
