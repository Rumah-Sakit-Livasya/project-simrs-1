<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\PettyCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PettyCashController extends Controller
{
    /**
     * Menampilkan daftar petty cash.
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('petty_cash as pc')
                ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
                ->leftJoin('users as u', 'pc.user_id', '=', 'u.id')
                ->select([
                    'pc.id',
                    'pc.tanggal',
                    'pc.kode_transaksi',
                    'pc.keterangan',
                    'pc.status',
                    'pc.total_nominal',
                    'bp.nama as kas_nama',
                    'u.name as user_name',
                    'pc.created_at'
                ]);

            if ($request->filled('tanggal_awal')) {
                $query->where('pc.tanggal', '>=', $request->tanggal_awal);
            }
            if ($request->filled('tanggal_akhir')) {
                $query->where('pc.tanggal', '<=', $request->tanggal_akhir);
            }
            if ($request->filled('keterangan')) {
                $query->where('pc.keterangan', 'like', '%' . $request->keterangan . '%');
            }
            if ($request->filled('kas_id')) {
                $query->where('pc.kas_id', $request->kas_id);
            }
            if ($request->filled('biaya_id')) {
                $query->whereExists(function ($subQuery) use ($request) {
                    $subQuery->select(DB::raw(1))
                        ->from('petty_cash_detail as pcd')
                        ->whereColumn('pcd.petty_cash_id', 'pc.id')
                        ->where('pcd.coa_id', $request->biaya_id);
                });
            }

            $pettycash = $query->orderBy('pc.tanggal', 'desc')
                ->orderBy('pc.created_at', 'desc')
                ->get();

            $detailsForJs = [];
            $allIds = $pettycash->pluck('id');

            if ($allIds->isNotEmpty()) {
                $allDetails = DB::table('petty_cash_detail as pcd')
                    ->leftJoin('chart_of_account as coa', 'pcd.coa_id', '=', 'coa.id')
                    ->select([
                        'pcd.petty_cash_id',
                        'coa.name as tipe_transaksi',
                        'pcd.keterangan',
                        'pcd.nominal',
                    ])
                    ->whereIn('pcd.petty_cash_id', $allIds)
                    ->get()
                    ->groupBy('petty_cash_id');

                foreach ($allDetails as $id => $details) {
                    $detailsForJs[$id] = $details->map(function ($item) {
                        $item->nominal_formatted = 'Rp ' . number_format($item->nominal, 0, ',', '.');
                        return $item;
                    });
                }
            }

            // Load data for dropdowns
            $kass = DB::table('bank_perusahaan')
                ->where('is_aktivasi', 1)
                ->select('id', 'nama')
                ->orderBy('nama')
                ->get();

            $coas = DB::table('chart_of_account')
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get();

            return view('app-type.keuangan.petty-cash.index', compact('pettycash', 'detailsForJs', 'kass', 'coas'));
        } catch (Exception $e) {
            Log::error("Error loading petty cash index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Halaman create.
     */
    public function create()
    {
        try {
            $kass = DB::table('bank_perusahaan')
                ->where('is_aktivasi', 1)
                ->select('id', 'nama as nama_bank')
                ->orderBy('nama')
                ->get();

            $coas = DB::table('chart_of_account')
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get();

            $costCenters = DB::table('rnc_centers')
                ->where('is_active', 1)
                ->select('id', 'nama_rnc')
                ->orderBy('nama_rnc')
                ->get();

            return view('app-type.keuangan.petty-cash.create', compact('kass', 'coas', 'costCenters'));
        } catch (Exception $e) {
            Log::error("Error loading create petty cash page: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman: ' . $e->getMessage());
        }
    }

    public function getKasSaldo($id)
    {
        try {
            // Asumsi tabel 'bank_perusahaan' punya kolom 'saldo'
            $kas = DB::table('bank_perusahaan')->where('id', $id)->first();

            if ($kas && isset($kas->saldo)) {
                return response()->json([
                    'success' => true,
                    'saldo' => $kas->saldo,
                    'saldo_formatted' => 'Rp ' . number_format($kas->saldo, 0, ',', '.')
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Saldo tidak ditemukan'], 404);
            }
        } catch (Exception $e) {
            Log::error("Error getting kas saldo: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server'], 500);
        }
    }


    /**
     * Simpan data petty cash baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_ap' => 'required|date_format:d-m-Y',
            'kas_id' => 'required|exists:bank_perusahaan,id',
            'keterangan' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.coa_id' => 'required|exists:chart_of_account,id',
            'details.*.cost_center_id' => 'required|exists:rnc_centers,id',
            'details.*.nominal' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total nominal dari detail
            $totalNominal = collect($request->details)->sum('nominal');

            // Insert ke petty_cash
            $pettyCashId = DB::table('petty_cash')->insertGetId([
                'kode_transaksi' => $this->generateTransactionCode(),
                'tanggal' => Carbon::createFromFormat('d-m-Y', $request->tanggal_ap)->format('Y-m-d'),
                'kas_id' => $request->kas_id,
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan,
                'total_nominal' => $totalNominal,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert ke petty_cash_detail
            $detailsToInsert = [];
            foreach ($request->details as $detail) {
                $detailsToInsert[] = [
                    'petty_cash_id' => $pettyCashId,
                    'coa_id' => $detail['coa_id'],
                    'cost_center_id' => $detail['cost_center_id'],
                    'keterangan' => $detail['keterangan'] ?? null,
                    'nominal' => $detail['nominal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('petty_cash_detail')->insert($detailsToInsert);

            // Kurangi saldo kas di bank_perusahaan
            DB::table('bank_perusahaan')
                ->where('id', $request->kas_id)
                ->decrement('saldo', $totalNominal);

            DB::commit();
            return redirect()->route('keuangan.petty-cash.index')->with('success', 'Transaksi berhasil disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error storing petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        try {
            // Bagian Query (tetap sama)
            $query = DB::table('petty_cash as pc')
                ->leftJoin('users as u', 'pc.user_id', '=', 'u.id')
                ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
                ->select(
                    'pc.id',
                    'pc.tanggal',
                    'pc.kode_transaksi',
                    'pc.keterangan',
                    'pc.total_nominal',
                    'u.name as user_name',
                    'bp.nama as kas_nama',
                    'pc.kas_id',
                    'pc.created_at'
                );

            $tanggalAwal = $request->filled('tanggal_awal') ? Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay() : null;
            $tanggalAkhir = $request->filled('tanggal_akhir') ? Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay() : null;

            if ($tanggalAwal) $query->where('pc.tanggal', '>=', $tanggalAwal->format('Y-m-d'));
            if ($tanggalAkhir) $query->where('pc.tanggal', '<=', $tanggalAkhir->format('Y-m-d'));
            if ($request->filled('keterangan')) $query->where('pc.keterangan', 'like', '%' . $request->keterangan . '%');
            if ($request->filled('kas_id')) $query->where('pc.kas_id', $request->kas_id);

            $reports = $query->orderBy('pc.tanggal', 'asc')->orderBy('pc.created_at', 'asc')->get();

            $isKasFiltered = $request->filled('kas_id');

            // Logika Perhitungan Saldo Berjalan (Metode Mundur)
            if ($isKasFiltered && $reports->isNotEmpty()) {
                $kasId = $request->kas_id;

                // 1. Ambil SALDO SAAT INI (Source of Truth, seperti di getKasSaldo).
                $saldoSaatIni = (float) DB::table('bank_perusahaan')->where('id', $kasId)->value('saldo');

                // 2. Hitung total pengeluaran yang terjadi SETELAH periode laporan berakhir.
                $totalPengeluaranSetelahPeriode = 0;
                if ($tanggalAkhir) {
                    $totalPengeluaranSetelahPeriode = (float) DB::table('petty_cash')
                        ->where('kas_id', $kasId)
                        ->where('tanggal', '>', $tanggalAkhir->format('Y-m-d'))
                        ->sum('total_nominal');
                }

                // 3. Hitung saldo tepat pada AKHIR PERIODE LAPORAN.
                $saldoDiAkhirPeriode = $saldoSaatIni + $totalPengeluaranSetelahPeriode;

                // 4. Hitung saldo berjalan dengan bekerja MUNDUR dari titik ini.
                $runningBalance = $saldoDiAkhirPeriode;

                // Proses koleksi secara terbalik (dari transaksi terbaru ke terlama).
                foreach ($reports->reverse() as $report) {
                    // Saldo akhir untuk baris ini adalah saldo berjalan saat ini.
                    $report->saldo_akhir = $runningBalance;
                    // Untuk mendapatkan saldo SEBELUM transaksi ini, tambahkan kembali nominalnya.
                    $runningBalance += (float) $report->total_nominal;
                }

                // Balikkan kembali koleksi ke urutan kronologis untuk ditampilkan.
                $reports = $reports->reverse();
            } else {
                foreach ($reports as $report) {
                    $report->saldo_akhir = null;
                }
            }

            $kass = DB::table('bank_perusahaan')->where('is_aktivasi', 1)->select('id', 'nama')->orderBy('nama')->get();

            return view('app-type.keuangan.petty-cash.report', compact('reports', 'kass', 'isKasFiltered'));
        } catch (Exception $e) {
            Log::error("Error loading petty cash report: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }


    public function exportReport(Request $request)
    {
        $query = DB::table('petty_cash as pc')
            ->leftJoin('users as u', 'pc.user_id', '=', 'u.id')
            ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
            ->select(
                'pc.kode_transaksi',
                'pc.tanggal',
                'pc.keterangan',
                'pc.total_nominal',
                'u.name as user_name',
                'bp.nama as kas_nama'
            );

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $tanggalAkhir = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
            $query->whereBetween('pc.tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        if ($request->filled('keterangan')) {
            $query->where('pc.keterangan', 'like', '%' . $request->keterangan . '%');
        }

        if ($request->filled('kas_id')) {
            $query->where('pc.kas_id', $request->kas_id);
        }

        $data = $query->orderBy('pc.tanggal', 'desc')->get();

        // Export ke Excel pakai laravel-excel
        return \Excel::download(new \App\Exports\PettyCashReportExport($data), 'report_petty_cash.xlsx');
    }



    /**
     * Tampilkan detail transaksi.
     */
    public function show($id)
    {
        try {
            $pettycash = DB::table('petty_cash as pc')
                ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
                ->leftJoin('users as u', 'pc.user_id', '=', 'u.id')
                ->select('pc.*', 'bp.nama as kas_nama', 'u.name as user_name')
                ->where('pc.id', $id)
                ->first();

            $details = DB::table('petty_cash_detail as pcd')
                ->leftJoin('chart_of_account as coa', 'pcd.coa_id', '=', 'coa.id')
                ->leftJoin('rnc_centers as cc', 'pcd.cost_center_id', '=', 'cc.id')
                ->select('pcd.*', 'coa.name', 'cc.nama_rnc')
                ->where('pcd.petty_cash_id', $id)
                ->get();

            return view('app-type.keuangan.petty-cash.show', compact('pettycash', 'details'));
        } catch (Exception $e) {
            Log::error("Error showing petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat detail transaksi');
        }
    }

    public function edit($id)
    {
        try {
            // Ambil data petty cash utama menggunakan Query Builder
            $pettycash = DB::table('petty_cash')->where('id', $id)->first();

            // Tambahkan pengecekan jika data tidak ditemukan
            if (!$pettycash) {
                return redirect()->route('keuangan.petty-cash.index')->with('error', 'Transaksi tidak ditemukan.');
            }

            // Kode selanjutnya tetap sama
            $details = DB::table('petty_cash_detail')->where('petty_cash_id', $pettycash->id)->get();
            $kass = DB::table('bank_perusahaan')->where('is_aktivasi', 1)->select('id', 'nama')->get();
            $coas = DB::table('chart_of_account')->select('id', 'code', 'name')->get();
            $costCenters = DB::table('rnc_centers')->where('is_active', 1)->select('id', 'nama_rnc')->get();

            return view('app-type.keuangan.petty-cash.edit', compact('pettycash', 'details', 'kass', 'coas', 'costCenters'));
        } catch (Exception $e) {
            Log::error("Error loading edit petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman edit');
        }
    }

    /**
     * Cetak Jurnal
     * ---
     * DIGANTI: Dari (PettyCash $pettycash) menjadi ($id)
     * ---
     */
    public function cetakJurnal($id)
    {
        try {
            // Ambil data petty cash utama dengan join ke tabel lain menggunakan Query Builder
            $pettycash = DB::table('petty_cash as pc')
                ->leftJoin('users as u', 'pc.user_id', '=', 'u.id')
                ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
                ->select(
                    'pc.*',
                    'u.name as user_name',
                    'bp.nama as kas_nama',
                )
                ->where('pc.id', $id)
                ->first();

            if (!$pettycash) {
                return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
            }

            // Get details with proper COA and cost center info
            $details = DB::table('petty_cash_detail as pcd')
                ->leftJoin('chart_of_account as coa', 'pcd.coa_id', '=', 'coa.id')
                ->leftJoin('rnc_centers as cc', 'pcd.cost_center_id', '=', 'cc.id')
                ->select(
                    'pcd.*',
                    'coa.code as coa_code',
                    'coa.name as coa_name',
                    'cc.nama_rnc'
                )
                ->where('pcd.petty_cash_id', $pettycash->id)
                ->get();

            // Calculate totals for balance verification
            $totalDebet = $details->sum('nominal');
            $totalKredit = $pettycash->total_nominal;

            return view('app-type.keuangan.petty-cash.print-jurnal', compact('pettycash', 'details', 'totalDebet', 'totalKredit'));
        } catch (Exception $e) {
            Log::error("Error printing jurnal for Petty Cash ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat dokumen cetak jurnal: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Voucher Pengeluaran
     * ---
     * DIGANTI: Dari (PettyCash $pettycash) menjadi ($id) dan perbaikan parameter
     * ---
     */
    /**
     * Cetak Voucher Pengeluaran
     * ---
     * FIXED: Changed 'kas' table reference to 'bank_perusahaan' to match other methods
     * ---
     */
    public function cetakVoucher($id)
    {
        try {
            // Ambil header petty cash - FIXED: Changed from 'kas' to 'bank_perusahaan'
            $pettycash = DB::table('petty_cash as pc')
                ->leftJoin('bank_perusahaan as bp', 'pc.kas_id', '=', 'bp.id')
                ->select(
                    'pc.id',
                    'pc.kode_transaksi',
                    'pc.tanggal',
                    'pc.status',
                    'pc.total_nominal',
                    'bp.nama as kas_nama'  // Changed from 'k.nama' to 'bp.nama'
                )
                ->where('pc.id', $id)
                ->first();

            if (!$pettycash) {
                return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
            }

            // Ambil detail petty cash - FIXED: Changed from 'coa' to 'chart_of_account'
            $details = DB::table('petty_cash_detail as pcd')
                ->leftJoin('chart_of_account as coa', 'pcd.coa_id', '=', 'coa.id')
                ->select(
                    'pcd.id',
                    'pcd.keterangan',
                    'pcd.nominal',
                    'coa.code as coa_code',    // Changed from 'c.coa_code' to 'coa.code'
                    'coa.name as coa_name'     // Changed from 'c.coa_name' to 'coa.name'
                )
                ->where('pcd.petty_cash_id', $id)
                ->get();

            // Hitung total nominal
            $totalAmount = $details->sum('nominal');

            // Konversi angka ke terbilang
            $terbilang = terbilangRp($totalAmount, false);

            // Kirim ke view
            return view(
                'app-type.keuangan.petty-cash.print-voucher',
                compact('pettycash', 'details', 'totalAmount', 'terbilang')
            );
        } catch (Exception $e) {
            Log::error("Error printing voucher for Petty Cash ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat dokumen cetak voucher: ' . $e->getMessage());
        }
    }


    /**
     * Halaman edit.
     */

    /**
     * Update transaksi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_ap' => 'required|date_format:d-m-Y',
            'kas_id' => 'required|exists:bank_perusahaan,id',
            'keterangan' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.coa_id' => 'required|exists:chart_of_account,id',
            'details.*.cost_center_id' => 'required|exists:rnc_centers,id',
            'details.*.nominal' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data lama
            $oldPettyCash = DB::table('petty_cash')->where('id', $id)->first();
            if (!$oldPettyCash) {
                return redirect()->route('keuangan.petty-cash.index')->with('error', 'Data tidak ditemukan');
            }

            $oldTotal = $oldPettyCash->total_nominal;
            $oldKasId = $oldPettyCash->kas_id;

            // Hitung total baru
            $totalNominal = collect($request->details)->sum('nominal');

            // Update petty cash
            DB::table('petty_cash')->where('id', $id)->update([
                'tanggal' => Carbon::createFromFormat('d-m-Y', $request->tanggal_ap)->format('Y-m-d'),
                'kas_id' => $request->kas_id,
                'keterangan' => $request->keterangan,
                'total_nominal' => $totalNominal,
                'updated_at' => now(),
            ]);

            // Hapus detail lama & simpan baru
            DB::table('petty_cash_detail')->where('petty_cash_id', $id)->delete();
            $detailsToInsert = [];
            foreach ($request->details as $detail) {
                $detailsToInsert[] = [
                    'petty_cash_id' => $id,
                    'coa_id' => $detail['coa_id'],
                    'keterangan' => $detail['keterangan'],
                    'cost_center_id' => $detail['cost_center_id'],
                    'nominal' => $detail['nominal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('petty_cash_detail')->insert($detailsToInsert);

            // Rollback saldo lama
            DB::table('bank_perusahaan')->where('id', $oldKasId)->increment('saldo', $oldTotal);

            // Kurangi saldo baru
            DB::table('bank_perusahaan')->where('id', $request->kas_id)->decrement('saldo', $totalNominal);

            DB::commit();
            return redirect()->route('keuangan.petty-cash.index')->with('success', 'Transaksi berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }


    /**
     * Hapus transaksi.
     */

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('petty_cash_detail')->where('petty_cash_id', $id)->delete();
            DB::table('petty_cash')->where('id', $id)->delete();

            DB::commit();
            return redirect()->route('keuangan.petty-cash.index')->with('success', 'Transaksi berhasil dihapus');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error deleting petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi');
        }
    }

    /**
     * Approve transaksi.
     */
    public function approve($id)
    {
        try {
            DB::table('petty_cash')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);

            return redirect()->route('keuangan.petty-cash.index')->with('success', 'Transaksi berhasil disetujui');
        } catch (Exception $e) {
            Log::error("Error approving petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui transaksi');
        }
    }

    /**
     * Reject transaksi.
     */
    public function reject($id)
    {
        try {
            DB::table('petty_cash')->where('id', $id)->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);

            return redirect()->route('keuangan.petty-cash.index')->with('success', 'Transaksi berhasil ditolak');
        } catch (Exception $e) {
            Log::error("Error rejecting petty cash: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak transaksi');
        }
    }

    /**
     * Helper: Generate kode transaksi unik.
     */
    private function generateTransactionCode()
    {
        $prefix = 'PC/' . date('Ym') . '/';
        $lastTransaction = DB::table('petty_cash')
            ->where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $newNumber = 1;
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->kode_transaksi, -4);
            $newNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
