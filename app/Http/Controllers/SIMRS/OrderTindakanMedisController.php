<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

class OrderTindakanMedisController extends Controller
{
    public function getMedicalActions($registrationId)
    {
        // Validasi input
        if (empty($registrationId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID pendaftaran tidak boleh kosong. Silakan masukkan ID pendaftaran yang valid.',
            ], 400); // 400 Bad Request
        }

        // Fetch medical actions from the database
        $actions = OrderTindakanMedis::where('registration_id', $registrationId)
            ->with(['user.employee', 'tindakan_medis', 'departement', 'registration', 'doctor.employee'])
            ->get();

        // Jika tidak ada tindakan medis ditemukan
        if ($actions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tindakan medis yang ditemukan untuk ID pendaftaran ini. Pastikan ID pendaftaran yang dimasukkan benar.',
            ], 404); // 404 Not Found
        }

        return response()->json([
            'success' => true,
            'data' => $actions,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validasi data
            $validatedData = $request->validate([
                'tanggal_tindakan' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'registration_id' => 'required|exists:registrations,id',
                'doctor_id' => 'required|exists:doctors,id',
                'departement_id' => 'required|exists:departements,id',
                'kelas' => 'required|string',
                'tindakan_medis_id' => 'required|exists:tindakan_medis,id',
                'qty' => 'required|integer|min:1',
            ], [
                'tanggal_tindakan.required' => 'Tanggal tindakan harus diisi.',
                'user_id.required' => 'Pengguna harus diisi.',
                'doctor_id.required' => 'Dokter harus diisi.',
                'departement_id.required' => 'Departemen harus diisi.',
                'kelas.required' => 'Kelas harus diisi.',
                'tindakan_medis_id.required' => 'Tindakan medis harus diisi.',
                'qty.required' => 'Jumlah harus diisi dan tidak boleh kurang dari 1.',
            ]);

            if ($request->diskon_dokter) {
                $validatedData['diskon_dokter'] = true;
            }

            $bilingan = Bilingan::firstOrCreate([
                'registration_id' => $validatedData['registration_id']
            ], [
                'status' => 'belum final',
                'is_paid' => 0,
            ]);

            // Simpan tindakan medis
            $medicalAction = OrderTindakanMedis::create($validatedData);

            $medicalAction->load([
                'doctor.employee',      // Untuk mendapatkan nama dokter
                'user.employee',        // Untuk mendapatkan nama "Entry By"
                'tindakan_medis',       // Untuk nama tindakan (best practice)
                'departement'           // Untuk nama poliklinik (best practice)
            ]);

            $groupPenjaminId = Registration::find($validatedData['registration_id'])->penjamin->group_penjamin->id;
            $kelasId = Registration::find($validatedData['registration_id'])->kelas_rawat;
            if (!$kelasId) {
                $kelasId = 1; // Default to 1 if kelas_id is not found
            } else {
                $kelasId = $kelasId->id;
            }

            // Periksa apakah bilingan sudah ada

            // return dd($bilingan);


            // Simpan tagihan pasien
            $tagihanPasien = TagihanPasien::create([
                'user_id' => $validatedData['user_id'],
                'bilingan_id' => $bilingan->id,
                'registration_id' => $validatedData['registration_id'],
                'tindakan_medis_id' => $validatedData['tindakan_medis_id'],
                'date' => now(),
                'tagihan' => '[Tindakan Medis] ' . $medicalAction->tindakan_medis->nama_billing,
                'quantity' => $validatedData['qty'],
                'nominal' => $validatedData['qty'] * $medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId),
                'tipe_diskon' => $request->tipe_diskon ?? null,
                'disc' => $request->disc ?? null,
                'diskon' => $request->diskon ?? null,
                'jamin' => $request->jamin ?? null,
                'jaminan' => $request->jaminan ?? null,
                'wajib_bayar' => ($validatedData['qty'] * $medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId)) - ($request->diskon ?? 0),
            ]);

            // Simpan relasi bilingan-tagihan pasien
            BilinganTagihanPasien::create([
                'tagihan_pasien_id' => $tagihanPasien->id,
                'bilingan_id' => $bilingan->id,
                'status' => 'belum final',
                'is_paid' => 0,
            ]);

            if ($request->bilingan_ids) {
                foreach ($request->bilingan_ids as $bilinganId) {
                    BilinganTagihanPasien::create([
                        'tagihan_pasien_id' => $tagihanPasien->id,
                        'bilingan_id' => $bilinganId,
                    ]);
                }
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'no' => $medicalAction->no,
                    'tanggal_tindakan' => $medicalAction->tanggal_tindakan,
                    'doctor' => $medicalAction->doctor,
                    'tindakan_medis' => $medicalAction->tindakan_medis,
                    'kelas' => $medicalAction->kelas,
                    'departement' => $medicalAction->departement,
                    'qty' => $medicalAction->qty,
                    'user' => $medicalAction->user,
                    'foc' => $medicalAction->foc,
                    'id' => $medicalAction->id,
                ],
                'message' => 'Tindakan medis berhasil disimpan!'
            ], 201);
        } catch (\Exception $e) {
            // Jika ada error, rollback semua perubahan ke database
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tindakan medis. Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $action = OrderTindakanMedis::find($id);

        if (!$action) {
            return response()->json([
                'success' => false,
                'message' => 'Tindakan medis tidak ditemukan. Pastikan ID yang dimasukkan benar.',
            ], 404);
            try {
                $action->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Tindakan medis berhasil dihapus.'
                ]);
            } catch (\Exception $e) {
                \Log::error('Error menghapus tindakan medis: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus tindakan medis. Silakan coba lagi nanti.'
                ], 500);
            }
        }
    }

    private function calculateNominal($medicalAction)
    {
        // Implement your logic to calculate nominal here
        return $medicalAction->qty * 100; // Example calculation
    }

    private function calculateDiskon($medicalAction)
    {
        // Implement your logic to calculate discount here
        return $medicalAction->qty * 10; // Example calculation
    }

    private function calculateWajibBayar($medicalAction)
    {
        // Implement your logic to calculate wajib bayar here
        return $this->calculateNominal($medicalAction) - $this->calculateDiskon($medicalAction); // Example calculation
    }
}
