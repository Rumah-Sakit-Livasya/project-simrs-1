<?php

namespace App\Http\Controllers\SIMRS\CPPT;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CPPTController extends Controller
{
    public function getJadwalDokter($departement_id)
    {
        $hariIni = ucfirst(Carbon::now()->locale('id')->isoFormat('dddd')); // e.g. 'Selasa'
        $jamSekarang = Carbon::now()->format('H:i:s'); // e.g. '14:33:06'

        $jadwal_dokter = JadwalDokter::with('doctor.employee')
            ->whereHas('doctor', function ($query) use ($departement_id) {
                $query->where('departement_id', $departement_id);
            })
            ->where('hari', $hariIni)
            ->where(function ($query) use ($jamSekarang) {
                $query->where(function ($q) use ($jamSekarang) {
                    // Jadwal normal (misal 08:00 - 17:00)
                    $q->whereRaw('jam_mulai <= jam_selesai')
                        ->where('jam_mulai', '<=', $jamSekarang)
                        ->where('jam_selesai', '>=', $jamSekarang);
                })->orWhere(function ($q) use ($jamSekarang) {
                    // Jadwal malam (misal 22:00 - 06:00)
                    $q->whereRaw('jam_mulai > jam_selesai')
                        ->where(function ($sub) use ($jamSekarang) {
                            $sub->where('jam_mulai', '<=', $jamSekarang)
                                ->orWhere('jam_selesai', '>=', $jamSekarang);
                        });
                });
            })
            ->get();

        $data = $jadwal_dokter->map(function ($item) {
            return [
                'doctor_id' => $item->doctor_id,
                'doctor_name' => $item->doctor->employee->fullname,
            ];
        });

        return response()->json($data);
    }

    public function getCPPT(Request $request)
    {
        try {
            $id = $request->registration_id;

            $cppt = CPPT::where('registration_id', $id)
                ->where('tipe_cppt', '!=', 'dokter')
                ->with(['user.employee', 'signature']) // tambahkan relasi signature
                ->orderBy('created_at', 'desc')
                ->get();

            if ($cppt->isNotEmpty()) {
                $cppt = $cppt->map(function ($item) {
                    $item->nama = optional($item->user->employee)->fullname;

                    // Format tipe_rawat
                    if (!empty($item->tipe_rawat)) {
                        $item->tipe_rawat = $item->tipe_rawat === 'igd'
                            ? 'UGD'
                            : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                    }

                    // Tambahkan full path ke signature jika ada
                    $item->signature_url = $item->signature
                        ? asset('storage/' . $item->signature->signature)
                        : null;

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


    public function getCPPTDokter(Request $request)
    {
        try {
            $id = $request->registration_id;

            $cppt = CPPT::where('registration_id', $id)
                ->where('tipe_cppt', '=', 'dokter')
                ->with('user.employee')->orderBy('created_at', 'desc')
                ->get();

            if ($cppt->isNotEmpty()) {
                $cppt = $cppt->map(function ($item) {
                    $item->nama = optional($item->user->employee)->fullname;

                    // Modifikasi tipe_rawat menjadi format huruf kapital pada setiap kata
                    if (!empty($item->tipe_rawat)) {
                        $item->tipe_rawat = $item->tipe_rawat === 'igd'
                            ? 'UGD'
                            : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                    }

                    // Tambahkan full path ke signature jika ada
                    $item->signature_url = $item->signature
                        ? asset('storage/' . $item->signature->signature)
                        : null;

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


            $cppt = CPPT::create($validatedData);

            // Handle signature jika ada
            if ($request->filled('signature_image')) {
                $imageData = $request->input('signature_image');
                $image = base64_decode(str_replace('data:image/png;base64,', '', str_replace(' ', '+', $imageData)));
                $imageName = 'ttd_' . time() . '.png';
                $path = 'signatures/' . $imageName;

                // Hapus tanda tangan lama jika ada
                if ($cppt->signature && \Storage::disk('public')->exists($cppt->signature->signature)) {
                    \Storage::disk('public')->delete($cppt->signature->signature);
                }

                // Simpan file baru
                \Storage::disk('public')->put($path, $image);

                // Simpan/Update relasi signature
                $cppt->signature()->updateOrCreate(
                    [
                        'signable_id' => $cppt->id,
                        'signable_type' => get_class($cppt),
                    ],
                    [
                        'signature' => $path,
                        'pic' => $request->input('pic'),
                        'role' => $request->input('role') ?? 'perawat',
                    ]
                );
            }

            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
