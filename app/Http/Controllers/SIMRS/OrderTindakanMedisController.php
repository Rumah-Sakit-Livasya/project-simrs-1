<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

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
        // Validate the incoming request data
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

        // Create a new medical action
        $medicalAction = OrderTindakanMedis::create($validatedData);
        $groupPenjaminId = Registration::find($validatedData['registration_id'])->penjamin->group_penjamin->id;
        $kelasId = Registration::find($validatedData['registration_id'])->kelas_rawat->id;
        // return dd($groupPenjaminId . ' ' . $kelasId);
        // return dd($medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId));

        // Check if there are any bilingans associated with the registration
        $bilingan = \App\Models\SIMRS\Bilingan::where('registration_id', $validatedData['registration_id'])->first();

        // If no bilingan exists, create a new one
        if (!$bilingan) {
            $bilingan = \App\Models\SIMRS\Bilingan::create([
                'registration_id' => $validatedData['registration_id'],
                'status' => 'belum final',
                'is_paid' => 0,
                // Add other necessary fields for the bilingan here
            ]);
        }

        // Create the TagihanPasien record
        $tagihanPasien = \App\Models\SIMRS\TagihanPasien::create([
            'user_id' => $validatedData['user_id'],
            'registration_id' => $validatedData['registration_id'],
            'date' => now(), // Assuming this is the date of the action
            'tagihan' => '[Tindakan Medis] ' . $medicalAction->tindakan_medis->nama_billing, // Assuming 'nama' is a field in tindakan_medis
            'quantity' => $validatedData['qty'],
            'nominal' => $validatedData['qty'] * $medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId), // Fetch tarif using the new method
            'tipe_diskon' => $request->tipe_diskon ?? null, // Assuming you might have this in the request
            'disc' => $request->disc ?? null, // Assuming you might have this in the request
            'diskon' => $request->diskon ?? null, // Assuming you might have this in the request
            'jamin' => $request->jamin ?? null, // Assuming you might have this in the request
            'jaminan' => $request->jaminan ?? null, // Assuming you might have this in the request
            'wajib_bayar' => ($validatedData['qty'] * $medicalAction->tindakan_medis->getTotalTarif($groupPenjaminId, $kelasId)) - ($request->diskon ?? 0), // Calculate the amount to be paid
        ]);

        // Create the many-to-many relationship using BilinganTagihanPasien
        \App\Models\SIMRS\BilinganTagihanPasien::create([
            'tagihan_pasien_id' => $tagihanPasien->id,
            'bilingan_id' => $bilingan->id,
            'status' => 'belum final',
            'is_paid' => 0,
        ]);

        // Attach to the many-to-many relationship using BilinganTagihanPasien
        if ($request->bilingan_ids) {
            foreach ($request->bilingan_ids as $bilinganId) {
                \App\Models\SIMRS\BilinganTagihanPasien::create([
                    'tagihan_pasien_id' => $tagihanPasien->id,
                    'bilingan_id' => $bilinganId,
                ]);
            }
        }

        // Load the necessary relationships
        $medicalAction->load(['user.employee', 'doctor.employee', 'tindakan_medis', 'departement']);

        // Return a response
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
