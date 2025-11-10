<?php

namespace App\Http\Controllers\SIMRS\Pasien;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Patient;
use Illuminate\Http\Request;

class PatientSearchController extends Controller
{
    /**
     * Search patients for global search bar
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $patients = Patient::with(['registration' => function ($q) {
            $q->latest()->limit(1);
        }])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('medical_record_number', 'LIKE', "%{$query}%")
                    ->orWhere('nik', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'medical_record_number' => $patient->medical_record_number,
                    'nik' => $patient->nik,
                    'date_of_birth' => $patient->date_of_birth
                        ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y')
                        : 'N/A',
                    'gender' => $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                    'registration' => $patient->registration->map(function ($reg) {
                        return [
                            'id' => $reg->id,
                            'status' => $reg->status,
                            'registration_number' => $reg->registration_number
                        ];
                    })
                ];
            });

        return response()->json($patients);
    }

    /**
     * Quick access to patient dashboard
     */
    public function quickAccess($patientId)
    {
        $patient = Patient::with(['registration' => function ($q) {
            $q->where('status', 'aktif')->latest();
        }])->findOrFail($patientId);

        // Jika ada registrasi aktif, redirect ke halaman registrasi
        if ($patient->registration->isNotEmpty()) {
            $activeRegistration = $patient->registration->first();
            return redirect()->route('registration.show', $activeRegistration->id);
        }

        // Jika tidak ada registrasi aktif, redirect ke dashboard pasien
        return redirect()->route('patients.dashboard', $patient->id);
    }
}
