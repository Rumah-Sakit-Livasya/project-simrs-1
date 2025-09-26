<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifVisiteDokter extends Model
{
    use HasFactory;

    protected $table = 'tarif_visite_dokters';
    protected $guarded = ['id'];

    /**
     * Boot the model.
     * Secara otomatis menghitung total setiap kali data disimpan.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->total = $model->share_rs + $model->share_dr + $model->prasarana;
            if (auth()->check() && !$model->created_by) {
                $model->created_by = auth()->id();
            }
        });
    }

    /**
     * Relasi ke model Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Relasi ke model KelasRawat
     */
    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }

    /**
     * Relasi ke user yang membuat data
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
