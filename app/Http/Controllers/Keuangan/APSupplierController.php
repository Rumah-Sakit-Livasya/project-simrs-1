<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\keuangan\ApSupplierHeader;
use App\Models\keuangan\ApSupplierHeader as KeuanganApSupplierHeader;
use App\Models\keuangan\PenerimaanBarangHeader;
use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class APSupplierController extends Controller
{
    public function index(Request $request)
    {
        $queryBuilder = ApSupplierHeader::query();

        // --- Eager Loading untuk Performa Optimal ---
        $queryBuilder->with(['supplier', 'userEntry', 'details.penerimaanBarang.purchasable']);


        $hasFilters = $request->hasAny(['tanggal_awal', 'tanggal_akhir', 'supplier_id', 'po_number', 'invoice_number', 'grn_number']);

        if ($hasFilters) {
            // Filter by Date Range
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                // Konversi format tanggal dari dd-mm-yyyy ke yyyy-mm-dd untuk database
                $start_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
                $end_date = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
                $queryBuilder->whereBetween('tanggal_ap', [$start_date, $end_date]);
            }

            // Filter by Supplier
            if ($request->filled('supplier_id')) {
                $queryBuilder->where('supplier_id', $request->supplier_id);
            }

            // Filter by Invoice Number
            if ($request->filled('invoice_number')) {
                $queryBuilder->where('no_invoice_supplier', 'like', '%' . $request->invoice_number . '%');
            }

            if ($request->filled('grn_number')) {
                $queryBuilder->whereHas('details.penerimaanBarang', function ($q) use ($request) {
                    $q->where('no_grn', 'like', '%' . $request->grn_number . '%');
                });
            }

            if ($request->filled('po_number')) {
                $queryBuilder->whereHas('details.penerimaanBarang.purchasable', function ($q) use ($request) {
                    $q->where('no_po', 'like', '%' . $request->po_number . '%');
                });
            }
        }


        if ($request->ajax()) {
            // Jika AJAX, eksekusi query dan kembalikan hasilnya sebagai JSON
            $results = $hasFilters ? $queryBuilder->latest()->get() : collect([]); // Kirim array kosong jika tidak ada filter
            return response()->json($results);
        }


        $ap_suppliers = $hasFilters ? $queryBuilder->latest()->paginate(25) : collect([]);

        // Ambil data untuk dropdown di form pencarian
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // Kirim data ke view
        return view('app-type.keuangan.ap-supplier.index', [ // Pastikan path view benar
            'ap_suppliers' => $ap_suppliers,
            'suppliers' => $suppliers
        ]);
    }

    // Fungsi untuk menampilkan halaman pilih GRN
    public function indexGrn(Request $request)
    {
        $queryBuilder = PenerimaanBarangHeader::with(['supplier', 'purchasable'])
            ->where('status_ap', 'Belum AP');

        // Filter berdasarkan parameter pencarian
        $hasFilters = $request->hasAny(['supplier', 'po_number', 'invoice_number', 'grn_number']);

        if ($hasFilters) {
            if ($request->filled('supplier')) {
                $queryBuilder->whereHas('supplier', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->supplier . '%');
                });
            }

            if ($request->filled('po_number')) {
                $queryBuilder->whereHas('purchasable', function ($q) use ($request) {
                    $q->where('no_po', 'like', '%' . $request->po_number . '%');
                });
            }

            if ($request->filled('invoice_number')) {
                $queryBuilder->where('no_invoice', 'like', '%' . $request->invoice_number . '%');
            }

            if ($request->filled('grn_number')) {
                $queryBuilder->where('no_grn', 'like', '%' . $request->grn_number . '%');
            }
        }

        $availableGrns = $hasFilters ? $queryBuilder->latest('tanggal_penerimaan')->get() : collect([]);
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // Ambil supplier_id dari request, atau bisa juga pakai default null
        $activeSupplierId = $request->get('supplier_id');

        return view('app-type.keuangan.ap-supplier.partials.index-grn', [
            'availableGrns' => $availableGrns,
            'suppliers' => $suppliers,
            'hasFilters' => $hasFilters,
            'activeSupplierId' => $activeSupplierId
        ]);
    }


    // store function
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_ap' => 'required|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y|after_or_equal:tanggal_ap',
            'tanggal_faktur_pajak' => 'nullable|date_format:d-m-Y',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'no_invoice' => 'required|string|max:100',
            'grn_ids' => 'required|array|min:1',
            'grn_ids.*' => 'required|integer|exists:penerimaan_barang_header,id',
            'notes' => 'nullable|string',
            'diskon_final' => 'required|numeric|min:0',
            'ppn_persen' => 'required|numeric|min:0',
            // Checkboxes (boolean)
            'ada_kwitansi' => 'nullable|boolean',
            'ada_faktur_pajak' => 'nullable|boolean',
            'ada_surat_jalan' => 'nullable|boolean',
            'ada_salinan_po' => 'nullable|boolean',
            'ada_tanda_terima_barang' => 'nullable|boolean',
            'ada_berita_acara' => 'nullable|boolean',
        ]);

        try {
            // Gunakan Transaksi Database untuk memastikan integritas data
            DB::transaction(function () use ($request, $validated) {
                // Konversi format tanggal sebelum disimpan
                $tanggalAp = Carbon::createFromFormat('d-m-Y', $validated['tanggal_ap']);
                $dueDate = Carbon::createFromFormat('d-m-Y', $validated['due_date']);
                $tanggalFakturPajak = $validated['tanggal_faktur_pajak'] ? Carbon::createFromFormat('d-m-Y', $validated['tanggal_faktur_pajak']) : null;

                // 1. Ambil semua GRN yang dipilih untuk menghitung total
                $selectedGrns = PenerimaanBarangHeader::whereIn('id', $validated['grn_ids'])->get();
                $subtotal = $selectedGrns->sum('total_nilai_barang');

                // Hitung PPN Nominal
                $ppnNominal = ($subtotal - $request->input('diskon_final', 0)) * ($request->input('ppn_persen', 0) / 100);

                // Hitung Grand Total
                $grandTotal = ($subtotal - $request->input('diskon_final', 0)) + $ppnNominal;

                // 2. Buat record di ap_supplier_header
                $apHeader = ApSupplierHeader::create([
                    'kode_ap' => $this->generateApCode(),
                    'supplier_id' => $validated['supplier_id'],
                    'no_invoice_supplier' => $validated['no_invoice'],
                    'tanggal_ap' => $tanggalAp,
                    'due_date' => $dueDate,
                    'tanggal_faktur_pajak' => $tanggalFakturPajak,
                    'subtotal' => $subtotal,
                    'diskon_final' => $request->input('diskon_final', 0),
                    'ppn_persen' => $request->input('ppn_persen', 0),
                    'ppn_nominal' => $ppnNominal,
                    'biaya_lainnya' => 0, // Belum ada di form, kita set 0
                    'grand_total' => $grandTotal,
                    'notes' => $validated['notes'],
                    'status_pembayaran' => 'Belum Lunas',
                    'user_entry_id' => auth()->id(),
                    'ada_kwitansi' => $request->boolean('ada_kwitansi'),
                    'ada_faktur_pajak' => $request->boolean('ada_faktur_pajak'),
                    'ada_surat_jalan' => $request->boolean('ada_surat_jalan'),
                    'ada_salinan_po' => $request->boolean('ada_salinan_po'),
                    'ada_tanda_terima_barang' => $request->boolean('ada_tanda_terima_barang'),
                    'ada_berita_acara' => $request->boolean('ada_berita_acara'),
                ]);

                // 3. Loop untuk menyimpan detail dan update status GRN
                foreach ($selectedGrns as $grn) {
                    $apHeader->details()->create([
                        'penerimaan_barang_header_id' => $grn->id,
                        'nominal_grn' => $grn->total_nilai_barang,
                    ]);
                    // Update status GRN menjadi 'Sudah AP'
                    $grn->update(['status_ap' => 'Sudah AP']);
                }
            });
        } catch (\Exception $e) {
            // Jika transaksi gagal, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }

        // Jika berhasil, redirect ke halaman index
        return redirect()->route('app-type.keuangan.ap-supplier.index')->with('success', 'AP Supplier berhasil dibuat.');
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

    public function create()
    {
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        return view('app-type.keuangan.ap-supplier.partials.create', [
            'suppliers' => $suppliers
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
}
