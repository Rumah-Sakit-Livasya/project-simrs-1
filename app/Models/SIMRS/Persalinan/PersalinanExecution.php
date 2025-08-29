<?php

namespace App\Models\SIMRS\Persalinan;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersalinanExecution extends Model
{
    use SoftDeletes;

    protected $table = 'persalinan_execution';

    protected $fillable = [
        'execution_number',
        'order_persalinan_id',
        'registration_id',
        'tanggal_persalinan_actual',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'dokter_operator_actual',
        'asisten_operator_actual',
        'dokter_anestesi_actual',
        'asisten_anestesi_actual',
        'dokter_resusitator_actual',
        'asisten_resusitator_actual',
        'dokter_umum_actual',
        'ruang_bersalin_actual',
        'observation_room_actual',
        'hasil_persalinan',
        'jumlah_bayi',
        'jenis_kelamin_bayi',
        'berat_bayi',
        'panjang_bayi',
        'apgar_score_1',
        'apgar_score_5',
        'komplikasi',
        'catatan_medis',
        'instruksi_pasca_persalinan',
        'status',
        'verified_by',
        'verified_at',
        'biaya_operator_actual',
        'biaya_anestesi_actual',
        'biaya_ruang_actual',
        'biaya_total_actual'
    ];

    protected $casts = [
        'tanggal_persalinan_actual' => 'datetime',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'verified_at' => 'datetime',
        'berat_bayi' => 'decimal:2',
        'biaya_operator_actual' => 'decimal:2',
        'biaya_anestesi_actual' => 'decimal:2',
        'biaya_ruang_actual' => 'decimal:2',
        'biaya_total_actual' => 'decimal:2'
    ];

    // Auto generate execution number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->execution_number) {
                $model->execution_number = 'EXE-VK-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        static::saving(function ($model) {
            // Auto calculate duration
            if ($model->jam_mulai && $model->jam_selesai) {
                $start = \Carbon\Carbon::parse($model->jam_mulai);
                $end = \Carbon\Carbon::parse($model->jam_selesai);
                $model->durasi_menit = $end->diffInMinutes($start);
            }
        });
    }

    // Relationships
    public function orderPersalinan(): BelongsTo
    {
        return $this->belongsTo(OrderPersalinan::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function dokterOperatorActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'dokter_operator_actual');
    }

    public function asistenOperatorActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'asisten_operator_actual');
    }

    public function dokterAnestesiActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'dokter_anestesi_actual');
    }

    public function asistenAnestesiActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'asisten_anestesi_actual');
    }

    public function dokterResusitatorActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'dokter_resusitator_actual');
    }

    public function asistenResusitatorActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'asisten_resusitator_actual');
    }

    public function dokterUmumActual(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'dokter_umum_actual');
    }

    public function ruangBersalinActual(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'ruang_bersalin_actual');
    }

    public function observationRoomActual(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'observation_room_actual');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Accessors
    public function getHasilPersalinanLabelAttribute()
    {
        $labels = [
            'live_birth' => 'Kelahiran Hidup',
            'stillbirth' => 'Lahir Mati',
            'abortion' => 'Keguguran'
        ];

        return $labels[$this->hasil_persalinan] ?? $this->hasil_persalinan;
    }

    public function getDurasiFormatAttribute()
    {
        if (!$this->durasi_menit) return '-';

        $hours = floor($this->durasi_menit / 60);
        $minutes = $this->durasi_menit % 60;

        return $hours > 0 ? "{$hours} jam {$minutes} menit" : "{$minutes} menit";
    }
}
