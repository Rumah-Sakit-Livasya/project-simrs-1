<?php

namespace App\Models\SIMRS\Operasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SIMRS\Doctor;
use App\Models\User;

class ProsedurOperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prosedur_operasi';

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function orderOperasi()
    {
        return $this->belongsTo(OrderOperasi::class, 'order_operasi_id');
    }

    public function tindakanOperasi()
    {
        return $this->belongsTo(TindakanOperasi::class, 'tindakan_operasi_id');
    }

    // Tim Operasi Relations
    public function dokterOperator()
    {
        return $this->belongsTo(Doctor::class, 'dokter_operator_id');
    }
    public function assDokterOperator1()
    {
        return $this->belongsTo(Doctor::class, 'dokter_operator1_id'); // adjust the foreign key as needed
    }
    public function assDokterOperator2()
    {
        return $this->belongsTo(Doctor::class, 'dokter_operator2_id'); // adjust the foreign key as needed
    }
    public function assDokterOperator3()
    {
        return $this->belongsTo(Doctor::class, 'dokter_operator3_id'); // adjust the foreign key as needed
    }

    public function assDokterOperator()
    {
        return $this->belongsTo(Doctor::class, 'ass_dokter_operator_id');
    }

    public function dokterAnastesi()
    {
        return $this->belongsTo(Doctor::class, 'dokter_anastesi_id');
    }

    public function assDokterAnastesi()
    {
        return $this->belongsTo(Doctor::class, 'ass_dokter_anastesi_id');
    }

    public function dokterResusitator()
    {
        return $this->belongsTo(Doctor::class, 'dokter_resusitator_id');
    }

    public function dokterTambahan()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan_id');
    }
    public function dokterTambahan1()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan1_id');
    }
    public function dokterTambahan2()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan2_id');
    }
    public function dokterTambahan3()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan3_id');
    }
    public function dokterTambahan4()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan4_id');
    }
    public function dokterTambahan5()
    {
        return $this->belongsTo(Doctor::class, 'dokter_tambahan5_id');
    }
    // Audit Relations
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getDurasiOperasiAttribute()
    {
        if ($this->waktu_mulai && $this->waktu_selesai) {
            return $this->waktu_mulai->diffInMinutes($this->waktu_selesai);
        }
        return null;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'rencana' => 'badge-secondary',
            'berlangsung' => 'badge-warning',
            'selesai' => 'badge-success',
            'batal' => 'badge-danger'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDokter($query, $doctorId)
    {
        return $query->where(function ($q) use ($doctorId) {
            $q->where('dokter_operator_id', $doctorId)
                ->orWhere('ass_dokter_operator_id', $doctorId)
                ->orWhere('dokter_anastesi_id', $doctorId)
                ->orWhere('ass_dokter_anastesi_id', $doctorId)
                ->orWhere('dokter_resusitator_id', $doctorId)
                ->orWhere('dokter_tambahan_id', $doctorId);
        });
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'berlangsung');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
