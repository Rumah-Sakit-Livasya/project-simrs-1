<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengkajianDokterIGD extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengkajian_dokter_igd';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // Menggunakan guarded untuk memperbolehkan semua field diisi, kecuali 'id'.
    // Ini lebih mudah untuk form yang besar.
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_generalis' => 'array',
        'pemeriksaan_penunjang' => 'array',
        'tindak_lanjut' => 'array',
        'edukasi_penerima' => 'array',
        'edukasi_tidak_dapat_diberikan' => 'boolean',
    ];

    /**
     * Get the registration record associated with the assessment.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Get the user who created the record.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
