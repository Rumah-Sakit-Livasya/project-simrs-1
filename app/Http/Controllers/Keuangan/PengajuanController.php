<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\Pengajuan;
use App\Models\Keuangan\PengajuanDetail;
use Illuminate\Http\Request;
use App\Models\User;
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
        $users = User::where('is_active', 1)->orderBy('name')->get();
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

        $nominalValue = preg_replace('/[Rp. ]/', '', $request->nominal);
        $nominalValue = (float) $nominalValue;

        if ($nominalValue <= 0) {
            return redirect()->back()->withErrors(['nominal' => 'Nominal harus lebih besar dari 0.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $pengajuan = Pengajuan::create([
                'kode_pengajuan'          => $this->generateKodePengajuan(),
                'tanggal_pengajuan'       => $request->tanggal_pengajuan,
                'pengaju_id'              => $request->pengaju_id,
                'keterangan'              => $request->keterangan,
                'total_nominal_pengajuan' => $nominalValue,
                'status'                  => 'pending',
                'user_entry_id'           => Auth::id(),
            ]);

            PengajuanDetail::create([
                'pengajuan_id' => $pengajuan->id,
                'deskripsi'    => $request->keterangan,
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

    public function approveBulk(Request $request)
    {
        $emailOtorisasi = 'dimas@livasya.com';
        $user = User::find($request->otorisasi_id);

        if (!$user) {
            return response()->json(['message' => 'User otorisasi tidak ditemukan.'], 404);
        }

        $userEmailFromDB = trim($user->email);

        if (strtolower($userEmailFromDB) !== strtolower($emailOtorisasi)) {
            return response()->json(['message' => 'User yang dipilih tidak memiliki hak untuk melakukan persetujuan.'], 403);
        }

        if ($this->zimbraLogin($userEmailFromDB, $request->password)) {
            try {
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
            Log::error('Zimbra Login FAILED for email: ' . $userEmailFromDB);
            return response()->json(['message' => 'Password salah!'], 401);
        }
    }

    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $validator = Validator::make($request->all(), [
            'otorisasi_id' => 'required|exists:users,id',
            'password'     => 'required',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        if ($pengajuan->status !== 'pending') {
            return response()->json(['message' => 'Hanya pengajuan dengan status "Menunggu" yang bisa diproses.'], 400);
        }

        $emailOtorisasi = 'dimas@livasya.com';
        $user = User::find($request->otorisasi_id);

        if (!$user || strtolower(trim($user->email)) !== strtolower($emailOtorisasi)) {
            return response()->json(['message' => 'User yang dipilih tidak memiliki hak untuk melakukan aksi ini.'], 403);
        }

        if ($this->zimbraLogin(trim($user->email), $request->password)) {
            try {
                $alasan = $request->input('rejection_reason') ?: 'Ditolak oleh atasan.';

                $pengajuan->update([
                    'status' => 'rejected',
                    'approved_by_id' => $user->id,
                    'approved_at' => now(),
                    'keterangan' => $pengajuan->keterangan . ' | Alasan Ditolak: ' . $alasan,
                ]);

                return response()->json(['message' => 'Pengajuan ' . $pengajuan->kode_pengajuan . ' berhasil ditolak.']);
            } catch (\Exception $e) {
                Log::error('DB Update Failed during rejection: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan internal saat menolak pengajuan.'], 500);
            }
        } else {
            return response()->json(['message' => 'Password salah!'], 401);
        }
    }

    public function destroy(Pengajuan $pengajuan)
    {
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

    // FIXED: Bulk Delete Method
    public function deleteBulk(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Bulk delete request received', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        // Enhanced validation
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|min:1'
        ], [
            'ids.required' => 'Silakan pilih data yang akan dihapus.',
            'ids.array' => 'Format data tidak valid.',
            'ids.min' => 'Minimal pilih 1 data untuk dihapus.',
            'ids.*.required' => 'ID pengajuan tidak valid.',
            'ids.*.integer' => 'ID pengajuan harus berupa angka.',
            'ids.*.min' => 'ID pengajuan tidak valid.'
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed in bulk delete', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $ids = $request->input('ids');

        // Validate that all IDs exist in database
        $existingIds = Pengajuan::whereIn('id', $ids)->pluck('id')->toArray();
        $missingIds = array_diff($ids, $existingIds);

        if (!empty($missingIds)) {
            Log::warning('Some IDs not found in database', [
                'missing_ids' => $missingIds,
                'existing_ids' => $existingIds
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Beberapa data tidak ditemukan di database.'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Get pengajuan data with status check
            $pengajuans = Pengajuan::whereIn('id', $ids)->get();

            if ($pengajuans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang ditemukan.'
                ], 404);
            }

            // Filter only deletable records (pending or rejected)
            $deletableIds = [];
            $undeletableCount = 0;

            foreach ($pengajuans as $pengajuan) {
                if (in_array($pengajuan->status, ['pending', 'rejected'])) {
                    $deletableIds[] = $pengajuan->id;
                } else {
                    $undeletableCount++;
                }
            }

            if (empty($deletableIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dapat dihapus. Semua data sudah diproses.'
                ], 400);
            }

            // Delete related details first
            $detailsDeleted = PengajuanDetail::whereIn('pengajuan_id', $deletableIds)->delete();

            // Delete main records
            $deletedCount = Pengajuan::whereIn('id', $deletableIds)->delete();

            DB::commit();

            $message = "Berhasil menghapus {$deletedCount} data pengajuan.";
            if ($undeletableCount > 0) {
                $message .= " {$undeletableCount} data tidak dapat dihapus karena sudah diproses.";
            }

            Log::info('Bulk delete successful', [
                'deleted_count' => $deletedCount,
                'details_deleted' => $detailsDeleted,
                'undeletable_count' => $undeletableCount,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'undeletable_count' => $undeletableCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bulk delete failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ids' => $ids,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
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
            return strpos($result, $mark) === false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generateKodePengajuan()
    {
        $prefix = 'ADVA' . date('y') . '-';
        $lastPengajuan = Pengajuan::where('kode_pengajuan', 'like', $prefix . '%')
            ->orderBy('kode_pengajuan', 'desc')
            ->first();
        $number = 1;
        if ($lastPengajuan) {
            $lastNumber = (int) substr($lastPengajuan->kode_pengajuan, -6);
            $number = $lastNumber + 1;
        }
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
