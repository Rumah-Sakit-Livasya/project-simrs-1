<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ApSupplierHeader;
use App\Models\Keuangan\ApSupplierDetail;
use App\Models\Keuangan\PenerimaanBarangHeader;
use App\Models\WarehousePenerimaanBarangFarmasi;
use App\Models\WarehousePenerimaanBarangNonFarmasi;
use App\Models\WarehouseReturBarang;
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
        // =========================================================================
        // LANGKAH 1: MEMULAI QUERY DENGAN EAGER LOADING
        // =========================================================================
        // Ini adalah langkah paling penting untuk performa dan ketersediaan data.
        // Kita memuat semua relasi yang akan ditampilkan di tabel dalam satu kali jalan.
        $queryBuilder = ApSupplierHeader::with([
            'supplier',
            'userEntry',
            'details.penerimaanBarang.po'
        ]);

        // =========================================================================
        // LANGKAH 2: MENERAPKAN SEMUA FILTER
        // =========================================================================
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            try {
                $start_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
                $end_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
                $queryBuilder->whereBetween('tanggal_ap', [$start_date, $end_date]);
            } catch (\Exception $e) {
                // Abaikan jika format tanggal dari input salah untuk mencegah error
                Log::warning('Format tanggal salah pada filter AP Supplier: ' . $request->tanggal_awal . ' - ' . $request->tanggal_akhir);
            }
        }

        if ($request->filled('supplier_id')) {
            $queryBuilder->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('invoice_number')) {
            $queryBuilder->where('no_invoice_supplier', 'like', '%' . $request->invoice_number . '%');
        }

        // Filter berdasarkan Nomor PO
        if ($request->filled('po_number')) {
            $queryBuilder->whereHas('details.penerimaanBarang.po', function ($q) use ($request) {
                $q->where('kode_po', 'like', '%' . $request->po_number . '%');
            });
        }

        // Filter berdasarkan Nomor GRN/Penerimaan
        if ($request->filled('grn_number')) {
            $queryBuilder->whereHas('details.penerimaanBarang', function ($q) use ($request) {
                $q->where('kode_penerimaan', 'like', '%' . $request->grn_number . '%');
            });
        }

        // =========================================================================
        // LANGKAH 3: MENANGANI REQUEST AJAX (UNTUK PENCARIAN DINAMIS)
        // =========================================================================
        if ($request->ajax()) {
            $results = $queryBuilder->latest('tanggal_ap')->get();
            // Mengembalikan data dalam format JSON yang akan diolah oleh JavaScript DataTables Anda
            return response()->json($results);
        }

        // =========================================================================
        // LANGKAH 4: MENGAMBIL DATA UNTUK PEMUATAN HALAMAN NORMAL
        // =========================================================================
        // Jika bukan request AJAX, kita lanjutkan dengan paginasi.
        // `withQueryString()` memastikan parameter filter tetap ada di link pagination.
        $ap_suppliers = $queryBuilder->latest('tanggal_ap')->paginate(20)->withQueryString();

        // Ambil data master untuk dropdown filter
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // Ambil ID dari flash session untuk memicu popup cetak otomatis
        $newlyCreatedId = $request->session()->get('newly_created_ap_id');

        // =========================================================================
        // LANGKAH 5: MENGIRIM SEMUA DATA KE VIEW
        // =========================================================================
        return view('app-type.keuangan.ap-supplier.index', compact(
            'ap_suppliers',
            'suppliers',
            'newlyCreatedId'
        ));
    }

    public function backupindegrn(Request $request)
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

    public function indexGrn(Request $request)
    {
        // Validasi dan dapatkan supplierId
        $supplierId = $request->initial_supplier_id ?? $request->supplier_id;
        if (!$supplierId) {
            $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();
            return view('app-type.keuangan.ap-supplier.partials.index-grn', [
                'availableGrns' => collect(),
                'suppliers' => $suppliers,
                'activeSupplierId' => null,
                'isSearching' => false,
            ]);
        }

        // =========================================================================
        //         LANGKAH KUNCI: DAPATKAN SEMUA GRN YANG SUDAH DIPAKAI
        // =========================================================================
        // Ambil semua detail AP untuk supplier ini
        $usedGrnDetails = ApSupplierDetail::whereHas('header', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })->get();

        // Pisahkan ID yang sudah dipakai berdasarkan tipenya
        $usedFarmasiIds = $usedGrnDetails
            ->where('penerimaan_barang_type', \App\Models\WarehousePenerimaanBarangFarmasi::class)
            ->pluck('penerimaan_barang_id')->all();

        $usedNonFarmasiIds = $usedGrnDetails
            ->where('penerimaan_barang_type', \App\Models\WarehousePenerimaanBarangNonFarmasi::class)
            ->pluck('penerimaan_barang_id')->all();

        // =========================================================================
        //         MEMFILTER GRN YANG TERSEDIA
        // =========================================================================

        // Ambil GRN Farmasi yang statusnya final DAN ID-nya TIDAK ADA di daftar yang sudah dipakai
        $grnsFarmasiQuery = WarehousePenerimaanBarangFarmasi::with('po', 'supplier')
            ->where('supplier_id', $supplierId)
            ->where('status', 'final')
            ->whereNotIn('id', $usedFarmasiIds); // <-- FILTER UTAMA

        // Ambil GRN Non-Farmasi yang statusnya final DAN ID-nya TIDAK ADA di daftar yang sudah dipakai
        $grnsNonFarmasiQuery = WarehousePenerimaanBarangNonFarmasi::with('po', 'supplier')
            ->where('supplier_id', $supplierId)
            ->where('status', 'final')
            ->whereNotIn('id', $usedNonFarmasiIds); // <-- FILTER UTAMA

        // Jalankan query untuk mendapatkan hasilnya
        $grnsFarmasi = $grnsFarmasiQuery->get();
        $grnsNonFarmasi = $grnsNonFarmasiQuery->get();

        // Tandai tipe GRN untuk frontend
        $grnsFarmasi->each(function ($item) {
            $item->grn_type = 'farmasi';
        });
        $grnsNonFarmasi->each(function ($item) {
            $item->grn_type = 'non_farmasi';
        });

        // Gabungkan dan urutkan hasilnya
        $availableGrns = $grnsFarmasi->concat($grnsNonFarmasi)->sortByDesc('tanggal_terima');

        // Ambil data lain yang dibutuhkan oleh view
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();
        $isSearching = $request->hasAny(['supplier_id', 'initial_supplier_id']);

        return view('app-type.keuangan.ap-supplier.partials.index-grn', [
            'availableGrns' => $availableGrns,
            'suppliers' => $suppliers,
            'activeSupplierId' => $supplierId,
            'isSearching' => $isSearching,
        ]);
    }
    // store function
    // File: app/Http/Controllers/Keuangan/APSupplierController.php

    // app/Http/Controllers/Keuangan/APSupplierController.php

    // GANTI SELURUH FUNGSI STORE ANDA DENGAN INI

    public function store(Request $request)
    {
        Log::debug('================ MEMULAI PROSES SIMPAN AP SUPPLIER ================');
        Log::debug('Request Data Mentah:', $request->all());

        // ==================== BAGIAN 1: VALIDASI INPUT ====================
        try {
            $validated = $request->validate([
                'tanggal_ap' => 'required|date_format:d-m-Y',
                'due_date' => 'required|date_format:d-m-Y|after_or_equal:tanggal_ap',
                'tanggal_faktur_pajak' => 'nullable|date_format:d-m-Y',
                'supplier_id' => 'required|exists:warehouse_supplier,id',
                'no_invoice' => 'required|string|max:100',
                'grn_ids' => 'required|array|min:1',
                'grn_ids.*' => 'required|string',
                'diskon' => 'required|array',
                'diskon.*' => 'numeric|min:0',
                'biaya_lain' => 'required|array',
                'biaya_lain.*' => 'numeric|min:0',
                'ppn_persen' => 'required|numeric|min:0',
                'diskon_final' => 'required|numeric|min:0',
                'retur' => 'required|numeric|min:0',
                'materai' => 'required|numeric|min:0',
                'faktur_pajak_retur' => 'nullable|string|max:255',
                'no_faktur_pajak' => 'nullable|string|max:255',
            ]);
            Log::debug('Validasi berhasil.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi Gagal:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        /** @var \App\Models\Keuangan\ApSupplierHeader|null $apHeader */
        $apHeader = null;

        try {
            Log::debug('Memulai DB Transaction.');
            DB::transaction(function () use ($request, &$apHeader) {
                // ==================== BAGIAN 2: PERSIAPAN DATA ====================
                Log::debug('Langkah 2: Mempersiapkan data.');

                // Konversi tanggal
                $tanggalAp = Carbon::createFromFormat('d-m-Y', $request->tanggal_ap);
                $dueDate = Carbon::createFromFormat('d-m-Y', $request->due_date);
                $tanggalFakturPajak = $request->filled('tanggal_faktur_pajak')
                    ? Carbon::createFromFormat('d-m-Y', $request->tanggal_faktur_pajak)
                    : null;

                Log::debug('Tanggal:', [
                    'AP' => $tanggalAp->format('Y-m-d'),
                    'Due Date' => $dueDate->format('Y-m-d'),
                    'Faktur Pajak' => $tanggalFakturPajak ? $tanggalFakturPajak->format('Y-m-d') : null
                ]);

                // Ekstrak GRN IDs
                $farmasiIds = [];
                $nonFarmasiIds = [];
                $grnDetails = [];

                foreach ($request->grn_ids as $identifier) {
                    $parts = explode('_', $identifier);
                    if (count($parts) >= 2) {
                        $type = implode('_', array_slice($parts, 0, -1));
                        $id = end($parts);

                        if ($type === 'farmasi') {
                            $farmasiIds[] = $id;
                        } elseif ($type === 'non_farmasi') {
                            $nonFarmasiIds[] = $id;
                        }

                        $grnDetails[$id] = [
                            'type' => $type,
                            'diskon' => $request->diskon[$id] ?? 0,
                            'biaya_lain' => $request->biaya_lain[$id] ?? 0
                        ];
                    }
                }

                Log::debug('GRN IDs:', [
                    'Farmasi' => $farmasiIds,
                    'Non-Farmasi' => $nonFarmasiIds,
                    'Details' => $grnDetails
                ]);

                // Ambil data GRN
                $grnsFarmasi = \App\Models\WarehousePenerimaanBarangFarmasi::whereIn('id', $farmasiIds)->get();
                $grnsNonFarmasi = \App\Models\WarehousePenerimaanBarangNonFarmasi::whereIn('id', $nonFarmasiIds)->get();
                $allSelectedGrns = $grnsFarmasi->concat($grnsNonFarmasi);

                // Validasi GRN
                if ($allSelectedGrns->count() !== count($request->grn_ids)) {
                    throw new \Exception('Satu atau lebih GRN tidak ditemukan di database');
                }

                // ==================== BAGIAN 3: KALKULASI DETAIL ====================
                Log::debug('Langkah 3: Melakukan kalkulasi detail.');

                $parseNumeric = function ($value) {
                    if (is_numeric($value)) return $value;
                    return (float) str_replace(['.', ','], ['', '.'], $value);
                };

                // Inisialisasi variabel perhitungan
                $subtotal = 0;
                $totalDiskonItems = 0;
                $totalBiayaLainItems = 0;

                // Hitung nilai per GRN
                foreach ($allSelectedGrns as $grn) {
                    $grnId = $grn->id;
                    $diskon = $parseNumeric($grnDetails[$grnId]['diskon']);
                    $biayaLain = $parseNumeric($grnDetails[$grnId]['biaya_lain']);

                    $subtotal += $grn->total;
                    $totalDiskonItems += $diskon;
                    $totalBiayaLainItems += $biayaLain;

                    Log::debug('Detail GRN:', [
                        'GRN ID' => $grnId,
                        'Nominal' => $grn->total,
                        'Diskon' => $diskon,
                        'Biaya Lain' => $biayaLain
                    ]);
                }

                // ==================== BAGIAN 4: KALKULASI TOTAL ====================
                Log::debug('Langkah 4: Melakukan kalkulasi total.');

                // Hitung nilai lainnya
                $retur = $parseNumeric($request->retur);
                $diskonFinal = $parseNumeric($request->diskon_final);
                $materai = $parseNumeric($request->materai);
                $ppnPersen = $parseNumeric($request->ppn_persen);

                // Hitung adjusted subtotal setelah diskon item dan biaya lainnya
                $adjustedSubtotal = $subtotal - $totalDiskonItems + $totalBiayaLainItems;

                // Hitung DPP (setelah retur & diskon final)
                $dpp = max(0, $adjustedSubtotal - $retur - $diskonFinal);
                $ppnNominal = $dpp * ($ppnPersen / 100);
                $grandTotal = $dpp + $ppnNominal + $materai;

                Log::debug('Hasil Kalkulasi:', [
                    'Subtotal Awal' => $subtotal,
                    'Total Diskon Item' => $totalDiskonItems,
                    'Total Biaya Lain' => $totalBiayaLainItems,
                    'Adjusted Subtotal' => $adjustedSubtotal,
                    'Retur' => $retur,
                    'Diskon Final' => $diskonFinal,
                    'DPP' => $dpp,
                    'PPN %' => $ppnPersen,
                    'PPN Nominal' => $ppnNominal,
                    'Materai' => $materai,
                    'Grand Total' => $grandTotal
                ]);

                // ==================== BAGIAN 5: SIMPAN DATA ====================
                Log::debug('Langkah 5: Menyimpan data ke database.');

                // Simpan Header
                $apHeader = ApSupplierHeader::create([
                    'kode_ap' => $this->generateApCode(),
                    'ap_type' => 'PO',
                    'supplier_id' => $request->supplier_id,
                    'no_invoice_supplier' => $request->no_invoice,
                    'tanggal_ap' => $tanggalAp,
                    'due_date' => $dueDate,
                    'tanggal_faktur_pajak' => $tanggalFakturPajak,
                    'no_faktur_pajak' => $request->no_faktur_pajak,
                    'subtotal' => $subtotal,
                    'diskon_final' => $diskonFinal,
                    'biaya_lainnya' => $totalBiayaLainItems,
                    'ppn_persen' => $ppnPersen,
                    'ppn_nominal' => $ppnNominal,
                    'retur' => $retur,
                    'materai' => $materai,
                    'sisa_hutang' => $grandTotal,
                    'grand_total' => $grandTotal,
                    'notes' => $request->notes,
                    'status_pembayaran' => 'Belum Lunas',
                    'user_entry_id' => auth()->id(),
                    'ada_kwitansi' => $request->has('ada_kwitansi') ? 1 : 0,
                    'ada_faktur_pajak' => $request->has('ada_faktur_pajak') ? 1 : 0,
                    'ada_surat_jalan' => $request->has('ada_surat_jalan') ? 1 : 0,
                    'ada_salinan_po' => $request->has('ada_salinan_po') ? 1 : 0,
                    'ada_tanda_terima_barang' => $request->has('ada_tanda_terima_barang') ? 1 : 0,
                    'ada_berita_acara' => $request->has('ada_berita_acara') ? 1 : 0,
                    'faktur_pajak_retur' => $request->faktur_pajak_retur,
                ]);

                Log::debug('Header AP berhasil disimpan dengan ID: ' . $apHeader->id);

                // Simpan Detail
                foreach ($allSelectedGrns as $grn) {
                    $grnId = $grn->id;
                    $detail = $apHeader->details()->create([
                        'penerimaan_barang_id' => $grnId,
                        'penerimaan_barang_type' => get_class($grn),
                        'nominal_grn' => $grn->total,
                        'diskon' => $parseNumeric($grnDetails[$grnId]['diskon']),
                        'biaya_lain' => $parseNumeric($grnDetails[$grnId]['biaya_lain']),
                    ]);

                    Log::debug('Detail GRN disimpan:', [
                        'AP ID' => $apHeader->id,
                        'GRN ID' => $grnId,
                        'Type' => get_class($grn),
                        'Nominal' => $grn->total,
                        'Diskon' => $detail->diskon,
                        'Biaya Lain' => $detail->biaya_lain
                    ]);
                }

                Log::debug('Semua detail berhasil disimpan.');
            });

            Log::debug('DB Transaction berhasil diselesaikan.');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan AP Supplier:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menyimpan AP Supplier: ' . $e->getMessage())
                ->withInput();
        }

        Log::debug('================ PROSES SIMPAN AP SUPPLIER SELESAI ================');
        return redirect()->route('keuangan.ap-supplier.index')
            ->with('success', 'AP Supplier berhasil dibuat.')
            ->with('newly_created_ap_id', $apHeader->id);
    }
    /**
     * Generate kode AP (contoh: APS-2506-0001)
     */
    // private function generateApCode()
    // {
    //     $prefix = 'APS-' . date('ym') . '-';
    //     $lastAP = ApSupplierHeader::where('kode_ap', 'like', $prefix . '%')
    //         ->orderBy('kode_ap', 'desc')
    //         ->first();

    //     $lastNumber = $lastAP ? (int) str_replace($prefix, '', $lastAP->kode_ap) : 0;
    //     return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    // }
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

        $availableGrns1 = WarehousePenerimaanBarangNonFarmasi::where('supplier_id', $request->supplier_id)
            ->whereNotIn('id', function () {
                ApSupplierDetail::select("id")
                    ->where("penerimaan_barang_type", "non_farmasi")
                    ->get();
            })
            ->get();

        $availableGrns2 = WarehousePenerimaanBarangFarmasi::where('supplier_id', $request->supplier_id)
            ->whereNotIn('id', function () {
                ApSupplierDetail::select("id")
                    ->where("penerimaan_barang_type", "farmasi")
                    ->get();
            })
            ->get();



        // Path view ini sudah benar sesuai permintaan Anda sebelumnya
        return view('app-type.keuangan.ap-supplier.partials.index-grn', [
            'availableGrns' => $availableGrns1,
            'availableGrns' => $availableGrns2,
            'supplierId' => $request->supplier_id
        ]);
    }

    public function pilihRetur(Request $request)
    {
        $request->validate(['supplier_id' => 'required|integer|exists:warehouse_supplier,id']);
        $supplierId = $request->supplier_id;

        // =========================================================================
        //                    EAGER LOAD RELASI YANG DIPERBAIKI
        // =========================================================================
        // Muat kedua relasi 'stored' dan relasi 'barang' di dalamnya.
        $query = \App\Models\WarehouseReturBarang::with([
            'items.storedFarmasi.barang',
            'items.storedNonFarmasi.barang'
        ])->where('supplier_id', $supplierId);

        $availableReturs = $query->latest('tanggal_retur')->get();

        $availableReturs->transform(function ($retur) {
            $retur->item_codes = $retur->items->map(function ($item) {
                return optional($item->barangInfo)->kode ?? 'N/A';
            })->implode('<br>');

            $retur->item_names = $retur->items->map(function ($item) {
                return optional($item->barangInfo)->nama ?? 'N/A';
            })->implode('<br>');

            // Jumlahkan semua qty
            $retur->total_qty = $retur->items->sum('qty');

            return $retur;
        });

        return view('app-type.keuangan.ap-supplier.partials.pilih-retur', compact('availableReturs', 'supplierId'));
    }

    // app/Http/Controllers/Keuangan/APSupplierController.php

    // app/Http/Controllers/Keuangan/APSupplierController.php

    public function show($id)
    {
        $apSupplier = ApSupplierHeader::with([
            'supplier',
            'userEntry',
            'details.penerimaanBarang.po'
        ])->findOrFail($id);

        $selectedGrnsJson = $apSupplier->details->map(function ($detail) {

            // Keamanan: Jika karena suatu hal relasi penerimaanBarang tidak ada (misal data terhapus manual),
            // kita akan skip item ini untuk mencegah error.
            if (!$grn = $detail->penerimaanBarang) {
                Log::warning("AP Supplier Detail ID {$detail->id} tidak memiliki relasi 'penerimaanBarang' yang valid.");
                return null;
            }

            return [
                'total_nilai_barang' => (float) $detail->nominal_grn,

                'diskon'             => (float) ($grn->diskon_item ?? 0),

                'biaya_lainnya'      => 0,

                'id'                 => $grn->id,
                'no_grn'             => $grn->kode_penerimaan,
                'no_po'              => optional($grn->po)->kode_po, // Gunakan optional() untuk keamanan jika PO tidak ada
                'tanggal_terima' => optional($grn->tanggal_terima)->format('d M Y'),
                'keterangan'         => $grn->keterangan,
            ];
        })->filter()->values()->toJson();

        return view('app-type.keuangan.ap-supplier.partials.details', compact(
            'apSupplier',       // Objek utama ApSupplierHeader dengan semua relasinya.
            'selectedGrnsJson'  // String JSON yang berisi detail GRN untuk JavaScript.
        ));
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
        // Eager load relasi yang dibutuhkan. Ini efisien.
        $apSupplier->load(['supplier', 'details.penerimaanBarang.po']);

        // Siapkan koleksi kosong untuk menampung baris yang akan dicetak.
        $lineItems = collect();

        // INI ADALAH LOGIKA UTAMA
        if ($apSupplier->details->isNotEmpty()) {
            // KASUS 1: Invoice MEMILIKI details (berarti dari GRN/PO)
            // Kita kelompokkan detail berdasarkan kode PO.
            $groupedByPO = $apSupplier->details->groupBy(function ($detail) {
                return $detail->penerimaanBarang?->po?->kode_po ?? 'PO Tidak Terkait';
            });

            // Sekarang kita ubah grup tersebut menjadi format baris yang konsisten.
            foreach ($groupedByPO as $poCode => $details) {
                $lineItems->push((object) [
                    'po_code'      => $poCode,
                    'invoice_no'   => $apSupplier->no_invoice_supplier,
                    'line_total'   => $details->sum('subtotal') // Menjumlahkan subtotal dari semua item di PO ini
                ]);
            }
        } else {
            // KASUS 2: Invoice TIDAK MEMILIKI details (Non-GRN/langsung)
            // Kita buat SATU baris ringkasan dari data header.
            $lineItems->push((object) [
                'po_code'      => 'NON PO', // Sesuai permintaan Anda
                'invoice_no'   => $apSupplier->no_invoice_supplier, // Ambil dari header
                'line_total'   => $apSupplier->subtotal, // Ambil nominal dari header (subtotal adalah pilihan terbaik sebelum pajak dll)
            ]);
        }

        // Kirim data AP dan variabel $lineItems yang sudah siap ke view.
        return view('app-type.keuangan.ap-supplier.print.invoice', compact('apSupplier', 'lineItems'));
    }
}
