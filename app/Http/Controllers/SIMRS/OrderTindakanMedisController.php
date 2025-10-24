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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class OrderTindakanMedisController extends Controller
{
    public function getMedicalActions($registrationId)
    {
        if (empty($registrationId)) {
            return response()->json([
                "success" => false,
                "message" => "ID pendaftaran tidak boleh kosong. Silakan masukkan ID pendaftaran yang valid.",
            ], 400);
        }

        $actions = OrderTindakanMedis::where("registration_id", $registrationId)
            ->with([
                "user.employee",
                "tindakan_medis",
                "departement",
                "registration",
                "employee",
            ])
            ->get();

        if ($actions->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "Tidak ada tindakan medis yang ditemukan untuk ID pendaftaran ini.",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $actions,
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'tanggal_tindakan' => 'required|date_format:d-m-Y',
                'user_id' => 'required|exists:users,id',
                'registration_id' => 'required|exists:registrations,id',
                'employee_id' => 'nullable|exists:employees,id',
                'departement_id' => 'required|exists:departements,id',
                'kelas' => 'required|exists:kelas_rawat,id',
                'tindakan_medis_id' => 'required|exists:tindakan_medis,id',
                'qty' => 'required|integer|min:1',
                'foc' => 'required|string', // FIX: Added 'foc' to validation
            ], [
                'tanggal_tindakan.required' => 'Tanggal tindakan harus diisi.',
                'user_id.required' => 'Pengguna harus diisi.',
                'departement_id.required' => 'Departemen harus diisi.',
                'kelas.required' => 'Kelas harus diisi.',
                'tindakan_medis_id.required' => 'Tindakan medis harus diisi.',
                'qty.required' => 'Jumlah harus diisi dan tidak boleh kurang dari 1.',
            ]);

            // FIX: Convert date from dd-mm-yyyy to yyyy-mm-dd for database
            $validatedData['tanggal_tindakan'] = Carbon::createFromFormat('d-m-Y', $validatedData['tanggal_tindakan'])->format('Y-m-d');

            $bilingan = Bilingan::firstOrCreate(
                ['registration_id' => $validatedData['registration_id']],
                ['status' => 'belum final', 'is_paid' => 0]
            );

            $medicalAction = OrderTindakanMedis::create($validatedData);

            // FIX: Eager load all necessary relationships to return complete data
            $medicalAction->load(['employee', 'user.employee', 'tindakan_medis', 'departement']);

            $registration = Registration::with('penjamin.group_penjamin', 'kelas_rawat')->find($validatedData['registration_id']);
            $groupPenjaminId = $registration->penjamin->group_penjamin->id;
            $kelasId = $registration->kelas_rawat->id ?? 1;

            $totalTarif = $medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId);
            $wajibBayar = $validatedData['qty'] * $totalTarif - ($request->diskon ?? 0);

            $tagihanPasien = TagihanPasien::create([
                'user_id' => $validatedData['user_id'],
                'bilingan_id' => $bilingan->id,
                'registration_id' => $validatedData['registration_id'],
                'tindakan_medis_id' => $validatedData['tindakan_medis_id'],
                'date' => now(),
                'tagihan' => '[Tindakan Medis] ' . $medicalAction->tindakan_medis->nama_billing,
                'quantity' => $validatedData['qty'],
                'nominal_awal' => $totalTarif,
                'nominal' => $validatedData['qty'] * $totalTarif,
                'tipe_diskon' => $request->tipe_diskon,
                'disc' => $request->disc,
                'diskon' => $request->diskon,
                'jamin' => $request->jamin,
                'jaminan' => $request->jaminan,
                'wajib_bayar' => $wajibBayar,
            ]);

            BilinganTagihanPasien::create([
                'tagihan_pasien_id' => $tagihanPasien->id,
                'bilingan_id' => $bilingan->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                // FIX: Return the full medicalAction object with its relations
                'data' => $medicalAction,
                'message' => 'Tindakan medis berhasil disimpan!',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Data tidak valid.",
                "errors" => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan tindakan medis: " . $e->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Gagal menyimpan tindakan medis. Error: " . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        Log::info("Mencoba menghapus OrderTindakanMedis ID: {$id}");
        $action = OrderTindakanMedis::find($id);

        if (!$action) {
            Log::warning("OrderTindakanMedis ID: {$id} tidak ditemukan.");
            return response()->json([
                "success" => false,
                "message" => "Tindakan medis tidak ditemukan.",
            ], 404);
        }

        DB::beginTransaction();
        Log::info("Transaksi dimulai untuk menghapus OrderTindakanMedis ID: {$id}");

        try {
            // This logic finds the specific patient bill associated with this medical action.
            // It links them by matching multiple fields since a direct foreign key might not exist.
            // This is a critical step to ensure the correct bill is removed.
            $relatedTagihan = TagihanPasien::where('registration_id', $action->registration_id)
                ->where('tindakan_medis_id', $action->tindakan_medis_id)
                ->where('quantity', $action->qty)
                ->where('user_id', $action->user_id)
                ->latest() // Gets the most recent one in case of duplicates
                ->first();

            if ($relatedTagihan) {
                Log::info("TagihanPasien ID: {$relatedTagihan->id} ditemukan, akan dihapus.");
                BilinganTagihanPasien::where('tagihan_pasien_id', $relatedTagihan->id)->delete();
                $relatedTagihan->delete();
                Log::info("TagihanPasien ID: {$relatedTagihan->id} dan relasi pivot berhasil dihapus.");
            } else {
                Log::warning("Tidak ada TagihanPasien terkait yang ditemukan untuk OrderTindakanMedis ID: {$id}.");
            }

            $action->delete();
            Log::info("OrderTindakanMedis ID: {$id} berhasil dihapus (soft delete).");

            DB::commit();
            Log::info("Transaksi untuk menghapus OrderTindakanMedis ID: {$id} berhasil di-commit.");

            return response()->json([
                "success" => true,
                "message" => "Tindakan medis dan tagihan terkait berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("TRANSAKSI GAGAL! Rollback dieksekusi untuk OrderTindakanMedis ID: {$id}. Error: " . $e->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan pada server saat menghapus data.",
            ], 500);
        }
    }
}
