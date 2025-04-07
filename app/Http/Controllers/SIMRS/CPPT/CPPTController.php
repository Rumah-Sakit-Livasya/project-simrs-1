<?php

namespace App\Http\Controllers\SIMRS\CPPT;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;

class CPPTController extends Controller
{
    public function getCPPT(Request $request)
    {
        try {
            $noRM = $request->no_rm;
            $cppt = CPPT::where('medical_record_number', $noRM)->get();
            if ($cppt) {
                return response()->json($cppt, 200);
            } else {

                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request, $type, $registration_number)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required',
            'doctor_id' => 'nullable',
            'konsulkan_ke' => 'nullable',
            'subjective' => 'required',
            'objective' => 'required',
            'assesment' => 'required',
            'planning' => 'required',
            'instruksi' => 'nullable',
            'evaluasi' => 'nullable',
            'implementasi' => 'nullable',
            'medical_record_number' => 'required'
        ]);

        try {
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['tipe_rawat'] = 'rawat-jalan';

            if (auth()->user()->employee->doctor) {
                $validatedData['tipe_cppt'] = 'dokter';
            } else if (str_contains(auth()->user()->name, "A.Md.Kep")) {
                $validatedData['tipe_cppt'] = 'perawat';
            } else if (str_contains(auth()->user()->name, "A.Md.Keb")) {
                $validatedData['tipe_cppt'] = 'bidan';
            } else {
                $validatedData['tipe_cppt'] = auth()->user()->employee->organization->name;
            }

            $store = CPPT::create($validatedData);
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
