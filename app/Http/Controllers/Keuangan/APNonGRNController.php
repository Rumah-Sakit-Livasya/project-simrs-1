<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ApSupplierHeader; // Model Header Utama
use App\Models\Keuangan\ApNonGRNDetail;     // Model Detail Non-PO
use App\Models\Keuangan\ChartOfAccount;
use App\Models\Keuangan\GroupChartOfAccount;
use App\Models\Keuangan\RncCenter;
use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class APNonGRNController extends Controller
{
    /**
     * Menampilkan halaman index untuk AP Non-PO.
     */
    public function index(Request $request)
    {
        // Query dasar yang akan kita modifikasi
        $query = ApSupplierHeader::with(['supplier', 'userEntry'])
            ->where('ap_type', 'Non-PO');

        // ==========================================================
        // INI BAGIAN PALING PENTING: MENERAPKAN FILTER DARI REQUEST
        // ==========================================================
        if ($request->filled('tanggal_awal')) {
            // Mengubah format tanggal dari dd-mm-yyyy menjadi yyyy-mm-dd untuk query
            $tanggal_awal = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->format('Y-m-d');
            $query->whereDate('tanggal_ap', '>=', $tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $tanggal_akhir = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->format('Y-m-d');
            $query->whereDate('tanggal_ap', '<=', $tanggal_akhir);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('invoice_number')) {
            $query->where('no_invoice_supplier', 'like', '%' . $request->invoice_number . '%');
        }

        // Eksekusi query setelah semua filter diterapkan
        $apNonGrn = $query->orderBy('tanggal_ap', 'desc')->get();

        // Cek apakah ini adalah permintaan AJAX (dari form pencarian)
        if ($request->ajax()) {
            // Jika ya, kembalikan hanya data dalam format JSON
            return response()->json($apNonGrn);
        }

        // Jika bukan AJAX (saat halaman pertama kali dibuka),
        // muat data yang dibutuhkan untuk dropdown form
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // Tampilkan view lengkap dengan data awal
        return view('app-type.keuangan.ap-non-gr.index', compact('apNonGrn', 'suppliers'));
    }

    public function search(Request $request)
    {
        $query = ApSupplierHeader::with(['supplier', 'userEntry'])
            ->where('ap_type', 'Non-PO');

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
            $start = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_ap', [$start, $end]);
        }

        // Filter berdasarkan supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter berdasarkan nomor invoice
        if ($request->filled('invoice_number')) {
            $query->where('no_invoice_supplier', 'like', '%' . $request->invoice_number . '%');
        }

        $apNonPO = $query->orderBy('tanggal_ap', 'desc')->get();

        return view('keuangan.ap-non-gr.partials.table', compact('apNonPO'));
    }


    public function create()
    {
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // --- Logika untuk Akun Transaksi (TETAP SAMA) ---
        $expenseGroupHeader = ChartOfAccount::where('code', '5000')->where('header', 1)->first();
        $expenseGroupId = $expenseGroupHeader ? $expenseGroupHeader->group_id : 5;

        $transactionalCoas = ChartOfAccount::where('status', 1)
            ->where('header', 0)
            ->where('group_id', $expenseGroupId)
            ->orderBy('code')
            ->get()
            ->unique('code');

        $hierarchicalCoas = [];
        foreach ($transactionalCoas as $coa) {
            $nameParts = explode(' - ', $coa->name, 2);
            $groupTitle = trim($nameParts[0]);
            $detailName = isset($nameParts[1]) ? trim($nameParts[1]) : $groupTitle;
            $coa->detail_name = $detailName;
            if (!isset($hierarchicalCoas[$groupTitle])) {
                $hierarchicalCoas[$groupTitle] = [];
            }
            $hierarchicalCoas[$groupTitle][] = $coa;
        }
        ksort($hierarchicalCoas);

        // --- PERUBAHAN DI SINI ---
        // Ambil data Cost Center dari tabel rnc_centers yang aktif
        $costCenters = RncCenter::where('is_active', 1)->orderBy('nama_rnc')->get();
        // -------------------------

        return view('app-type.keuangan.ap-non-gr.partials.create', compact(
            'suppliers',
            'hierarchicalCoas',
            'costCenters' // Sekarang variabel ini berisi koleksi dari RncCenter
        ));
    }


    public function store(Request $request)
    {
        Log::debug('================ MEMULAI PROSES SIMPAN AP NON-PO ================');
        Log::debug('Request Data Mentah:', $request->all());

        $parseNumeric = function ($value) {
            if (is_numeric($value)) return $value;
            return (float) str_replace(['.', ','], ['', '.'], $value);
        };

        // BAGIAN 1: VALIDASI INPUT
        try {
            $validated = $request->validate([
                'tanggal_ap' => 'required|date_format:d-m-Y',
                'due_date' => 'required|date_format:d-m-Y|after_or_equal:tanggal_ap',
                'supplier_id' => 'required|exists:warehouse_supplier,id',
                'no_invoice_supplier' => 'required|string|max:100',
                'ppn_persen' => 'required|numeric|min:0|max:100', // Validasi persentase PPN
                'ppn_nominal' => 'required|numeric|min:0',
                'notes' => 'nullable|string',

                // Validasi untuk rincian detail
                'details' => 'required|array|min:1',
                'details.*.coa_id' => 'required|exists:chart_of_account,id',
                'details.*.cost_center_id' => 'required|exists:rnc_centers,id',
                'details.*.nominal' => 'required|numeric|min:1',
                'details.*.keterangan' => 'nullable|string',
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
            DB::transaction(function () use ($request, $parseNumeric, &$apHeader) {
                // BAGIAN 2: KALKULASI TOTAL DARI BACKEND
                Log::debug('Langkah 2: Melakukan kalkulasi.');

                // Parse nilai numerik
                $ppnPersen = $parseNumeric($request->ppn_persen);
                $ppnNominal = $parseNumeric($request->ppn_nominal);

                // Hitung subtotal dari detail
                $subtotal = collect($request->details)->sum(function ($detail) use ($parseNumeric) {
                    return $parseNumeric($detail['nominal']);
                });

                // Validasi konsistensi PPN
                $calculatedPpn = ($subtotal * $ppnPersen) / 100;
                if (abs($calculatedPpn - $ppnNominal) > 1) { // Toleransi selisih 1
                    throw new \Exception("Nilai PPN tidak konsisten. Hitung ulang PPN.");
                }

                $grandTotal = $subtotal + $ppnNominal;

                Log::debug('Hasil Kalkulasi:', [
                    'subtotal' => $subtotal,
                    'ppn_persen' => $ppnPersen,
                    'ppn_nominal' => $ppnNominal,
                    'grandTotal' => $grandTotal
                ]);

                // BAGIAN 3: SIMPAN DATA KE HEADER UTAMA
                $headerData = [
                    'kode_ap' => $this->generateApCode(),
                    'ap_type' => 'Non-PO',
                    'supplier_id' => $request->supplier_id,
                    'no_invoice_supplier' => $request->no_invoice_supplier,
                    'tanggal_ap' => Carbon::createFromFormat('d-m-Y', $request->tanggal_ap),
                    'due_date' => Carbon::createFromFormat('d-m-Y', $request->due_date),
                    'no_faktur_pajak' => $request->no_faktur_pajak,
                    'tanggal_faktur_pajak' => $request->tanggal_faktur_pajak ?
                        Carbon::createFromFormat('d-m-Y', $request->tanggal_faktur_pajak) : null,
                    'subtotal' => $subtotal,
                    'ppn_persen' => $ppnPersen,
                    'ppn_nominal' => $ppnNominal,
                    'grand_total' => $grandTotal,
                    'sisa_hutang' => $grandTotal,
                    'notes' => $request->notes,
                    'status_pembayaran' => 'Belum Lunas',
                    'user_entry_id' => auth()->id(),
                    'diskon_final' => 0,
                    'retur' => 0,
                    'materai' => 0,
                    'biaya_lainnya' => 0,
                    // Dokumen pendukung (default false)
                    'ada_kwitansi' => 0,
                    'ada_faktur_pajak' => $request->no_faktur_pajak ? 1 : 0,
                    'ada_surat_jalan' => 0,
                    'ada_salinan_po' => 0,
                    'ada_tanda_terima_barang' => 0,
                    'ada_berita_acara' => 0,
                ];

                Log::debug('Langkah 3: Data untuk ApSupplierHeader::create():', $headerData);
                $apHeader = ApSupplierHeader::create($headerData);
                Log::debug('ApSupplierHeader (Non-PO) berhasil dibuat dengan ID: ' . $apHeader->id);

                // BAGIAN 4: SIMPAN RINCIAN DETAIL
                Log::debug('Langkah 4: Memulai loop untuk menyimpan ApNonGrnDetails.');
                foreach ($request->details as $detail) {
                    $detailData = [
                        'coa_id' => $detail['coa_id'],
                        'cost_center_id' => $detail['cost_center_id'],
                        'nominal' => $parseNumeric($detail['nominal']),
                        'keterangan' => $detail['keterangan'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $apHeader->NonGrnDetails()->create($detailData);
                }
                Log::debug('Semua detail Non-PO berhasil disimpan.');

                // BAGIAN 5: LOGGING FINAL
                Log::debug('Data AP Non-PO berhasil disimpan:', [
                    'header_id' => $apHeader->id,
                    'total_details' => count($request->details),
                    'grand_total' => $grandTotal
                ]);
            });
            Log::debug('DB Transaction Selesai dengan Sukses.');
        } catch (\Exception $e) {
            Log::error('!!! EXCEPTION SAAT MENYIMPAN AP NON-PO !!!');
            Log::error('Pesan Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan fatal saat menyimpan data. Pesan: ' . $e->getMessage())
                ->withInput();
        }

        // BAGIAN 5: REDIRECT SETELAH SUKSES
        Log::debug('Proses selesai, redirecting ke halaman index.');
        return redirect()->route('keuangan.ap-non-gr.index')
            ->with('success', 'AP Non-PO berhasil dibuat dengan kode: ' . $apHeader->kode_ap)
            ->with('new_ap_id', $apHeader->id);
    }

    public function show($id)
    {
        $apNonGrn = ApSupplierHeader::with([
            'supplier',
            'userEntry',
            'nonPoDetails.coa',  // Ubah ke nama relasi yang konsisten
            'nonPoDetails.costCenter'
        ])
            ->where('ap_type', 'Non-PO')
            ->findOrFail($id);

        // 2. Ambil semua data yang dibutuhkan untuk mengisi dropdown di form
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        $transactionalCoas = ChartOfAccount::where('status', 1)
            ->where('header', 0)
            ->orderBy('code')
            ->get();

        // Group COA
        $grupCoa = GroupChartOfAccount::orderBy('name')->get();
        $groupedCoaDetails = $transactionalCoas->groupBy('group_id');

        $costCenters = RncCenter::where('is_active', 1)->orderBy('nama_rnc')->get();

        // 3. Kirim semua data ke view (perbaiki nama view dengan menghilangkan spasi)
        return view('app-type.keuangan.ap-non-gr.partials.details', compact(
            'apNonGrn',
            'suppliers',
            'grupCoa',
            'groupedCoaDetails',
            'costCenters',
            'transactionalCoas' // Ditambahkan untuk memastikan tersedia
        ));
    }
    // Pastikan method destroy Anda sudah siap (dari jawaban sebelumnya)
    public function destroy($id)
    {
        try {
            $apSupplier = ApSupplierHeader::where('ap_type', 'Non-PO')->findOrFail($id);

            if ($apSupplier->status_pembayaran !== 'Belum Lunas') {
                return redirect()->back()
                    ->with('error', 'Gagal membatalkan. AP ' . $apSupplier->kode_ap . ' sudah dalam proses pembayaran atau lunas.');
            }

            $kodeAp = $apSupplier->kode_ap;

            DB::transaction(function () use ($apSupplier) {
                $apSupplier->NonGrnDetails()->delete();
                $apSupplier->delete();
            });

            return redirect()->route('keuangan.ap-non-gr.index')
                ->with('success', 'AP Non-PO ' . $kodeAp . ' berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Gagal membatalkan AP Non-PO: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membatalkan AP Non-PO. Terjadi kesalahan sistem.');
        }
    }

    /**
     * Menampilkan form untuk mengedit AP Non-PO.
     * (Implementasi edit bisa ditambahkan di sini)
     */
    public function edit($id)
    {
        // Logika untuk mengambil data dan menampilkannya di form edit
    }

    public function update(Request $request, $id)
    {
        // Logika untuk validasi dan update data
    }

    public function print(ApSupplierHeader $apSupplier)
    {
        $apSupplier->load(['supplier', 'details.penerimaanBarang.po']);

        $lineItems = collect();

        if ($apSupplier->details->isNotEmpty()) {
            $groupedByPO = $apSupplier->details->groupBy(function ($detail) {
                return $detail->penerimaanBarang?->po?->kode_po ?? 'PO Tidak Terkait';
            });


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
        return view('app-type.keuangan.ap-non-gr.print.invoice', compact('apSupplier', 'lineItems'));
    }

    private function generateApCode()
    {
        $prefix = 'APN-' . date('ym') . '-';
        $lastAp = ApSupplierHeader::where('kode_ap', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $lastAp ? ((int) substr($lastAp->kode_ap, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
