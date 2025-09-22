<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\TarifPeralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LayananController extends Controller
{
    public function storePemakaianAlat(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'tanggal_order' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'registration_id' => 'required|exists:registrations,id',
            'doctor_id' => 'required|exists:doctors,id',
            'departement_id' => 'required|exists:departements,id',
            'peralatan_id' => 'required|exists:peralatan,id',
            'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
            'qty' => 'required|numeric|min:1',
            'lokasi' => 'required|string',
        ]);

        // Ambil data yang diperlukan di awal, tanpa eager loading berat
        $registration = Registration::select('id', 'penjamin_id')
            ->with(['penjamin:id,group_penjamin_id', 'penjamin.group_penjamin:id'])
            ->find($validatedData['registration_id']);

        if (
            !$registration ||
            !$registration->penjamin ||
            !$registration->penjamin->group_penjamin
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjamin atau group penjamin tidak ditemukan pada registrasi.'
            ], 422);
        }

        $groupPenjaminId = $registration->penjamin->group_penjamin->id;
        $kelasRawatId = $validatedData['kelas_rawat_id'];
        $peralatanId = $validatedData['peralatan_id'];

        // Cari tarif yang sesuai (gunakan select kolom seperlunya)
        $tarif = TarifPeralatan::select('id', 'total')
            ->where('peralatan_id', $peralatanId)
            ->where('group_penjamin_id', $groupPenjaminId)
            ->where('kelas_rawat_id', $kelasRawatId)
            ->first();

        if (!$tarif) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif untuk alat ini dengan penjamin dan kelas tersebut tidak ditemukan. Mohon atur tarif terlebih dahulu.'
            ], 422);
        }

        // Ambil user (hanya id dan name)
        $user = User::select('id', 'name')->find($validatedData['user_id']);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 422);
        }

        // Proses utama dalam transaksi, tapi minimalisasi query berat
        DB::beginTransaction();
        try {
            // Simpan data pemakaian alat
            $order = new OrderAlatMedis();
            $order->fill($validatedData);
            $order->entry_by = $user->name;
            $order->save();

            // Buat atau ambil Bilingan utama (gunakan select minimal)
            $bilingan = Bilingan::firstOrCreate(
                ['registration_id' => $validatedData['registration_id']],
                ['status' => 'belum final', 'is_paid' => 0]
            );

            // Hitung nominal
            $nominalTotal = $tarif->total * $validatedData['qty'];

            // Buat Tagihan Pasien (tanpa load relasi berat)
            $tagihanPasien = TagihanPasien::create([
                'user_id' => $user->id,
                'bilingan_id' => $bilingan->id,
                'registration_id' => $validatedData['registration_id'],
                'order_alat_medis_id' => $order->id,
                'date' => now(),
                'tagihan' => '[Pemakaian Alat]', // Nama alat diisi setelah commit
                'quantity' => $validatedData['qty'],
                'nominal' => $nominalTotal,
                'wajib_bayar' => $nominalTotal,
            ]);

            // Buat relasi Bilingan-Tagihan (pivot)
            BilinganTagihanPasien::create([
                'tagihan_pasien_id' => $tagihanPasien->id,
                'bilingan_id' => $bilingan->id,
                'status' => 'belum final',
                'is_paid' => 0,
            ]);

            DB::commit();

            // Setelah commit, load relasi yang diperlukan untuk response (agar transaksi tidak lama)
            $order->load(['alat', 'doctor.employee', 'kelas_rawat', 'user']);

            // Update nama alat di tagihan (jika perlu, tanpa blocking user)
            if ($order->alat && $tagihanPasien) {
                $tagihanPasien->update([
                    'tagihan' => '[Pemakaian Alat] ' . ($order->alat->nama ?? 'Tidak Diketahui')
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pemakaian alat dan tagihan berhasil disimpan!',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pemakaian alat: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    /**
     * Menghapus pemakaian alat dan tagihan terkait.
     */
    public function destroyPemakaianAlat(OrderAlatMedis $order)
    {
        // Anda mungkin perlu menambahkan validasi hak akses di sini

        DB::beginTransaction();
        try {
            // Cari tagihan yang berelasi
            $tagihan = $order->tagihan_pasien()->first();

            // Periksa apakah sudah ditagih (jika ada flag is_billed)
            // if ($tagihan && $tagihan->is_billed) {
            //     DB::rollBack();
            //     return response()->json(['success' => false, 'message' => 'Tidak bisa dihapus karena sudah ditagih.'], 422);
            // }

            if ($tagihan) {
                // Hapus dari tabel pivot bilingan_tagihan_pasien
                BilinganTagihanPasien::where('tagihan_pasien_id', $tagihan->id)->delete();
                // Hapus tagihan itu sendiri
                $tagihan->delete();
            }

            // Hapus data order alat
            $order->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pemakaian alat dan tagihan terkait berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus pemakaian alat dan tagihan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function getDataPemakaianAlat(Registration $registration)
    {
        $query = OrderAlatMedis::with(['doctor.employee', 'alat', 'user', 'kelas_rawat'])
            ->where('registration_id', $registration->id)
            ->select('order_alat_medis.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_order', function ($row) {
                return tgl($row->tanggal_order);
            })
            ->addColumn('doctor_name', function ($row) {
                return $row->doctor?->employee?->fullname ?? '-';
            })
            ->addColumn('alat_name', function ($row) {
                return $row->alat?->nama ?? '-';
            })
            ->addColumn('kelas_name', function ($row) {
                return $row->kelas_rawat?->kelas ?? $row->kelas; // Fallback ke field 'kelas' jika relasi null
            })
            ->addColumn('user_name', function ($row) {
                return $row->user?->name ?? $row->entry_by; // Fallback ke field 'entry_by'
            })
            ->addColumn('action', function ($row) {
                // Tombol hapus
                $btn = '<button class="btn btn-danger btn-sm btn-icon rounded-circle delete-action" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
