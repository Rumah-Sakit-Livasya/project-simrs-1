<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Pengajuan;
use App\Models\Keuangan\PengajuanDetail;
use Illuminate\Http\Request;
use App\Models\User;      // Impor model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $pengajuans = Pengajuan::with('pengaju', 'userEntry')->latest()->get();
        $userOtorisasi = User::where('email', 'dimas@livasya.com')->first();
        return view('app-type.keuangan.cash-advance.pengajuan', compact('pengajuans', 'userOtorisasi'));
    }


    public function Pengajuancreate()
    {
        $users = User::where('is_active', 1)->orderBy('name')->get(); // Sesuaikan query jika perlu
        return view('app-type.keuangan.cash-advance.pengajuan.create', compact('users'));
    }

    public function proses(Pengajuan $pengajuan)
    {
        $pengajuan->load('pengaju', 'userEntry');

        $userOtorisasi = User::where('email', 'dimas@livasya.com')->first();

        return view('app-type.keuangan.cash-advance.pengajuan.proses', compact('pengajuan', 'userOtorisasi'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'tanggal_pengajuan' => 'required|date',
            'pengaju_id'        => 'required|exists:users,id',
            'keterangan'        => 'required|string|max:255',
            'nominal'           => 'required|string',
        ], [
            'keterangan.required' => 'Field Keterangan wajib diisi.',
            'nominal.required' => 'Field Nominal wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Bersihkan dan konversi nilai nominal
        $nominalValue = preg_replace('/[Rp. ]/', '', $request->nominal);
        $nominalValue = (float) $nominalValue;

        if ($nominalValue <= 0) {
            return redirect()->back()->withErrors(['nominal' => 'Nominal harus lebih besar dari 0.'])->withInput();
        }

        // 3. Proses Penyimpanan menggunakan Database Transaction
        DB::beginTransaction();
        try {
            // Buat data Pengajuan (header)
            $pengajuan = Pengajuan::create([
                'kode_pengajuan'          => $this->generateKodePengajuan(),
                'tanggal_pengajuan'       => $request->tanggal_pengajuan,
                'pengaju_id'              => $request->pengaju_id,
                'keterangan'              => $request->keterangan,
                'total_nominal_pengajuan' => $nominalValue,
                'status'                  => 'pending',
                'user_entry_id'           => Auth::id(),
            ]);

            // Buat SATU data PengajuanDetail
            PengajuanDetail::create([
                'pengajuan_id' => $pengajuan->id,
                'deskripsi'    => $request->keterangan, // Deskripsi detail diambil dari keterangan header
                'nominal'      => $nominalValue,
            ]);

            DB::commit();

            return redirect()->route('keuangan.cash-advance.pengajuan')
                ->with('success', 'Pengajuan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat pengajuan: ' . $e->getMessage())
                ->withInput();
        }
    }




    // ... method-method lain (index, create, store, dll.) ...

    /**
     * Memproses approval menggunakan otorisasi Zimbra,
     * sesuai dengan pola yang diberikan.
     */
    // app/Http/Controllers/keuangan/PengajuanController.php

    // app/Http/Controllers/keuangan/PengajuanController.php

    public function approveBulk(Request $request)
    {
        // ... validasi awal ...

        $emailOtorisasi = 'dimas@livasya.com'; // Kembalikan ke email yang benar
        $user = User::find($request->otorisasi_id);

        if (!$user) {
            return response()->json(['message' => 'User otorisasi tidak ditemukan.'], 404);
        }

        // ==========================================================
        // PERBAIKAN 1: Bersihkan email dari database sebelum dibandingkan
        // ==========================================================
        $userEmailFromDB = trim($user->email);

        if (strtolower($userEmailFromDB) !== strtolower($emailOtorisasi)) {
            return response()->json(['message' => 'User yang dipilih tidak memiliki hak untuk melakukan persetujuan.'], 403);
        }

        // ==========================================================
        // PERBAIKAN 2: Gunakan email yang sudah dibersihkan untuk login
        // ==========================================================
        if ($this->zimbraLogin($userEmailFromDB, $request->password)) {
            // Otentikasi Zimbra Berhasil
            try {
                // ... (logika update database) ...
                $approvedCount = Pengajuan::whereIn('id', $request->ids)
                    ->where('status', 'pending')
                    ->update([
                        'status'         => 'approved',
                        'approved_by_id' => $user->id,
                        'approved_at'    => now(),
                        'total_nominal_disetujui' => DB::raw('total_nominal_pengajuan'),
                    ]);

                if ($approvedCount > 0) {
                    return response()->json(['message' => $approvedCount . ' data pengajuan berhasil disetujui.']);
                }
                return response()->json(['message' => 'Tidak ada data yang disetujui. Mungkin data sudah diproses sebelumnya.'], 400);
            } catch (\Exception $e) {
                Log::error('DB Update Failed during approval: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan internal saat memproses persetujuan.'], 500);
            }
        } else {
            // Otentikasi Zimbra Gagal
            Log::error('Zimbra Login FAILED for email: ' . $userEmailFromDB);
            return response()->json(['message' => 'Password salah!'], 401);
        }
    }

    private function zimbraLogin($email, $password)
    {
        $data = [
            "Header" => [
                "context" => [
                    "_jsns" => "urn:zimbra",
                    "userAgent" => ["name" => "curl", "version" => "8.8.15"],
                ],
            ],
            "Body" => [
                "AuthRequest" => [
                    "_jsns" => "urn:zimbraAccount",
                    "account" => ["_content" => $email, "by" => "name"],
                    "password" => $password,
                ],
            ],
        ];

        try {
            $encodedData = json_encode($data);

            $url = 'https://webmail.livasya.com/service/soap';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
            ));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $mark = 'AUTH_FAILED';

            if (strpos($result, $mark) !== false) {
                return false; // Autentikasi gagal
            } else {
                // Autentikasi berhasil
                // Anda mungkin ingin melakukan sesuatu di sini, seperti memproses respons
                // atau mengembalikan informasi tambahan
                return true;
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat menjalankan permintaan cURL
            return false;
        }
    }

    /**
     * Helper function untuk otentikasi ke server ZIMBRA.
     * Disalin dari RegistrationController.
     */


    public function reject(Request $request, Pengajuan $pengajuan)
    {
        // 1. Validasi input dari modal AJAX
        $validator = Validator::make($request->all(), [
            'otorisasi_id' => 'required|exists:users,id',
            'password'     => 'required',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Cek status pengajuan
        if ($pengajuan->status !== 'pending') {
            return response()->json(['message' => 'Hanya pengajuan dengan status "Menunggu" yang bisa diproses.'], 400);
        }

        // 2. Otorisasi (sama seperti approveBulk)
        $emailOtorisasi = 'dimas@livasya.com';
        $user = User::find($request->otorisasi_id);

        if (!$user || strtolower(trim($user->email)) !== strtolower($emailOtorisasi)) {
            return response()->json(['message' => 'User yang dipilih tidak memiliki hak untuk melakukan aksi ini.'], 403);
        }

        // 3. Otentikasi Zimbra
        if ($this->zimbraLogin(trim($user->email), $request->password)) {
            // Otentikasi Berhasil, lanjutkan proses reject
            try {
                $alasan = $request->input('rejection_reason') ?: 'Ditolak oleh atasan.';

                $pengajuan->update([
                    'status' => 'rejected',
                    'approved_by_id' => $user->id, // User yang melakukan otorisasi
                    'approved_at' => now(),
                    'keterangan' => $pengajuan->keterangan . ' | Alasan Ditolak: ' . $alasan,
                ]);

                return response()->json(['message' => 'Pengajuan ' . $pengajuan->kode_pengajuan . ' berhasil ditolak.']);
            } catch (\Exception $e) {
                Log::error('DB Update Failed during rejection: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan internal saat menolak pengajuan.'], 500);
            }
        } else {
            // Otentikasi Gagal
            return response()->json(['message' => 'Password salah!'], 401);
        }
    }


    public function destroy(Pengajuan $pengajuan)
    {
        // Hanya bisa menghapus jika statusnya belum disetujui
        if ($pengajuan->status === 'approved' || $pengajuan->status === 'partial' || $pengajuan->status === 'closed') {
            return redirect()->route('keuangan.cash-advance.pengajuan')->with('error', 'Pengajuan yang sudah disetujui tidak dapat dihapus.');
        }

        try {
            $kode = $pengajuan->kode_pengajuan;
            $pengajuan->delete();
            return redirect()->route('keuangan.cash-advance.pengajuan')->with('success', 'Pengajuan ' . $kode . ' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('keuangan.cash-advance.pengajuan')->with('error', 'Gagal menghapus data. ' . $e->getMessage());
        }
    }

    private function generateKodePengajuan()
    {
        $prefix = 'ADVA' . date('y') . '-';
        $lastPengajuan = Pengajuan::where('kode_pengajuan', 'like', $prefix . '%')->orderBy('kode_pengajuan', 'desc')->first();
        $number = 1;
        if ($lastPengajuan) {
            $lastNumber = (int) substr($lastPengajuan->kode_pengajuan, -6);
            $number = $lastNumber + 1;
        }
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
