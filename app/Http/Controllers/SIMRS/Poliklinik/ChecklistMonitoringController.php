<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ChecklistMonitoringController extends Controller
{
    /**
     * Display the checklist monitoring page
     */
    public function index(): View
    {
        return view('pages.simrs.poliklinik.checklist-monitoring');
    }

    /**
     * Get patient data by patient ID or registration ID from database
     */
    public function getPatient(Request $request): JsonResponse
    {
        try {
            $patientId = $request->get('patient_id');
            $registrationId = $request->get('registration_id');

            if ($registrationId) {
                // Get data by registration ID - most direct approach
                $registration = Registration::with([
                    'patient:id,medical_record_number,name,date_of_birth,gender,address,mobile_phone_number',
                    'departement:id,name',
                    'doctor.employee:id,fullname',
                ])->find($registrationId);

                if (! $registration) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data registrasi tidak ditemukan',
                    ], 404);
                }

                $patient = $registration->patient;
            } elseif ($patientId) {
                // Get patient data and their latest registration for today
                $patient = Patient::find($patientId);

                if (! $patient) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pasien tidak ditemukan',
                    ], 404);
                }

                // Get latest registration for today
                $registration = $patient->registration()
                    ->with(['departement:id,name', 'doctor.employee:id,fullname'])
                    ->whereDate('registration_date', Carbon::today())
                    ->latest('created_at')
                    ->first();

                if (! $registration) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pasien tidak memiliki registrasi hari ini',
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient ID atau Registration ID diperlukan',
                ], 400);
            }

            // Build patient data from database
            $patientData = [
                'id' => $patient->id,
                'registration_id' => $registration->id,
                'medical_record_number' => $patient->medical_record_number,
                'patient_name' => $patient->name,
                'date_of_birth' => $patient->date_of_birth,
                'age' => $patient->date_of_birth
                    ? Carbon::parse($patient->date_of_birth)->diffInYears(Carbon::now())
                    : null,
                'gender' => $patient->gender,
                'address' => $patient->address,
                'mobile_phone_number' => $patient->mobile_phone_number,
                'polyclinic_name' => $registration->departement->name ?? null,
                'doctor_name' => $registration->doctor->employee->fullname ?? null,
                'registration_number' => $registration->registration_number,
                'registration_date' => $registration->registration_date,
                'registration_time' => $registration->created_at?->format('H:i:s'),
                'registration_type' => $registration->registration_type,
                'patient_status' => $registration->patient_status ?? 'Aktif',
            ];

            return response()->json([
                'success' => true,
                'data' => $patientData,
                'message' => 'Data pasien berhasil diambil dari database',
            ]);
        } catch (Exception $e) {
            Log::error('Error getting patient data from database: '.$e->getMessage(), [
                'patient_id' => $patientId ?? null,
                'registration_id' => $registrationId ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pasien dari database',
            ], 500);
        }
    }

    /**
     * Start monitoring for a patient
     */
    public function startMonitoring(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'patient_id' => 'required|integer',
                'monitoring_items' => 'required|array|min:1',
                'start_time' => 'required|date',
            ]);

            $patientId = $request->get('patient_id');
            $monitoringItems = $request->get('monitoring_items');
            $startTime = Carbon::parse($request->get('start_time'));

            // Get patient registration
            $registration = Registration::where('patient_id', $patientId)
                ->whereDate('registration_date', Carbon::today())
                ->latest('created_at')
                ->first();

            if (! $registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi pasien tidak ditemukan',
                ], 404);
            }

            // Here you would typically save to a monitoring table
            // For now, we'll create a basic monitoring record structure
            $monitoringData = [
                'registration_id' => $registration->id,
                'patient_id' => $patientId,
                'monitoring_items' => json_encode($monitoringItems),
                'start_time' => $startTime,
                'status' => 'active',
                'created_by' => Auth::user()->id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // TODO: Save to monitoring table when created
            // DB::table('patient_monitoring')->insert($monitoringData);

            Log::info('Monitoring started', [
                'patient_id' => $patientId,
                'registration_id' => $registration->id,
                'monitoring_items' => $monitoringItems,
                'start_time' => $startTime,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Monitoring berhasil dimulai',
                'data' => [
                    'monitoring_id' => uniqid('MON_'), // Temporary ID
                    'patient_name' => $registration->patient->name,
                    'monitoring_items' => $monitoringItems,
                    'start_time' => $startTime->format('d/m/Y H:i:s'),
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Error starting monitoring: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memulai monitoring',
            ], 500);
        }
    }

    /**
     * Save checklist data
     */
    public function saveChecklist(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'patient_id' => 'required|integer',
                'checklist_items' => 'required|array|min:1',
            ]);

            $patientId = $request->get('patient_id');
            $checklistItems = $request->get('checklist_items');

            // Get patient registration
            $registration = Registration::where('patient_id', $patientId)
                ->whereDate('registration_date', Carbon::today())
                ->latest('created_at')
                ->first();

            if (! $registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi pasien tidak ditemukan',
                ], 404);
            }

            // Here you would typically save to a checklist table
            $checklistData = [
                'registration_id' => $registration->id,
                'patient_id' => $patientId,
                'checklist_items' => json_encode($checklistItems),
                'created_by' => Auth::user()->id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // TODO: Save to checklist table when created
            // DB::table('patient_checklists')->insert($checklistData);

            Log::info('Checklist saved', [
                'patient_id' => $patientId,
                'registration_id' => $registration->id,
                'checklist_items' => $checklistItems,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checklist berhasil disimpan',
                'data' => [
                    'patient_name' => $registration->patient->name,
                    'checklist_items' => $checklistItems,
                    'saved_at' => now()->format('d/m/Y H:i:s'),
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Error saving checklist: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan checklist',
            ], 500);
        }
    }

    /**
     * Get monitoring history for a patient
     */
    public function getMonitoringHistory(Request $request): JsonResponse
    {
        try {
            $patientId = $request->get('patient_id');
            $limit = $request->get('limit', 10);

            if (! $patientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient ID diperlukan',
                ], 400);
            }

            // Get monitoring history
            $history = Registration::where('patient_id', $patientId)
                ->with([
                    'patient:id,medical_record_number,name',
                    'departement:id,name',
                    'doctor.employee:id,fullname',
                ])
                ->orderBy('registration_date', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'registration_number' => $registration->registration_number,
                        'registration_date' => $registration->registration_date,
                        'patient_name' => $registration->patient->name,
                        'medical_record_number' => $registration->patient->medical_record_number,
                        'polyclinic_name' => $registration->departement->name ?? null,
                        'doctor_name' => $registration->doctor->employee->fullname ?? null,
                        'status' => $registration->registration_status ?? 'Aktif',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $history,
            ]);
        } catch (Exception $e) {
            Log::error('Error getting monitoring history: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil riwayat monitoring',
            ], 500);
        }
    }

    /**
     * Get monitoring statistics
     */
    public function getMonitoringStats(Request $request): JsonResponse
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);

            // Get monitoring statistics
            $stats = [
                'total_registrations' => Registration::whereDate('registration_date', $selectedDate)->count(),
                'active_monitoring' => Registration::whereDate('registration_date', $selectedDate)
                    ->where('registration_status', 'active')
                    ->count(),
                'completed_monitoring' => Registration::whereDate('registration_date', $selectedDate)
                    ->where('registration_status', 'completed')
                    ->count(),
                'date' => $selectedDate->format('d/m/Y'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (Exception $e) {
            Log::error('Error getting monitoring stats: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik monitoring',
            ], 500);
        }
    }

    /**
     * Search patients for monitoring
     */
    public function searchPatients(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $limit = $request->get('limit', 10);

            if (empty($search)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter pencarian diperlukan',
                ], 400);
            }

            $patients = Registration::with([
                'patient:id,medical_record_number,name,date_of_birth,gender',
                'departement:id,name',
            ])
                ->whereDate('registration_date', Carbon::today())
                ->where(function ($query) use ($search) {
                    $query->whereHas('patient', function ($q) use ($search) {
                        $q->where('medical_record_number', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    })
                        ->orWhere('registration_number', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'patient_id' => $registration->patient->id,
                        'registration_number' => $registration->registration_number,
                        'medical_record_number' => $registration->patient->medical_record_number,
                        'patient_name' => $registration->patient->name,
                        'date_of_birth' => $registration->patient->date_of_birth,
                        'age' => $registration->patient->date_of_birth
                            ? Carbon::parse($registration->patient->date_of_birth)->diffInYears(Carbon::now())
                            : null,
                        'gender' => $registration->patient->gender,
                        'polyclinic_name' => $registration->departement->name ?? null,
                        'registration_date' => $registration->registration_date,
                        'registration_time' => $registration->created_at?->format('H:i:s'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $patients,
            ]);
        } catch (Exception $e) {
            Log::error('Error searching patients: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari pasien',
            ], 500);
        }
    }
}
