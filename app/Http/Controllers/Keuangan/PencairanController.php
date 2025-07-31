<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\BankPerusahaan;
use Illuminate\Http\Request;
use App\Models\Keuangan\Pencairan;
use App\Models\Keuangan\Pengajuan;
use App\Models\Keuangan\Bank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'tanggal_pencairan' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'bank_id' => 'required|exists:banks,id',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data pengajuan
            $pengajuan = Pengajuan::findOrFail($validatedData['pengajuan_id']);

            // Hitung sisa yang bisa dicairkan
            $totalDisetujui = $pengajuan->total_nominal_disetujui;
            $sudahDicairkan = $pengajuan->pencairan->sum('nominal_pencairan');
            $sisa = $totalDisetujui - $sudahDicairkan;

            // Validasi nominal tidak melebihi sisa
            if ($validatedData['nominal'] > $sisa) {
                return back()->withInput()->with('error', 'Nominal pencairan melebihi sisa yang tersedia!');
            }

            // Buat pencairan baru
            $pencairan = new Pencairan();
            $pencairan->kode_pencairan = $this->generateKodePencairan();
            $pencairan->pengajuan_id = $validatedData['pengajuan_id'];
            $pencairan->tanggal_pencairan = $validatedData['tanggal_pencairan'];
            $pencairan->nominal_pencairan = $validatedData['nominal'];
            $pencairan->bank_id = $validatedData['bank_id'];
            $pencairan->keterangan = $validatedData['keterangan'];
            $pencairan->user_entry_id = auth()->id();
            $pencairan->save();

            // Update status pengajuan jika sudah dicairkan semua
            if ($validatedData['nominal'] == $sisa) {
                $pengajuan->status = 'closed';
                $pengajuan->save();
            } elseif ($pengajuan->status == 'approved') {
                $pengajuan->status = 'partial';
                $pengajuan->save();
            }

            DB::commit();

            return redirect()->route('keuangan.cash-advance.pencairan')
                ->with('success', 'Pencairan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pencairan: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pencairan. Silakan coba lagi.');
        }
    }
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name'          => 'required|string|max:255',
    //         'pemilik'       => 'required|string|max:255',
    //         'nomor'         => 'required|string|max:255',
    //         'saldo'         => 'required|numeric|min:0',
    //         'akun_kas_bank' => 'required|exists:chart_of_accounts,id', // Pastikan nama tabel CoA benar
    //         'akun_kliring'  => 'required|exists:chart_of_accounts,id', // Pastikan nama tabel CoA benar
    //         'is_aktivasi'   => 'nullable', // Checkbox tidak akan dikirim jika tidak dicentang
    //         'is_bank'       => 'nullable',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $bank = new Bank();
    //         $bank->name = $validatedData['name'];
    //         $bank->saldo_awal = $validatedData['saldo'];
    //         $bank->total_masuk = $validatedData['saldo']; // Saldo awal dianggap sebagai pemasukan pertama
    //         $bank->total_keluar = 0;
    //         $bank->save();

    //         // 4. Buat dan simpan data ke tabel `bank_perusahaan`
    //         // Tabel ini berisi detail akun bank milik perusahaan
    //         $bankPerusahaan = new BankPerusahaan();
    //         $bankPerusahaan->nama = $validatedData['name'];
    //         $bankPerusahaan->pemilik = $validatedData['pemilik'];
    //         $bankPerusahaan->nomor = $validatedData['nomor'];
    //         $bankPerusahaan->saldo = $validatedData['saldo'];
    //         $bankPerusahaan->akun_kas_bank = $validatedData['akun_kas_bank'];
    //         $bankPerusahaan->akun_kliring = $validatedData['akun_kliring'];
    //         $bankPerusahaan->is_aktivasi = $request->has('is_aktivasi'); // Mengembalikan true jika dicentang
    //         $bankPerusahaan->is_bank = $request->has('is_bank');


    //         $bankPerusahaan->save();

    //         // 5. Jika semua proses berhasil, commit transaksi
    //         DB::commit();

    //         // 6. Redirect kembali ke halaman index dengan pesan sukses
    //         return redirect()->route('bank.index')
    //             ->with('success', 'Bank baru berhasil ditambahkan.');
    //     } catch (\Exception $e) {
    //         // 7. Jika terjadi error, batalkan semua query (rollback)
    //         DB::rollBack();

    //         // Opsional: Catat error untuk debugging
    //         Log::error('Gagal menyimpan bank baru: ' . $e->getMessage());

    //         // 8. Redirect kembali ke form dengan pesan error dan input sebelumnya
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
    //             ->withInput();
    //     }
    // }

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

        $terbilang = terbilangRp($pencairan->nominal_pencairan, false);

        return view('app-type.keuangan.cash-advance.pencairan.print.cash-advance-print', compact('pencairan', 'terbilang'));
    }
}
