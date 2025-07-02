<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan\Pencairan;
use App\Models\Keuangan\Pengajuan;
use App\Models\Keuangan\Bank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PencairanController extends Controller
{
    /**
     * Menampilkan halaman daftar pencairan.
     */
    public function index()
    {
        $pencairans = Pencairan::with(['pengajuan.pengaju', 'bank', 'userEntry'])->latest()->get();
        return view('app-type.keuangan.cash-advance.pencairan', compact('pencairans'));
    }

    /**
     * Menampilkan form untuk membuat pencairan baru.
     */
    public function Pencairancreate()
    {
        // Ambil daftar pengajuan yang statusnya bisa dicairkan
        $pengajuans = Pengajuan::with('pengaju')
            ->whereIn('status', ['approved', 'partial'])
            ->orderBy('kode_pengajuan', 'desc')
            ->get();

        // Ambil daftar bank/kas
        $banks = Bank::all();

        return view('app-type.keuangan.cash-advance.pencairan.create', compact('pengajuans', 'banks'));
    }

    /**
     * Menyimpan data pencairan baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pencairan' => 'required|date',
            'pengajuan_id'      => 'required|exists:pengajuans,id',
            'bank_id'           => 'required|exists:banks,id',
            'nominal'           => 'required|string',
            'keterangan'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nominalPencairan = (float) preg_replace('/[Rp. ]/', '', $request->nominal);

        $pengajuan = Pengajuan::with('pencairan')->find($request->pengajuan_id);

        $sudahDicairkan = $pengajuan->pencairan->sum('nominal_pencairan');
        $sisa = $pengajuan->total_nominal_disetujui - $sudahDicairkan;

        if ($nominalPencairan <= 0) {
            return redirect()->back()->withErrors(['nominal' => 'Nominal pencairan harus lebih besar dari 0.'])->withInput();
        }
        if ($nominalPencairan > $sisa) {
            return redirect()->back()->withErrors(['nominal' => 'Nominal pencairan melebihi sisa yang belum dicairkan (Sisa: Rp ' . number_format($sisa, 0, ',', '.') . ')'])->withInput();
        }

        DB::beginTransaction();
        try {
            Pencairan::create([
                'kode_pencairan'    => $this->generateKodePencairan(),
                'pengajuan_id'      => $pengajuan->id,
                'tanggal_pencairan' => $request->tanggal_pencairan,
                'nominal_pencairan' => $nominalPencairan,
                'bank_id'           => $request->bank_id,
                'keterangan'        => $request->keterangan,
                'user_entry_id'     => Auth::id(),
            ]);

            $totalDicairkanBaru = $sudahDicairkan + $nominalPencairan;
            $status_baru = ($totalDicairkanBaru >= $pengajuan->total_nominal_disetujui) ? 'closed' : 'partial';

            $pengajuan->update(['status' => $status_baru]);

            DB::commit();
            return redirect()->route('keuangan.cash-advance.pencairan')->with('success', 'Pencairan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function dataPengajuanPopup(Request $request)
    {
        // Mulai query builder
        $query = Pengajuan::with(['pengaju', 'pencairan'])
            ->whereIn('status', ['approved', 'partial']);

        // Filter berdasarkan Kode Pengajuan jika ada
        if ($request->filled('kode_pengajuan')) {
            $query->where('kode_pengajuan', 'like', '%' . $request->kode_pengajuan . '%');
        }

        // Filter berdasarkan Nama Pengaju jika ada
        if ($request->filled('nama_pengaju')) {
            $query->whereHas('pengaju', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_pengaju . '%');
            });
        }

        if ($request->filled('tipe_pengajuan')) {
            if ($request->tipe_pengajuan == 'approval_pengajuan') {
                // Cari yang nominalnya berbeda (membutuhkan approval nominal)
                $query->whereColumn('total_nominal_disetujui', '!=', 'total_nominal_pengajuan');
            } elseif ($request->tipe_pengajuan == 'non_pengajuan') {
                // Cari yang nominalnya sama
                $query->whereColumn('total_nominal_disetujui', '=', 'total_nominal_pengajuan');
            }
        }

        $pengajuans = $query->orderBy('kode_pengajuan', 'desc')->get();

        return view('app-type.keuangan.cash-advance.pencairan.data-pengajuan-popup', compact('pengajuans'));
    }

    /**
     * Endpoint AJAX untuk mengambil detail sisa pengajuan.
     */
    public function getPengajuanData($id)
    {
        $pengajuan = Pengajuan::with('pencairan')->find($id);
        if (!$pengajuan) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        $totalDisetujui = $pengajuan->total_nominal_disetujui;
        $sudahDicairkan = $pengajuan->pencairan->sum('nominal_pencairan');
        $sisa = $totalDisetujui - $sudahDicairkan;

        return response()->json([
            'nama_pengaju_text' => $pengajuan->pengaju->name ?? 'N/A',
            'jumlah_pengajuan' => $totalDisetujui,
            'telah_dicairkan' => $sudahDicairkan,
            'belum_dicairkan' => $sisa,
        ]);
    }

    /**
     * Helper function untuk generate kode pencairan.
     */
    private function generateKodePencairan()
    {
        $prefix = 'ADVC' . date('y') . '-';
        $last = Pencairan::where('kode_pencairan', 'like', $prefix . '%')
            ->latest('kode_pencairan')
            ->first();

        $number = !$last ? 1 : (int) substr($last->kode_pencairan, -6) + 1;

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }


    public function print(Pencairan $pencairan)
    {
        // Eager load relasi yang dibutuhkan
        $pencairan->load(['pengajuan.pengaju', 'bank', 'userEntry']);

        // --- PERUBAHAN DI SINI ---
        // Panggil fungsi helper global 'terbilangRp'
        // Kita tidak menambahkan 'Rupiah' karena di template sudah ada
        $terbilang = terbilangRp($pencairan->nominal_pencairan, false);

        return view('app-type.keuangan.cash-advance.pencairan.print.cash-advance-print', compact('pencairan', 'terbilang'));
    }
}
