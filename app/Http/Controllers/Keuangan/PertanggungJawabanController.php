<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Pencairan;
use App\Models\Keuangan\Pertanggungjawaban;
use App\Models\Keuangan\RncCenter;
use App\Models\Keuangan\TransaksiRutin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class PertanggungJawabanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar dengan relasi
        $query = Pertanggungjawaban::with([
            // Relasi yang sudah ada tetap dipertahankan
            'pencairan.pengajuan.pengaju',
            'userEntry',

            // ================== PERUBAHAN DI SINI ==================
            // Kita muat relasi 'details', dan di dalamnya kita muat lagi relasi
            // 'transaksiRutin' dan 'rncCenter' dari setiap detail.
            'details.transaksiRutin',
            'details.rncCenter'
            // =======================================================
        ]);

        // Filter berdasarkan request (Tidak ada perubahan di bagian filter)
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $tanggalAkhir = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();

            $query->whereBetween('tanggal_pj', [$tanggalAwal, $tanggalAkhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $tanggalAwal = Carbon::createFromFormat('d-m-Y', $request->tanggal_awal)->startOfDay();
            $query->where('tanggal_pj', '>=', $tanggalAwal);
        } elseif ($request->filled('tanggal_akhir')) {
            $tanggalAkhir = Carbon::createFromFormat('d-m-Y', $request->tanggal_akhir)->endOfDay();
            $query->where('tanggal_pj', '<=', $tanggalAkhir);
        }

        if ($request->filled('kode_pjawab')) {
            $query->where('kode_pj', 'like', '%' . $request->kode_pjawab . '%');
        }

        if ($request->filled('kode_pencairan')) {
            $query->whereHas('pencairan', function ($q) use ($request) {
                $q->where('kode_pencairan', 'like', '%' . $request->kode_pencairan . '%');
            });
        }

        if ($request->filled('kode-pengaju')) {
            $query->whereHas('pencairan.pengajuan.pengaju', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('kode-pengaju') . '%')
                    ->orWhere('email', 'like', '%' . $request->input('kode-pengaju') . '%');
            });
        }

        $pertanggungjawabans = $query->latest()->get();

        // Jika request AJAX, return JSON (Sudah benar, tidak perlu diubah)
        if ($request->ajax()) {
            return response()->json($pertanggungjawabans);
        }

        // Jika request biasa, return view (Sudah benar, tidak perlu diubah)
        return view('app-type.keuangan.cash-advance.pertanggungjawaban', compact('pertanggungjawabans'));
    }
    public function pjawabanCreate(Request $request)
    {
        $tipe_transaksis = TransaksiRutin::where('is_active', true)
            ->get(['id', 'nama_transaksi']);

        $cost_centers = RncCenter::where('is_active', true)
            ->get(['id', 'nama_rnc']);

        return view('app-type.keuangan.cash-advance.pertanggung-jawaban.create', compact('tipe_transaksis', 'cost_centers'));
    }

    public function dataPencairanPopup()
    {
        $pencairans = Pencairan::with(['pengajuan.pengaju', 'pertanggungjawaban'])
            ->get()
            ->filter(function ($pencairan) {
                // Hitung total yang telah dipertanggungjawabkan
                $totalPj = $pencairan->pertanggungjawaban->sum('total_pj');

                // Tampilkan hanya jika masih ada sisa yang belum dipertanggungjawabkan
                return $totalPj < $pencairan->nominal_pencairan;
            })
            ->map(function ($pencairan) {
                // Tambahkan properti calculated untuk frontend
                $pencairan->total_telah_dipertanggungjawabkan = $pencairan->pertanggungjawaban->sum('total_pj');
                $pencairan->sisa_yang_belum_dipertanggungjawabkan = $pencairan->nominal_pencairan - $pencairan->total_telah_dipertanggungjawabkan;
                return $pencairan;
            });

        return view('app-type.keuangan.cash-advance.pertanggung-jawaban.pencairan-popup', compact('pencairans'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'tanggal_pj' => 'required|date',
            'pencairan_id' => 'required|exists:pencairans,id',
            'details' => 'required|array|min:1',
            'details.*.transaksi_rutin_id' => 'required|exists:transaksi_rutins,id',
            'details.*.rnc_center_id' => 'required|exists:rnc_centers,id',
            'details.*.keterangan' => 'required|string|max:255',
            'details.*.nominal' => 'required|numeric|min:1',
            // 'reimburse_details' => 'sometimes|array',
            // 'reimburse_details.*.transaksi_rutin_id' => 'required_with:reimburse_details|exists:transaksi_rutins,id',
            // 'reimburse_details.*.rnc_center_id' => 'required_with:reimburse_details|exists:rnc_centers,id',
            // 'reimburse_details.*.keterangan' => 'required_with:reimburse_details|string|max:255',
            // 'reimburse_details.*.nominal' => 'required_with:reimburse_details|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data Anda.');
        }

        DB::beginTransaction();
        try {
            $pencairan = Pencairan::findOrFail($request->pencairan_id);

            // Hitung total pertanggungjawaban
            $totalPj = collect($request->details)->sum('nominal');
            $totalReimburse = collect($request->reimburse_details ?? [])->sum('nominal');
            $selisih = $pencairan->nominal_pencairan - $totalPj + $totalReimburse;

            // Buat record pertanggungjawaban
            $pj = Pertanggungjawaban::create([
                'kode_pj' => $this->generateKodePj(),
                'tanggal_pj' => $request->tanggal_pj,
                'pencairan_id' => $pencairan->id,
                'total_pj' => $totalPj,
                'total_reimburse' => $totalReimburse,
                'selisih' => $selisih,
                'status' => 'pending',
                'user_entry_id' => Auth::id(),
            ]);

            // Simpan detail pertanggungjawaban
            foreach ($request->details as $detail) {
                $pj->details()->create([
                    'tipe' => 'pj',
                    'transaksi_rutin_id' => $detail['transaksi_rutin_id'],
                    'rnc_center_id' => $detail['rnc_center_id'],
                    'keterangan' => $detail['keterangan'],
                    'nominal' => $detail['nominal'],
                ]);
            }

            // Simpan detail reimburse jika ada
            if ($request->has('reimburse_details')) {
                foreach ($request->reimburse_details as $detail) {
                    $pj->details()->create([
                        'tipe' => 'reimburse',
                        'transaksi_rutin_id' => $detail['transaksi_rutin_id'],
                        'rnc_center_id' => $detail['rnc_center_id'],
                        'keterangan' => $detail['keterangan'],
                        'nominal' => $detail['nominal'],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('keuangan.cash-advance.pertanggung-jawaban')
                ->with('success', 'Pertanggungjawaban berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function generateKodePj()
    {
        $prefix = 'ADVS' . date('y') . '-';
        $last = Pertanggungjawaban::where('kode_pj', 'like', $prefix . '%')
            ->latest('kode_pj')
            ->first();

        $number = !$last ? 1 : (int) substr($last->kode_pj, -6) + 1;

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    // laporan

    public function laporanPj(Request $request)
    {
        $users = User::where('is_active', 1)->orderBy('name')->get();
        $query = Pencairan::with(['pengajuan.pengaju', 'pertanggungjawaban']);

        // --- FILTER ---
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pencairan', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pencairan', '>=', $request->tanggal_awal);
        }

        // --- PERBAIKAN FILTER PENGAJU ---
        // Sesuaikan dengan name="pengaju_id" di view
        if ($request->filled('pengaju_id')) {
            $query->whereHas('pengajuan', function ($q) use ($request) {
                $q->where('pengaju_id', $request->pengaju_id);
            });
        }

        // --- PROSES DATA ---
        $pencairans = $query->get()->map(function ($pencairan) {
            $pencairan->total_telah_dipertanggungjawabkan = $pencairan->pertanggungjawaban->sum('total_pj');
            $pencairan->sisa = $pencairan->nominal_pencairan - $pencairan->total_telah_dipertanggungjawabkan;
            $pencairan->umur = Carbon::parse($pencairan->tanggal_pencairan)->diffInDays(now());
            return $pencairan;
        })->filter(function ($pencairan) use ($request) {
            // --- PERBAIKAN FILTER TIPE DATA ---
            // Logika ini sekarang akan berfungsi karena view mengirim 'tipe_data'
            if ($request->input('tipe_data') === 'lunas') {
                return $pencairan->sisa <= 0;
            }
            // Defaultnya (atau jika tipe_data 'outstanding'), tampilkan yang masih ada sisa
            return $pencairan->sisa > 0;
        });

        // --- LOGIKA RESPON AJAX ---
        if ($request->ajax()) {
            return response()->json(['data' => $pencairans->values()]);
        }

        // --- RESPON HALAMAN BIASA ---
        return view('app-type.keuangan.cash-advance.pertanggung-jawaban.laporan.laporan-pj', compact('pencairans', 'users'));
    }

    // Tambahkan method ini ke dalam PertanggungJawabanController

    public function laporanDetail(Request $request)
    {
        $query = Pertanggungjawaban::with([
            'pencairan.pengajuan.pengaju',
            'userEntry'
        ]);



        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereHas('pencairan', function ($q) use ($request) {
                $q->whereBetween('tanggal_pencairan', [$request->tanggal_awal, $request->tanggal_akhir]);
            });
        }
        if ($request->filled('nama_pengaju')) {
            $query->whereHas('pencairan.pengajuan.pengaju', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_pengaju . '%');
            });
        }
        if ($request->filled('tipe_data')) {
            $tipe = $request->tipe_data;
            if ($tipe === 'outstanding') {
                $query->where('selisih', '>', 0);
            } elseif ($tipe === 'reimburse') {
                $query->where('selisih', '<', 0);
            }
        }

        $pertanggungjawabans = $query->latest('tanggal_pj')->get();

        if ($request->ajax()) {
            return response()->json(['data' => $pertanggungjawabans]);
        }

        // --- Handle Permintaan Biasa (load halaman pertama kali) ---
        return view('app-type.keuangan.cash-advance.pertanggung-jawaban.laporan.laporan-detail', [
            'pertanggungjawabans' => $pertanggungjawabans
        ]);
    }
}
