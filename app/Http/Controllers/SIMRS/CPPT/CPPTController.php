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
            $id = $request->registration_id;
            $cppt = CPPT::where('registration_id', $id)->with('user.employee')->orderBy('created_at', 'desc')->get();

            if ($cppt->isNotEmpty()) {
                $cppt = $cppt->map(function ($item) {
                    $item->nama = optional($item->user->employee)->fullname;

                    // Modifikasi tipe_rawat menjadi format huruf kapital pada setiap kata
                    if (!empty($item->tipe_rawat)) {
                        $item->tipe_rawat = $item->tipe_rawat === 'igd'
                            ? 'UGD'
                            : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                    }

                    return $item;
                });

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
            $registration_type = Registration::find($request->registration_id)->registration_type;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['tipe_rawat'] = $registration_type;

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
