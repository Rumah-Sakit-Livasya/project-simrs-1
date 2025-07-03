<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Pencairan;
use App\Models\Keuangan\Pertanggungjawaban;
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
            'pencairan.pengajuan.pengaju', // Relasi bertingkat untuk mendapatkan nama pengaju
            'details',                     // Relasi ke detail pertanggungjawaban
            'userEntry'                    // Relasi ke user yang menginput
        ]);

        // Filter berdasarkan request
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

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json($pertanggungjawabans);
        }

        // Jika request biasa, return view
        return view('app-type.keuangan.cash-advance.pertanggungjawaban', compact('pertanggungjawabans'));
    }
    public function pjawabanCreate(Request $request)
    {
        // Data untuk dropdown, misal dari tabel master
        $tipe_transaksis = ['Biaya Transport', 'Makan & Minum', 'Pembelian ATK', 'Lain-lain'];
        $cost_centers = ['Operasional', 'Marketing', 'IT', 'HRD'];

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
        $validator = Validator::make($request->all(), [
            'tanggal_pj' => 'required|date',
            'pencairan_id' => 'required|exists:pencairans,id',
            'details' => 'required|array|min:1',
            'details.*.tipe_transaksi' => 'required',
            'details.*.keterangan' => 'required|string',
            'details.*.nominal' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $pencairan = Pencairan::findOrFail($request->pencairan_id);

            // Hitung total yang telah dipertanggungjawabkan sebelumnya
            $totalPjSebelumnya = $pencairan->pertanggungjawaban()->sum('total_pj');

            // Hitung total dari detail baru
            $totalPjBaru = collect($request->details)->sum('nominal');

            // Total setelah penambahan
            $totalSetelahPj = $totalPjSebelumnya + $totalPjBaru;

            // Validasi tidak melebihi nominal pencairan
            if ($totalSetelahPj > $pencairan->nominal_pencairan) {
                throw new \Exception('Total pertanggungjawaban melebihi nominal pencairan');
            }

            // Hitung selisih
            $selisih = $pencairan->nominal_pencairan - $totalSetelahPj;

            // Buat record pertanggungjawaban
            $pj = Pertanggungjawaban::create([
                'kode_pj' => $this->generateKodePj(),
                'tanggal_pj' => $request->tanggal_pj,
                'pencairan_id' => $pencairan->id,
                'total_pj' => $totalPjBaru,
                'selisih' => $selisih,
                'status' => 'pending',
                'user_entry_id' => Auth::id(),
            ]);

            // Simpan detail
            foreach ($request->details as $detail) {
                $pj->details()->create([
                    'tipe' => 'pj',
                    'tipe_transaksi' => $detail['tipe_transaksi'],
                    'cost_center' => $detail['cost_center'] ?? null,
                    'keterangan' => $detail['keterangan'],
                    'nominal' => $detail['nominal'],
                ]);
            }

            DB::commit();
            return redirect()->route('keuangan.cash-advance.pertanggung-jawaban')
                ->with('success', 'Pertanggungjawaban berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal: ' . $e->getMessage())
                ->withInput();
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
        // Query dasar dengan relasi yang dibutuhkan
        $query = Pertanggungjawaban::with([
            'pencairan.pengajuan.pengaju',
            'userEntry'
        ]);

        // --- Logika Filter ---
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

        // --- Handle Permintaan AJAX ---
        if ($request->ajax()) {
            return response()->json(['data' => $pertanggungjawabans]);
        }

        // --- Handle Permintaan Biasa (load halaman pertama kali) ---
        return view('app-type.keuangan.cash-advance.pertanggung-jawaban.laporan.laporan-detail', [
            'pertanggungjawabans' => $pertanggungjawabans
        ]);
    }
}
