<?php
// app/Models/ChildInitialAssessment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildInitialAssessment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'child_initial_assessments';
    protected $guarded = ['id'];
    protected $casts = [
        'info_masuk_ruangan' => 'array',
        'obat_dibawa' => 'array',
        'riwayat_kesehatan' => 'array',
        'riwayat_kesehatan_lalu' => 'array',
        'riwayat_kesehatan_keluarga' => 'array',
        'riwayat_psikososial' => 'array',
        'riwayat_komunikasi' => 'array',
        'riwayat_kebudayaan' => 'array',
        'riwayat_kelahiran_imunisasi' => 'array',
        'riwayat_tumbuh_kembang' => 'array',
        'respon_emosi_kognitif' => 'array',
        'informasi_diinginkan' => 'array',
        'nutrisi' => 'array',
        'eliminasi' => 'array',
        'personal_hygiene' => 'array',
        'istirahat_tidur' => 'array',
        'aktivitas_latihan' => 'array',
        'neuro_cerebral' => 'array',
        'tingkat_kesadaran' => 'array',
        'pemeriksaan_fisik' => 'array',
        'asesmen_nyeri_anak' => 'array',
        'resiko_jatuh_anak' => 'array',
        'masalah_keperawatan' => 'array',
    ];

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
