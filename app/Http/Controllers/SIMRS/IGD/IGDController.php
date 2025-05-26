<?php

namespace App\Http\Controllers\SIMRS\IGD;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Peralatan\Peralatan;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IGDController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::query()
            ->where('registration_type', 'igd');

        $hasFilter = false;

        $simpleFilters = [
            'registration_number' => $request->registration_number,
        ];

        // Filter langsung
        foreach ($simpleFilters as $column => $value) {
            if (!empty($value)) {
                $query->where($column, 'like', '%' . $value . '%');
                $hasFilter = true;
            }
        }

        // Filter berdasarkan relasi patient
        if (!empty($request->medical_record_number)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $hasFilter = true;
        }

        // Filter berdasarkan nama pasien (relasi)
        if (!empty($request->name)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $hasFilter = true;
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
                $hasFilter = true;
            }
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === 'aktif' ? 'aktif' : 'tutup_kunjungan');
            $hasFilter = true;
        }

        // Default: jika tidak ada filter aktif, ambil data hari ini
        if (!$hasFilter) {
            $today = now()->format('Y-m-d');
            $query->whereBetween('registration_date', [
                $today . ' 00:00:00',
                $today . ' 23:59:59',
            ]);
        }

        $registrations = $query->orderBy('date', 'asc')->get();

        return view('pages.simrs.igd.daftar-pasien', [
            'registrations' => $registrations,
        ]);
    }

    public function getTriage($id)
    {
        $registration = Registration::find($id);
        $triage = Triage::where('registration_id', $registration->id)->first();

        if (!$triage) {
            return response()->json([
                'message' => 'Data Triage tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data Triage ditemukan.',
            'data' => $triage
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi jika diperlukan
        $request->validate([
            'tgl_masuk' => 'required|date',
            'jam_masuk' => 'required',
            'jam_dilayani' => 'required',
            'pr' => 'nullable|integer',
            'bp' => 'nullable|string',
        ]);

        // Simpan ke database
        $triage = \App\Models\SIMRS\Pelayanan\Triage::updateOrCreate(
            ['registration_id' => $request->registration_id], // Kondisi pencarian
            [
                'tgl_masuk' => $request->tgl_masuk,
                'jam_masuk' => $request->jam_masuk,
                'jam_dilayani' => $request->jam_dilayani,
                'pr' => $request->pr,
                'bp' => $request->bp,
                'body_height' => $request->body_height,
                'bmi' => $request->bmi,
                'lingkar_dada' => $request->lingkar_dada,
                'sp02' => $request->sp02,
                'rr' => $request->rr,
                'temperatur' => $request->temperatur,
                'body_weight' => $request->body_weight,
                'kat_bmi' => $request->kat_bmi,
                'lingkar_perut' => $request->lingkar_perut,
                'auto_anamnesa' => $request->has('auto_anamnesa'),
                'allo_anamnesa' => $request->has('allo_anamnesa'),
                'airway_merah' => json_encode($request->airway_merah),
                'airway_kuning' => json_encode($request->airway_kuning),
                'airway_hijau' => json_encode($request->airway_hijau),
                'breathing_merah' => json_encode($request->breathing_merah),
                'breathing_kuning' => json_encode($request->breathing_kuning),
                'breathing_hijau' => json_encode($request->breathing_hijau),
                'circulation_merah' => json_encode($request->circulation_merah),
                'circulation_kuning' => json_encode($request->circulation_kuning),
                'circulation_hijau' => json_encode($request->circulation_hijau),
                'disability' => json_encode($request->disability),
                'kesimpulan' => json_encode($request->kesimpulan),
                'daa_hitam' => $request->has('daa_hitam')
            ]
        );

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $triage
        ], 201);
    }
}
