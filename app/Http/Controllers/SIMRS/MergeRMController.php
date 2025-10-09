<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // <-- Tambahkan ini

class MergeRMController extends Controller
{
    // Method index() dan searchPatient() tidak perlu diubah, biarkan seperti sebelumnya.
    public function index()
    {
        return view('pages.simrs.rekam-medis.merge-rm');
    }

    public function searchPatient(Request $request)
    {
        $term = $request->term;
        $patients = Patient::where('status', 'aktif') // <-- TAMBAHKAN INI
            ->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('medical_record_number', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get([
                'id',
                'name as name_real',
                'medical_record_number as norm',
                'id_card as identification_cards',
                'place as place_of_birth',
                'date_of_birth',
                'gender',
                'married_status as marital_status',
                'religion',
                'email',
                'address',
                'mobile_phone_number'
            ]);

        foreach ($patients as $patient) {
            $patient->date_of_birth = \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y');
        }

        return response()->json($patients);
    }

    /**
     * Memproses aksi penggabungan rekam medis. (VERSI FINAL DENGAN FLAG)
     */
    public function mergeAction(Request $request)
    {
        $request->validate([
            'norm' => 'required|exists:patients,medical_record_number',
            'norm_to' => 'required|exists:patients,medical_record_number',
            'keep_patient_data' => [
                'required',
                Rule::in([$request->norm, $request->norm_to])
            ]
        ], [
            'keep_patient_data.required' => 'Anda harus memilih data pasien mana yang akan dipertahankan.'
        ]);

        if ($request->norm == $request->norm_to) {
            return redirect()->back()->with('error', 'Rekam Medis Asal dan Tujuan tidak boleh sama!');
        }

        DB::beginTransaction();
        try {
            $patientFrom = Patient::where('medical_record_number', $request->norm)->firstOrFail();
            $patientTo = Patient::where('medical_record_number', $request->norm_to)->firstOrFail();

            if ($request->keep_patient_data == $patientTo->medical_record_number) {
                $patientToKeep = $patientTo;
                $patientToMerge = $patientFrom;
            } else {
                $patientToKeep = $patientFrom;
                $patientToMerge = $patientTo;
            }

            // Pindahkan semua data relasi dari patientToMerge ke patientToKeep
            Registration::where('patient_id', $patientToMerge->id)->update(['patient_id' => $patientToKeep->id]);

            // Tambahkan pemindahan data dari tabel relasi lainnya di sini...
            // \App\Models\SIMRS\Billing::where('patient_id', $patientToMerge->id)->update(['patient_id' => $patientToKeep->id]);

            // === PERUBAHAN UTAMA: UPDATE FLAG, BUKAN DELETE ===
            // Non-aktifkan pasien yang datanya digabungkan
            $patientToMerge->status = 'digabung';
            $patientToMerge->merged_to_rm = $patientToKeep->medical_record_number;
            $patientToMerge->save();
            // ===============================================

            // Catat log
            Log::info('Penggabungan RM berhasil: RM ' . $patientToMerge->medical_record_number . ' digabung ke RM ' . $patientToKeep->medical_record_number . ' oleh user ' . auth()->id());

            DB::commit();

            return redirect()->route('rekam-medis.merge.form')->with('success', 'Penggabungan Rekam Medis berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Penggabungan RM Gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat penggabungan data: ' . $e->getMessage());
        }
    }
}
