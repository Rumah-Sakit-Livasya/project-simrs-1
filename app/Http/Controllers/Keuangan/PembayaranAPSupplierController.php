<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ApSupplierHeader;
use App\Models\Keuangan\Bank;
use App\Models\Keuangan\PembayaranApSupplierHeader;
use App\Models\WarehouseSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranAPSupplierController extends Controller
{
    /**
     * Menampilkan halaman utama daftar pembayaran AP Supplier.
     */
    // app/Http/Controllers/Keuangan/PembayaranAPSupplierController.php

    public function index(Request $request)
    {
        // 1. Memulai query dengan eager loading untuk performa yang lebih baik.
        // Kita memuat relasi 'supplier', 'kasBank', dan 'userEntry' agar tidak ada N+1 query problem.
        $query = PembayaranApSupplierHeader::with(['supplier', 'kasBank', 'userEntry']);

        // 2. Menerapkan filter berdasarkan input dari form pencarian.
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            try {
                // FIX: Konversi format tanggal dari 'd-m-Y' (jika datepicker menggunakan format itu) atau 'Y-m-d'.
                // Anggap saja datepicker Anda sudah mengirim format Y-m-d.
                $start = Carbon::parse($request->tanggal_awal)->startOfDay();
                $end = Carbon::parse($request->tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_pembayaran', [$start, $end]);
            } catch (\Exception $e) {
                Log::warning('Filter tanggal pembayaran AP gagal: ' . $e->getMessage());
                // Abaikan filter jika format tanggal salah.
            }
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('kode_payment')) {
            $query->where('kode_pembayaran', 'like', '%' . $request->kode_payment . '%');
        }

        // Tambahkan filter untuk no_invoice dan kode_ap (memerlukan join atau whereHas)
        if ($request->filled('no_invoice')) {
            $query->whereHas('details.apSupplier', function ($q) use ($request) {
                $q->where('no_invoice_supplier', 'like', '%' . $request->no_invoice . '%');
            });
        }

        if ($request->filled('kode_ap')) {
            $query->whereHas('details.apSupplier', function ($q) use ($request) {
                $q->where('kode_ap', 'like', '%' . $request->kode_ap . '%');
            });
        }

        // 3. Mengambil data hasil query dengan paginasi.
        // withQueryString() memastikan parameter filter tetap ada di link pagination.
        $payments = $query->latest('tanggal_pembayaran')->paginate(20)->appends($request->all());

        // 4. Mengambil data master untuk dropdown filter.
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // 5. Mengirim semua data yang diperlukan ke view.
        return view('app-type.keuangan.pembayaran-ap-supplier.index', compact(
            'payments',
            'suppliers'
        ));
    }
    /**
     * Menampilkan form untuk membuat pembayaran baru.
     */
    public function create()
    {
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();
        $bank = Bank::orderBy('name')->get();

        return view('app-type.keuangan.pembayaran-ap-supplier.partials.create', compact('suppliers', 'bank'));
    }

    /**
     * Menyimpan data pembayaran baru ke database.
     */
    // app/Http/Controllers/Keuangan/PembayaranAPSupplierController.php

    // app/Http/Controllers/Keuangan/PembayaranAPSupplierController.php

    public function store(Request $request)
    {
        // Validasi input tetap sama...
        $validated = $request->validate([
            'tanggal_pembayaran' => 'required|date_format:Y-m-d',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'kas_bank_id' => 'required|exists:banks,id',
            'invoices' => 'required|array|min:1',
            'invoices.*.id' => 'required|exists:ap_supplier_header,id',
            'invoices.*.pembayaran' => 'required|numeric|min:0',
            'invoices.*.potongan' => 'required|numeric|min:0',
            'invoices.*.biaya_lain' => 'required|numeric|min:0',
            'pembulatan' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // BAGIAN 1: KALKULASI TOTAL UNTUK HEADER PEMBAYARAN
            $totalPembayaranInvoice = 0;
            $totalPotongan = 0; // Tambahkan variabel ini
            $totalBiayaLain = 0;

            foreach ($validated['invoices'] as $invoiceData) {
                $totalPembayaranInvoice += (float) $invoiceData['pembayaran'];
                $totalPotongan += (float) $invoiceData['potongan']; // Hitung total potongan
                $totalBiayaLain += (float) $invoiceData['biaya_lain'];
            }
            $pembulatan = (float) $validated['pembulatan'];

            // =================================================================
            // PERBAIKAN FINAL DI SINI
            // LOGIKA BARU: Grand Total = (Pembayaran + Biaya Lain - Potongan) + Pembulatan
            $grandTotalPembayaran = ($totalPembayaranInvoice + $totalBiayaLain - $totalPotongan) + $pembulatan;
            // =================================================================

            // BAGIAN 2: MEMBUAT HEADER PEMBAYARAN
            $paymentHeader = PembayaranApSupplierHeader::create([
                'kode_pembayaran' => $this->generatePaymentCode(),
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'supplier_id' => $validated['supplier_id'],
                'kas_bank_id' => $validated['kas_bank_id'],
                'metode_pembayaran' => $request->metode_pembayaran,
                'no_referensi' => $request->no_referensi,
                'keterangan' => $request->keterangan,
                'total_pembayaran' => $totalPembayaranInvoice, // Ini adalah total alokasi untuk hutang
                'grand_total_pembayaran' => $grandTotalPembayaran, // Ini adalah total uang yang keluar (sudah dikurangi potongan)
                'pembulatan' => $pembulatan,
                'user_entry_id' => auth()->id(),
            ]);

            // BAGIAN 3: MEMPROSES DETAIL DAN MENGUPDATE INVOICE
            foreach ($validated['invoices'] as $invoiceData) {
                $pembayaran = (float) $invoiceData['pembayaran'];
                $potongan = (float) $invoiceData['potongan'];
                $biayaLain = (float) $invoiceData['biaya_lain'];

                if ($pembayaran <= 0 && $potongan <= 0 && $biayaLain <= 0) continue;

                $invoice = ApSupplierHeader::lockForUpdate()->find($invoiceData['id']);

                // =================================================================
                // PENGURANG SISA HUTANG = HANYA DARI NOMINAL PEMBAYARAN
                // INI TETAP SESUAI PERMINTAAN ANDA SEBELUMNYA
                $totalPengurangHutang = $pembayaran;
                // =================================================================

                // Validasi: Pastikan pengurang hutang tidak melebihi sisa hutang
                if ($totalPengurangHutang > $invoice->sisa_hutang + 0.01) {
                    throw new \Exception("Pembayaran untuk invoice {$invoice->kode_ap} (Rp {$pembayaran}) melebihi sisa hutang (Rp {$invoice->sisa_hutang}).");
                }

                // Buat detail pembayaran
                $paymentHeader->details()->create([
                    'ap_supplier_header_id' => $invoice->id,
                    'nominal_pembayaran' => $pembayaran,
                    'potongan' => $potongan,
                    'biaya_lain' => $biayaLain,
                ]);

                // Update sisa hutang invoice HANYA dengan `totalPengurangHutang`
                $invoice->sisa_hutang -= $totalPengurangHutang;

                // Update status pembayaran invoice
                if ($invoice->sisa_hutang <= 0.01) {
                    $invoice->sisa_hutang = 0;
                    $invoice->status_pembayaran = 'Lunas';
                } else {
                    $invoice->status_pembayaran = 'Lunas Sebagian';
                }
                $invoice->save();
            }

            DB::commit();
            return redirect()->route('keuangan.pembayaran-ap-supplier.index')
                ->with('success', 'Pembayaran berhasil disimpan: ' . $paymentHeader->kode_pembayaran);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal simpan pembayaran AP: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PembayaranApSupplierHeader $payment)
    {
        // Eager load relasi yang dibutuhkan
        $payment->load(['supplier', 'kasBank', 'details.apSupplier']);
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();
        $bank = Bank::orderBy('name')->get();

        $selectedInvoicesJson = $payment->details->map(function ($detail) {
            if (!$apSupplier = $detail->apSupplier) return null;

            // Sisa hutang awal = Sisa hutang saat ini di DB + nominal pembayaran dari detail ini
            $sisaHutangAwal = $apSupplier->sisa_hutang + $detail->nominal_pembayaran;

            return [
                'id' => $apSupplier->id,
                'kode_ap' => $apSupplier->kode_ap,
                'no_invoice_supplier' => $apSupplier->no_invoice_supplier,
                'grand_total' => (float) $apSupplier->grand_total,
                'sisa_hutang' => (float) $sisaHutangAwal, // Ini sisa hutang SEBELUM pembayaran ini dilakukan

                // Simpan nilai dari detail pembayaran ini untuk ditampilkan di input
                'pembayaran' => (float) $detail->nominal_pembayaran,
                'potongan'   => (float) $detail->potongan,
                'biaya_lain' => (float) $detail->biaya_lain,
            ];
        })->filter()->values()->toJson();

        // Pastikan view yang digunakan adalah 'edit' atau 'details' sesuai nama file Anda
        return view('app-type.keuangan.pembayaran-ap-supplier.partials.details', compact(
            'payment',
            'suppliers',
            'bank',
            'selectedInvoicesJson'
        ));
    }

    /**
     * Menampilkan halaman popup untuk memilih invoice. Ini adalah versi yang sudah diperbaiki.
     */
    public function pilihInvoice(Request $request)
    {
        // =========================================================================
        // BAGIAN 1: VALIDASI & PERSIAPAN AWAL
        // =========================================================================

        // Validasi input utama: supplier_id harus ada dan valid.
        $request->validate([
            'supplier_id' => 'required|integer|exists:warehouse_supplier,id'
        ]);

        $supplier = WarehouseSupplier::findOrFail($request->supplier_id);

        // =========================================================================
        // BAGIAN 2: MEMULAI QUERY DENGAN EAGER LOADING
        // =========================================================================

        // Memulai query pada model ApSupplierHeader.
        $query = ApSupplierHeader::query()
            // Filter wajib: hanya untuk supplier yang dipilih.
            ->where('supplier_id', $supplier->id)
            // Filter wajib: hanya invoice yang masih punya hutang.
            ->whereIn('status_pembayaran', ['Belum Lunas', 'Lunas Sebagian'])
            // EAGER LOAD RELASI PEMBAYARAN DETAIL (SANGAT PENTING UNTUK PERFORMA)
            // Ini akan mengambil semua detail pembayaran terkait dalam satu query.
            ->with('pembayaranDetails');

        // =========================================================================
        // BAGIAN 3: MENERAPKAN FILTER PENCARIAN TAMBAHAN (OPSIONAL)
        // =========================================================================

        // Filter berdasarkan rentang Due Date.
        if ($request->filled('due_date_awal') && $request->filled('due_date_akhir')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->due_date_awal)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->due_date_akhir)->endOfDay();
                $query->whereBetween('due_date', [$start, $end]);
            } catch (\Exception $e) {
                Log::warning('Format tanggal salah pada filter pilih invoice: ' . $e->getMessage());
            }
        }

        // Filter berdasarkan Nomor Invoice Supplier.
        if ($request->filled('no_invoice')) {
            $query->where('no_invoice_supplier', 'like', '%' . $request->no_invoice . '%');
        }

        // Filter berdasarkan Kode AP internal.
        if ($request->filled('kode_ap')) {
            $query->where('kode_ap', 'like', '%' . $request->kode_ap . '%');
        }



        $invoices = $query->orderBy('due_date', 'asc')->get();

        // Gunakan transform untuk memodifikasi setiap invoice dalam koleksi.
        $invoices->transform(function ($invoice) {

            $total_pembayaran_nominal = $invoice->pembayaranDetails->sum('nominal_pembayaran');
            $total_potongan = $invoice->pembayaranDetails->sum('potongan');

            $invoice->total_dibayar = $total_pembayaran_nominal;


            $invoice->sisa_hutang = $invoice->grand_total - $invoice->total_dibayar;

            $invoice->tanggal_ap_formatted = $invoice->tanggal_ap->format('d-m-Y');
            $invoice->due_date_formatted = $invoice->due_date->format('d-m-Y');

            return $invoice;
        });

        $invoices = $invoices->filter(function ($invoice) {
            return $invoice->sisa_hutang > 0.01; // Toleransi pembulatan
        });



        return view('app-type.keuangan.pembayaran-ap-supplier.partials.pilih-invoice', compact(
            'supplier',
            'invoices'
        ));
    }

    // ... method lain (update, destroy, dll.) ...


    /**
     * Fungsi helper untuk generate kode pembayaran yang unik.
     */
    private function generatePaymentCode()
    {
        $prefix = 'PAY-AP-' . date('ym') . '-';
        $last = PembayaranApSupplierHeader::where('kode_pembayaran', 'like', $prefix . '%')->latest('id')->first();
        $number = $last ? ((int) substr($last->kode_pembayaran, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }





    /**
     * Memperbarui data pembayaran di database.
     */
    public function update(Request $request, PembayaranApSupplierHeader $payment)
    {
        // Validasi data (mirip dengan store)
        $request->validate([
            'tanggal_pembayaran' => 'required|date_format:Y-m-d',
            'supplier_id' => 'required|exists:warehouse_supplier,id',
            'kas_bank_id' => 'required|exists:banks,id',
            'metode_pembayaran' => 'required|string',
            'invoices' => 'required|array|min:1',
            'invoices.*.id' => 'required|exists:ap_supplier_header,id',
            'invoices.*.pembayaran' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Kembalikan semua nilai pembayaran & potongan lama ke sisa hutang invoice
            foreach ($payment->details as $oldDetail) {
                if ($invoice = $oldDetail->apSupplier) {
                    $invoice->sisa_hutang += ($oldDetail->nominal_pembayaran + $oldDetail->potongan);
                    // Set status kembali ke 'Belum Lunas' atau 'Lunas Sebagian' jika perlu
                    if ($invoice->sisa_hutang > 0) {
                        $invoice->status_pembayaran = $invoice->sisa_hutang < $invoice->grand_total ? 'Lunas Sebagian' : 'Belum Lunas';
                    }
                    $invoice->save();
                }
            }

            // 2. Hapus semua detail pembayaran yang lama
            $payment->details()->delete();

            // 3. Hitung total pembayaran baru
            $totalPembayaranBaru = collect($request->invoices)->sum(fn($invoice) => (float)$invoice['pembayaran']);

            // 4. Update header pembayaran
            $payment->update([
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
                'supplier_id' => $request->supplier_id,
                'kas_bank_id' => $request->kas_bank_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'no_referensi' => $request->no_referensi,
                'keterangan' => $request->keterangan,
                'total_pembayaran' => $totalPembayaranBaru,
                'pembulatan' => $request->pembulatan ?? 0,
                // user_update_id bisa ditambahkan jika ada kolomnya
            ]);

            // 5. Buat ulang detail pembayaran & update sisa hutang invoice (logika sama seperti store)
            foreach ($request->invoices as $invoiceData) {
                $pembayaran = (float)($invoiceData['pembayaran'] ?? 0);
                $potongan = (float)($invoiceData['potongan'] ?? 0);

                if ($pembayaran <= 0 && $potongan <= 0) continue;

                $invoice = ApSupplierHeader::lockForUpdate()->find($invoiceData['id']);

                if (($pembayaran + $potongan) > $invoice->sisa_hutang + 0.01) {
                    throw new \Exception("Pembayaran untuk invoice {$invoice->kode_ap} melebihi sisa hutang.");
                }

                $payment->details()->create([
                    'ap_supplier_header_id' => $invoice->id,
                    'nominal_pembayaran' => $pembayaran,
                    'potongan' => $potongan,
                ]);

                $invoice->sisa_hutang -= ($pembayaran + $potongan);

                if ($invoice->sisa_hutang <= 0.01) {
                    $invoice->sisa_hutang = 0;
                    $invoice->status_pembayaran = 'Lunas';
                } else {
                    $invoice->status_pembayaran = 'Lunas Sebagian';
                }
                $invoice->save();
            }

            DB::commit();
            return redirect()->route('keuangan.pembayaran-ap-supplier.index')
                ->with('success', 'Pembayaran ' . $payment->kode_pembayaran . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update pembayaran AP: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(PembayaranApSupplierHeader $payment)
    {
        DB::beginTransaction();
        try {
            foreach ($payment->details as $detail) {
                $invoice = ApSupplierHeader::lockForUpdate()->find($detail->ap_supplier_header_id);

                if ($invoice) {
                    $invoice->sisa_hutang += $detail->nominal_pembayaran;

                    if (abs($invoice->sisa_hutang - $invoice->grand_total) < 0.01) {
                        $invoice->status_pembayaran = 'Belum Lunas';
                    } else {
                        $invoice->status_pembayaran = 'Lunas Sebagian';
                    }

                    $invoice->save();
                }
            }

            // =========================================================================
            // LANGKAH 2: HAPUS DATA PEMBAYARAN SECARA PERMANEN
            // =========================================================================

            // PERUBAHAN UTAMA: Gunakan forceDelete() untuk penghapusan permanen.

            // Hapus dulu semua "anak" (detail pembayaran) untuk menghindari error foreign key.
            $payment->details()->forceDelete();

            // Setelah semua anak dihapus, baru hapus "induk" (header pembayaran).
            $payment->forceDelete();

            // =========================================================================
            // LANGKAH 3: COMMIT TRANSAKSI & KIRIM RESPON SUKSES
            // =========================================================================
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibatalkan dan dihapus secara permanen.',
                'redirect_url' => route('keuangan.pembayaran-ap-supplier.index')
            ]);
        } catch (\Exception $e) {
            // =========================================================================
            // LANGKAH 4: JIKA TERJADI ERROR, BATALKAN SEMUA & KIRIM RESPON GAGAL
            // =========================================================================
            DB::rollBack();
            Log::error("Gagal batalkan pembayaran AP: {$payment->kode_pembayaran}. Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pembayaran: ' . $e->getMessage()
            ], 500); // Kode status 500 Internal Server Error
        }
    }
}
