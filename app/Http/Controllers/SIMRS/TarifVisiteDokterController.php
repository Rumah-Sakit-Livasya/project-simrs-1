<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\TarifVisiteDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TarifVisiteDokterController extends Controller
{
    public function index(Request $request)
    {
        // [UBAH TOTAL] Logika untuk menampilkan daftar dokter
        if ($request->ajax()) {
            // Ambil semua employee yang memiliki flag is_doctor = 1
            // Pastikan relasi 'doctor' ada untuk mendapatkan ID dokter
            $data = Employee::where('is_doctor', 1)->with(['doctor', 'organization', 'jobPosition'])->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('organization_name', function ($row) {
                    return $row->organization->name ?? 'N/A';
                })
                ->addColumn('job_position_name', function ($row) {
                    return $row->jobPosition->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    // Tombol akan mengarah ke halaman set-tarif-popup
                    // Kita butuh doctor_id, bukan employee_id, untuk halaman popup
                    if ($row->doctor) {
                        $doctorId = $row->doctor->id;
                        $btn = '<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed set-tarif-btn" data-doctor-id="' . $doctorId . '">
                                    <i class="fal fa-cogs"></i> Set Tarif per Kelas
                                </button>';
                        return $btn;
                    }
                    return '<span class="badge badge-warning">Data Dokter tidak ditemukan</span>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // View tidak lagi memerlukan data $doctors atau $kelasRawat
        return view('pages.simrs.tarif-visite-dokter.index');
    }

    /**
     * Menyimpan array tarif untuk dokter dan berbagai kombinasi kelas rawat & group penjamin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'tariffs' => 'required|array',
            'tariffs.*.kelas_rawat_id' => 'required|exists:kelas_rawat,id',
            'tariffs.*.group_penjamin_id' => 'required|exists:group_penjamin,id',
            'tariffs.*.share_rs' => 'nullable|numeric|min:0',
            'tariffs.*.share_dr' => 'nullable|numeric|min:0',
            'tariffs.*.prasarana' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $doctorId = $request->doctor_id;

            foreach ($request->tariffs as $tariffData) {
                // Proses baris hanya jika minimal salah satu entri ada
                if (
                    isset($tariffData['share_rs']) ||
                    isset($tariffData['share_dr']) ||
                    isset($tariffData['prasarana'])
                ) {
                    TarifVisiteDokter::updateOrCreate(
                        [
                            'doctor_id' => $doctorId,
                            'kelas_rawat_id' => $tariffData['kelas_rawat_id'],
                            'group_penjamin_id' => $tariffData['group_penjamin_id'],
                        ],
                        [
                            'share_rs' => $tariffData['share_rs'] ?? 0,
                            'share_dr' => $tariffData['share_dr'] ?? 0,
                            'prasarana' => $tariffData['prasarana'] ?? 0,
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json(['message' => 'Tarif berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman popup untuk setting tarif dokter dalam bentuk matriks.
     */
    public function setTariffForDoctor(Doctor $doctor)
    {
        $kelasRawat = KelasRawat::orderBy('kelas')->get();
        $groupPenjamins = GroupPenjamin::get();
        $doctor->load('employee');

        // [BARU] Ambil daftar dokter lain (yang bukan dokter saat ini) yang sudah memiliki tarif
        $sourceDoctors = Doctor::whereHas('tarif_visite') // Hanya dokter yang punya relasi ke tarif
            ->where('id', '!=', $doctor->id)      // Kecualikan dokter yang sedang diedit
            ->with('employee')
            ->get();

        $existingTariffs = TarifVisiteDokter::where('doctor_id', $doctor->id)
            ->get()
            ->keyBy(function ($item) {
                return $item->kelas_rawat_id . '_' . $item->group_penjamin_id;
            });

        // [UBAH] Kirim $sourceDoctors ke view
        return view('pages.simrs.tarif-visite-dokter.set-tarif-popup', compact('doctor', 'kelasRawat', 'groupPenjamins', 'existingTariffs', 'sourceDoctors'));
    }

    // [BARU] Endpoint API untuk mengambil data tarif dokter dalam format JSON
    public function getTariffsByDoctorAsJson(Doctor $doctor)
    {
        $tariffs = TarifVisiteDokter::where('doctor_id', $doctor->id)->get();
        return response()->json($tariffs);
    }
}
