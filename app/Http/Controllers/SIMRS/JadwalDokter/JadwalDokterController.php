<?php

namespace App\Http\Controllers\SIMRS\JadwalDokter;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $departements = Departement::all();
        $doctors = Doctor::with('department_from_doctors')->get();
        return view('pages.simrs.master-data.jadwal-dokter.index', compact('departements', 'doctors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'doctor_id' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota_regis_online' => 'nullable',
        ]);

        try {

            if ($request->hari == 'Semua Hari') {
                $hari = [
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                ];

                foreach ($hari as $h) {
                    $validatedData['hari'] = $h;

                    // Cek apakah sudah ada entri dengan doctor_id dan hari
                    JadwalDokter::updateOrCreate(
                        [
                            'doctor_id' => $validatedData['doctor_id'],
                            'hari' => $h,
                        ],
                        [
                            // Data yang ingin diupdate jika sudah ada
                            'jam_mulai' => $validatedData['jam_mulai'],
                            'jam_selesai' => $validatedData['jam_selesai'],
                            'kuota_regis_online' => $validatedData['kuota_regis_online'],
                        ]
                    );
                }
            } else {
                // Update atau buat untuk hari tertentu
                JadwalDokter::updateOrCreate(
                    [
                        'doctor_id' => $validatedData['doctor_id'],
                        'hari' => $validatedData['hari'],
                    ],
                    [
                        // Data yang ingin diupdate jika sudah ada
                        'jam_mulai' => $validatedData['jam_mulai'],
                        'jam_selesai' => $validatedData['jam_selesai'],
                        'kuota_regis_online' => $validatedData['kuota_regis_online'],
                    ]
                );
            }

            return response()->json(['message' => 'Jadwal berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(JadwalDokter $jadwal)
    {
        // Load relasi yang dibutuhkan untuk menampilkan nama dokter
        $jadwal->load('doctor.employee');
        return response()->json($jadwal);
    }

    // TAMBAHKAN METHOD INI
    public function update(Request $request, JadwalDokter $jadwal)
    {
        $validatedData = $request->validate([
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota_regis_online' => 'nullable|integer',
        ]);

        try {
            $jadwal->update($validatedData);
            return response()->json(['message' => 'Jadwal berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
