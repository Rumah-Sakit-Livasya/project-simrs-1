<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;

class PatientMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $departements = Departement::all();
        $doctors = Doctor::all();
        $departementId = $request->get('departement_id', '');
        $doctorId = $request->get('doctor_id', '');

        return view('pages.simrs.monitoring.patient-monitoring', compact(
            'date',
            'departements',
            'doctors',
            'departementId',
            'doctorId'
        ));
    }

    // ===============================
    // Data Monitoring untuk table
    // ===============================
    public function getMonitoringData(Request $request)
    {
        $query = Registration::with(['patient', 'doctor', 'departement']);

        if ($request->departement_id) {
            $query->where('departement_id', $request->departement_id);
        }
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->date) {
            $query->whereDate('registration_date', $request->date);
        }

        $registrations = $query->get();

        $data = $registrations->map(function ($reg) {
            $stages = [
                'pengkajian_awal' => 'Pengkajian Awal',
                'pengkajian_dokter' => 'Pengkajian Dokter',
                'resume_medis' => 'Resume Medis',
                'diagnosa' => 'Diagnosa',
                'tindakan' => 'Tindakan',
                'resep' => 'Resep',
                'obat_alkes_ruangan' => 'Obat/Alkes',
                'bhp' => 'BHP',
                'laboratorium' => 'Lab',
                'radiologi' => 'Rad',
                'fisio' => 'Fisio',
                'hemodialisa' => 'Hemo',
                'keluar' => 'Keluar',
                'tagihan' => 'Tagihan',
            ];

            $stage_status = [];
            foreach ($stages as $key => $label) {
                $stage_status[$key] = $reg->$key ? 'completed' : 'pending';
            }

            $completion_percentage = round(array_count_values($stage_status)['completed'] ?? 0 / count($stages) * 100);

            return [
                'registration_id' => $reg->id,
                'patient_name' => $reg->patient->name ?? '-',
                'medical_record_number' => $reg->patient->medical_record_number ?? '-',
                'doctor_name' => $reg->doctor->employee->fullname ?? '-',
                'departement_name' => $reg->departement->name ?? '-',
                'penjamin_name' => $reg->penjamin->nama_perusahaan ?? '-',
                'stages' => $stages,
                'stage_status' => $stage_status,
                'completion_percentage' => $completion_percentage,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===============================
    // Statistik Monitoring
    // ===============================
    public function getMonitoringStats(Request $request)
    {
        $query = Registration::query();

        if ($request->departement_id) {
            $query->where('departement_id', $request->departement_id);
        }
        if ($request->date) {
            $query->whereDate('registration_date', $request->date);
        }

        $registrations = $query->get();

        $total = $registrations->count();
        $completed_assessment_nurse = $registrations->where('pengkajian_awal', 1)->count();
        $completed_assessment_doctor = $registrations->where('pengkajian_dokter', 1)->count();
        $completed_resume = $registrations->where('resume_medis', 1)->count();
        $completed_treatment = $registrations->where('tindakan', 1)->count();
        $completed_lab = $registrations->where('laboratorium', 1)->count();
        $completed_rad = $registrations->where('radiologi', 1)->count();
        $completed_discharge = $registrations->where('keluar', 1)->count();

        $data = [
            ['key' => 'total_patients', 'label' => 'Total Pasien', 'value' => $total, 'percentage' => 100],
            ['key' => 'completed_assessment_nurse', 'label' => 'Pengkajian Perawat', 'value' => $completed_assessment_nurse, 'percentage' => $total ? ($completed_assessment_nurse / $total * 100) : 0],
            ['key' => 'completed_assessment_doctor', 'label' => 'Pengkajian Dokter', 'value' => $completed_assessment_doctor, 'percentage' => $total ? ($completed_assessment_doctor / $total * 100) : 0],
            ['key' => 'completed_resume', 'label' => 'Resume Medis', 'value' => $completed_resume, 'percentage' => $total ? ($completed_resume / $total * 100) : 0],
            ['key' => 'completed_treatment', 'label' => 'Tindakan', 'value' => $completed_treatment, 'percentage' => $total ? ($completed_treatment / $total * 100) : 0],
            ['key' => 'completed_lab', 'label' => 'Laboratorium', 'value' => $completed_lab, 'percentage' => $total ? ($completed_lab / $total * 100) : 0],
            ['key' => 'completed_rad', 'label' => 'Radiologi', 'value' => $completed_rad, 'percentage' => $total ? ($completed_rad / $total * 100) : 0],
            ['key' => 'completed_discharge', 'label' => 'Keluar', 'value' => $completed_discharge, 'percentage' => $total ? ($completed_discharge / $total * 100) : 0],
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===============================
    // Detail Monitoring
    // ===============================
    public function getMonitoringDetail(Request $request)
    {
        $reg = Registration::with(['patient', 'doctor', 'departement'])->find($request->registration_id);
        if (!$reg) return response()->json(['success' => false, 'message' => 'Registrasi tidak ditemukan']);

        $stages = [
            'pengkajian_awal' => 'Pengkajian Awal',
            'pengkajian_dokter' => 'Pengkajian Dokter',
            'resume_medis' => 'Resume Medis',
            'diagnosa' => 'Diagnosa',
            'tindakan' => 'Tindakan',
            'resep' => 'Resep',
            'obat_alkes_ruangan' => 'Obat/Alkes',
            'bhp' => 'BHP',
            'laboratorium' => 'Lab',
            'radiologi' => 'Rad',
            'fisio' => 'Fisio',
            'hemodialisa' => 'Hemo',
            'keluar' => 'Keluar',
            'tagihan' => 'Tagihan',
        ];

        $stage_status = [];
        foreach ($stages as $key => $label) {
            $stage_status[$key] = $reg->$key ? 'completed' : 'pending';
        }

        $completion_percentage = round(array_count_values($stage_status)['completed'] ?? 0 / count($stages) * 100);

        $completed_stages = array_count_values($stage_status)['completed'] ?? 0;
        $total_stages = count($stages);

        $data = [
            'registration_id' => $reg->id,
            'patient_name' => $reg->patient->fullname ?? '-',
            'medical_record_number' => $reg->patient->medical_record_number ?? '-',
            'doctor_name' => $reg->doctor->fullname ?? '-',
            'departement_name' => $reg->departement->name ?? '-',
            'registration_date' => $reg->registration_date->format('Y-m-d'),
            'stages' => $stages,
            'stage_status' => $stage_status,
            'completion_percentage' => $completion_percentage,
            'completed_stages' => $completed_stages,
            'total_stages' => $total_stages,
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}
