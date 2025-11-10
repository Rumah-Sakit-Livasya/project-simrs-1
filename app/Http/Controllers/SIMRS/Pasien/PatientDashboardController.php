<?php

namespace App\Http\Controllers\SIMRS\Pasien;

use App\Http\Controllers\Controller;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientDashboardController extends Controller
{
    /**
     * Display patient dashboard
     */
    public function index($patientId)
    {
        $pasien = Patient::with([
            'registration.departement',
            'registration.doctor.employee',
            'registration.penjamin'
        ])->findOrFail($patientId);

        // Get statistics
        $statistics = $this->getStatistics($pasien);

        // Get recent activities
        $recentActivities = $this->getRecentActivities($pasien);

        // Get registrations
        $registrations = $this->getRegistrations($pasien);

        // Get latest vitals
        $latestVitals = $this->getLatestVitals($pasien);

        // Get chart data
        $chartData = $this->getChartData($pasien);

        // Get documents
        $documents = $this->getDocuments($pasien);

        return view('app-type.simrs.pasien.dashboard', compact(
            'pasien',
            'statistics',
            'recentActivities',
            'registrations',
            'latestVitals',
            'chartData',
            'documents'
        ));
    }

    /**
     * Get patient statistics
     */
    private function getStatistics($pasien)
    {
        return [
            'total_visits' => $pasien->registration()->count(),
            'completed_visits' => $pasien->registration()
                ->where('status', 'selesai')
                ->count(),
            'total_procedures' => $pasien->registration()
                ->withCount('order_tindakan_medis')
                ->get()
                ->sum('order_tindakan_medis_count'),
            'total_assessments' => DB::table('cppt')
                ->whereIn('registration_id', $pasien->registration()->pluck('id'))
                ->count()
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($pasien)
    {
        $activities = collect();

        // Get recent registrations
        $recentRegistrations = $pasien->registration()
            ->with(['departement', 'doctor.employee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentRegistrations as $reg) {
            $activities->push([
                'type' => 'Registrasi',
                'title' => 'Kunjungan ke ' . ($reg->departement->name ?? 'N/A'),
                'description' => 'Dokter: ' . ($reg->doctor->employee->fullname ?? 'N/A'),
                'date' => $reg->created_at->format('d M Y, H:i'),
                'color' => '#667eea',
                'badge' => 'primary'
            ]);
        }

        // Get recent lab orders via OrderLaboratorium model concept
        $recentLabs = OrderLaboratorium::whereHas('registration', function ($q) use ($pasien) {
            $q->where('patient_id', $pasien->id);
        })
            ->with(['order_parameter_laboratorium.parameter_laboratorium'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentLabs as $lab) {
            $detailParam = $lab->order_parameter_laboratorium->pluck('parameter_laboratorium.nama_parameter')->filter()->implode(', ');
            $activities->push([
                'type' => 'Laboratorium',
                'title' => 'Pemeriksaan Laboratorium',
                'description' => 'Order No: ' . ($lab->order_number ?? '-') . ($detailParam ? ' | Parameter: ' . $detailParam : ''),
                'date' => $lab->created_at ? $lab->created_at->format('d M Y, H:i') : '-',
                'color' => '#28a745',
                'badge' => 'success'
            ]);
        }

        // Get recent radiology orders via OrderRadiologi model concept
        $recentRadiology = OrderRadiologi::whereHas('registration', function ($q) use ($pasien) {
            $q->where('patient_id', $pasien->id);
        })
            ->with(['order_parameter_radiologi.parameter_radiologi'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentRadiology as $rad) {
            $detailRad = $rad->order_parameter_radiologi->pluck('parameter_radiologi.nama_parameter')->filter()->implode(', ');
            $activities->push([
                'type' => 'Radiologi',
                'title' => 'Pemeriksaan Radiologi',
                'description' => 'Order No: ' . ($rad->order_number ?? '-') . ($detailRad ? ' | Parameter: ' . $detailRad : ''),
                'date' => $rad->created_at ? $rad->created_at->format('d M Y, H:i') : '-',
                'color' => '#ffc107',
                'badge' => 'warning'
            ]);
        }

        return $activities->sortByDesc('date')->take(10)->values();
    }

    /**
     * Get patient registrations
     */
    private function getRegistrations($pasien)
    {
        return $pasien->registration()
            ->with(['departement', 'doctor.employee', 'penjamin'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($reg) {
                return [
                    'id' => $reg->id,
                    'registration_number' => $reg->registration_number ?? 'N/A',
                    'jenis_rawat' => $reg->jenis_rawat ?? 'Rawat Jalan',
                    'date' => $reg->created_at->format('d M Y, H:i'),
                    'departement' => $reg->departement->name ?? 'N/A',
                    'doctor' => $reg->doctor->employee->fullname ?? 'N/A',
                    'penjamin' => $reg->penjamin->nama_perusahaan ?? 'N/A',
                    'status' => $reg->status ?? 'Aktif'
                ];
            });
    }

    /**
     * Get latest vital signs
     */
    private function getLatestVitals($pasien)
    {
        $latestRegistration = $pasien->registration()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestRegistration) {
            return null;
        }

        if ($latestRegistration->registration_type == 'igd') {
            $vitals = $latestRegistration->triage;
        } else if ($latestRegistration->registration_type == 'rawat-inap') {
            $vitals = $latestRegistration->pengkajian_perawat;
        } else {
            $vitals = $latestRegistration->pengkajian_nurse_rajal;
        }

        // $vitals = $latestRegistration->pengkajian_nurse_rajal;
        // Assuming vitals are stored in pengkajian_nurse_rajal

        if (!$vitals) {
            return null;
        }

        return (object) [
            'blood_pressure' => $vitals->bp ?? '-',
            'pulse' => $vitals->pr ?? '-',
            'temperature' => $vitals->temperatur ?? '-',
            'respiration' => $vitals->rr ?? '-',
            'created_at' => $vitals->created_at ?? now()
        ];
    }

    /**
     * Get chart data
     */
    private function getChartData($pasien)
    {
        // Visits chart data (last 6 months)
        $visitsData = $pasien->registration()
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $visitsLabels = $visitsData->pluck('month')
            ->map(function ($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M Y');
            });
        $visitsCounts = $visitsData->pluck('count');

        // Procedures chart data
        $proceduresData = DB::table('order_tindakan_medis')
            ->join('registrations', 'order_tindakan_medis.registration_id', '=', 'registrations.id')
            ->join('tindakan_medis', 'order_tindakan_medis.tindakan_medis_id', '=', 'tindakan_medis.id')
            ->where('registrations.patient_id', $pasien->id)
            ->selectRaw('tindakan_medis.nama_tindakan as nama_tindakan, COUNT(*) as count')
            ->groupBy('tindakan_medis.nama_tindakan')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $proceduresLabels = $proceduresData->pluck('nama_tindakan');
        $proceduresCounts = $proceduresData->pluck('count');

        return [
            'visits' => [
                'labels' => $visitsLabels,
                'data' => $visitsCounts
            ],
            'procedures' => [
                'labels' => $proceduresLabels,
                'data' => $proceduresCounts
            ]
        ];
    }

    /**
     * Get patient documents
     */
    private function getDocuments($pasien)
    {
        // Placeholder: Implement according to your documents storage
        return collect([]);
    }

    /**
     * Get vitals data for DataTable
     */
    public function getVitalsData(Request $request, $patientId)
    {
        $pasien = Patient::findOrFail($patientId);

        $query = DB::table('pengkajian_nurse_rajal')
            ->join('registrations', 'pengkajian_nurse_rajal.registration_id', '=', 'registrations.id')
            ->where('registrations.patient_id', $pasien->id)
            ->select([
                'pengkajian_nurse_rajal.created_at as date',
                'pengkajian_nurse_rajal.bp as blood_pressure',
                'pengkajian_nurse_rajal.pr as pulse',
                'pengkajian_nurse_rajal.temperatur as temperature',
                'pengkajian_nurse_rajal.rr as respiration',
                'pengkajian_nurse_rajal.body_weight as weight',
                'pengkajian_nurse_rajal.body_height as height'
            ]);

        return datatables()->of($query)
            ->editColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d M Y H:i');
            })
            ->editColumn('blood_pressure', function ($row) {
                return $row->blood_pressure ?? '-';
            })
            ->editColumn('pulse', function ($row) {
                return $row->pulse ? $row->pulse . ' x/mnt' : '-';
            })
            ->editColumn('temperature', function ($row) {
                return $row->temperature ? $row->temperature . ' °C' : '-';
            })
            ->editColumn('respiration', function ($row) {
                return $row->respiration ? $row->respiration . ' x/mnt' : '-';
            })
            ->editColumn('weight', function ($row) {
                return $row->weight ? $row->weight . ' kg' : '-';
            })
            ->editColumn('height', function ($row) {
                return $row->height ? $row->height . ' cm' : '-';
            })
            ->make(true);
    }

    /**
     * Get lab data for DataTable
     */
    public function getLabData(Request $request, $patientId)
    {
        $pasien = Patient::findOrFail($patientId);

        $query = OrderLaboratorium::with([
            'registration',
            'order_parameter_laboratorium.parameter_laboratorium'
        ])
            ->whereHas('registration', function ($q) use ($pasien) {
                $q->where('patient_id', $pasien->id);
            });

        return datatables()->of($query)
            ->editColumn('order_date', function ($row) {
                return $row->created_at?->format('d M Y H:i') ?? '-';
            })
            ->addColumn('order_number', function ($row) {
                return $row->no_order ?? '-';
            })
            ->addColumn('examination', function ($row) {
                if ($row->order_parameter_laboratorium && $row->order_parameter_laboratorium->count()) {
                    return $row->order_parameter_laboratorium->pluck('parameter_laboratorium.parameter')->filter()->implode(', ') ?: '-';
                }
                return '-';
            })
            ->addColumn('result', function ($row) {
                // Gabungkan parameter dan hasil, atau tampilkan status ringkas jika tidak ada detail hasil
                if ($row->order_parameter_laboratorium && $row->order_parameter_laboratorium->count()) {
                    // Asumsikan ada attribute 'hasil' pada order_parameter_laboratorium
                    return $row->order_parameter_laboratorium->map(function ($detail) {
                        $param = $detail->parameter_laboratorium->parameter ?? '-';
                        $hasil = $detail->hasil ?? 'Belum ada hasil';
                        return $param . ': ' . $hasil;
                    })->implode('<br>');
                }
                return 'Belum ada hasil';
            })
            ->addColumn('status', function ($row) {
                $status = $row->status_isi_hasil ?? '1';
                $statusText = match ($status) {
                    1 => 'Selesai',
                    0 => 'Belum',
                    default => 'Belum'
                };
                return $statusText;
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('laboratorium.order', $row->id) . '" class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . route('laboratorium.nota-order', $row->id) . '" class="btn btn-sm btn-primary" title="Cetak" target="_blank">
                        <i class="fas fa-print"></i>
                    </a>
                ';
            })
            ->rawColumns(['result', 'status', 'action'])
            ->make(true);
    }

    /**
     * Get radiology data for DataTable
     */
    public function getRadiologyData(Request $request, $patientId)
    {
        $pasien = Patient::findOrFail($patientId);

        $query = OrderRadiologi::with([
            'registration',
            'order_parameter_radiologi.parameter_radiologi'
        ])
            ->whereHas('registration', function ($q) use ($pasien) {
                $q->where('patient_id', $pasien->id);
            });

        return datatables()->of($query)
            ->editColumn('order_date', function ($row) {
                return $row->created_at?->format('d M Y H:i') ?? '-';
            })
            ->addColumn('order_number', function ($row) {
                return $row->no_order ?? '-';
            })
            ->addColumn('examination', function ($row) {
                if ($row->order_parameter_radiologi && $row->order_parameter_radiologi->count()) {
                    return $row->order_parameter_radiologi->pluck('parameter_radiologi.nama_parameter')->filter()->implode(', ') ?: '-';
                }
                return '-';
            })
            ->addColumn('result', function ($row) {
                if ($row->order_parameter_radiologi && $row->order_parameter_radiologi->count()) {
                    // Asumsikan attribute 'hasil' ada pada order_parameter_radiologi
                    return $row->order_parameter_radiologi->map(function ($detail) {
                        $param = $detail->parameter_radiologi->nama_parameter ?? '-';
                        $hasil = $detail->hasil ?? 'Belum ada hasil';
                        return $param . ': ' . $hasil;
                    })->implode('<br>');
                }
                return 'Belum ada hasil';
            })
            ->addColumn('status', function ($row) {
                $status = $row->status ?? 'pending';
                $badgeClass = match ($status) {
                    'completed' => 'success',
                    'processing' => 'warning',
                    'pending' => 'secondary',
                    default => 'secondary'
                };
                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('order-radiology.show', $row->id) . '" class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . route('order-radiology.print', $row->id) . '" class="btn btn-sm btn-primary" title="Cetak" target="_blank">
                        <i class="fas fa-print"></i>
                    </a>
                ';
            })
            ->rawColumns(['result', 'status', 'action'])
            ->make(true);
    }

    /**
     * Get registration details (AJAX)
     */
    public function getRegistrationDetails($registrationId)
    {
        $registration = Registration::with([
            'order_tindakan_medis',
            'cppt',
            'order_laboratorium.order_parameter_laboratorium.parameter_laboratorium',
            'order_radiologi.order_parameter_radiologi.parameter_radiologi'
        ])->findOrFail($registrationId);

        $html = view('app-type.simrs.pasien.registration-details', compact('registration'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}
