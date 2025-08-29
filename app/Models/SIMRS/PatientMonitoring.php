<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PatientMonitoring extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'patient_monitoring';

    protected $guarded = ['id'];

    protected $casts = [
        'monitoring_stages' => 'array',
        'stage_status' => 'array',
        'stage_timestamps' => 'array',
        'stage_completed_by' => 'array',
        'stage_notes' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    /**
     * Get the default monitoring stages
     */
    public static function getDefaultStages()
    {
        return [
            'pengkajian_awal' => 'Pengkajian Awal',
            'pengkajian_dokter' => 'Pengkajian Dokter',
            'resume_medis' => 'Resume Medis',
            'diagnosa' => 'Diagnosa',
            'tindakan' => 'Tindakan',
            'resep' => 'Resep',
            'obat_alkes_ruangan' => 'Obat/Alkes Ruangan',
            'bhp' => 'BHP',
            'laboratorium' => 'Laboratorium',
            'radiologi' => 'Radiologi',
            'fisio' => 'Fisioterapi',
            'hemodialisa' => 'Hemodialisa',
            'keluar' => 'Keluar',
            'tagihan' => 'Tagihan'
        ];
    }

    /**
     * Initialize monitoring stages for a registration
     */
    public static function initializeStages($registrationId)
    {
        $stages = self::getDefaultStages();
        $stageStatus = array_fill_keys(array_keys($stages), 'pending');
        $stageTimestamps = array_fill_keys(array_keys($stages), null);
        $stageCompletedBy = array_fill_keys(array_keys($stages), null);
        $stageNotes = array_fill_keys(array_keys($stages), null);

        return [
            'monitoring_stages' => $stages,
            'stage_status' => $stageStatus,
            'stage_timestamps' => $stageTimestamps,
            'stage_completed_by' => $stageCompletedBy,
            'stage_notes' => $stageNotes,
        ];
    }

    /**
     * Update stage status
     */
    public function updateStageStatus($stage, $status, $userId = null, $notes = null)
    {
        $stageStatuses = $this->stage_status ?? [];
        $stageTimestamps = $this->stage_timestamps ?? [];
        $stageCompletedBy = $this->stage_completed_by ?? [];
        $stageNotes = $this->stage_notes ?? [];

        $stageStatuses[$stage] = $status;
        $stageTimestamps[$stage] = $status === 'completed' ? now() : null;
        $stageCompletedBy[$stage] = $status === 'completed' ? $userId : null;
        $stageNotes[$stage] = $notes;

        $this->update([
            'stage_status' => $stageStatuses,
            'stage_timestamps' => $stageTimestamps,
            'stage_completed_by' => $stageCompletedBy,
            'stage_notes' => $stageNotes,
        ]);

        return $this;
    }

    /**
     * Get stage status
     */
    public function getStageStatus($stage)
    {
        return $this->stage_status[$stage] ?? 'pending';
    }

    /**
     * Check if all stages are completed
     */
    public function isAllStagesCompleted()
    {
        $stageStatuses = $this->stage_status ?? [];
        foreach ($stageStatuses as $status) {
            if ($status !== 'completed') {
                return false;
            }
        }
        return true;
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage()
    {
        $stageStatuses = $this->stage_status ?? [];
        $totalStages = count($stageStatuses);
        $completedStages = count(array_filter($stageStatuses, function ($status) {
            return $status === 'completed';
        }));

        return $totalStages > 0 ? round(($completedStages / $totalStages) * 100, 2) : 0;
    }
}
